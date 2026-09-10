<?php
declare(strict_types=1);

namespace ShadowShinobi\Enhancement;

use RuntimeException;
use ShadowShinobi\Core\Database;
use ShadowShinobi\WorldMemory\EventRecorder;

/**
 * Server-authoritative enhancement ritual.
 *
 * A ritual consumes the selected target plus at least two additional gear
 * pieces. On success a new enhanced copy is created; on a high-risk failure
 * the selected target can be destroyed. Fragments and World Memory are
 * recorded within the same transaction so the result cannot partially apply.
 */
final class EnhancementService
{
    public static function enhance(int $playerId, int $itemId, array $sacrificeIds): array
    {
        $pdo = Database::connection();
        $config = require '/var/www/config/app.php';
        $minimumPieces = (int)($config['enhancement']['minimum_sacrifice_pieces'] ?? 3);

        $allIds = array_values(array_unique(array_map('intval', array_merge([$itemId], $sacrificeIds))));
        if (count($allIds) < $minimumPieces) {
            throw new RuntimeException("Enhancement requires at least {$minimumPieces} total gear pieces, including the selected item.");
        }
        if (in_array($itemId, $sacrificeIds, true)) {
            throw new RuntimeException('The selected item cannot also be listed as a separate sacrifice.');
        }

        $pdo->beginTransaction();
        try {
            $placeholders = implode(',', array_fill(0, count($allIds), '?'));
            $stmt = $pdo->prepare("SELECT * FROM equipment_items WHERE player_id=? AND id IN ({$placeholders}) AND destroyed=0 FOR UPDATE");
            $stmt->execute(array_merge([$playerId], $allIds));
            $items = $stmt->fetchAll();
            if (count($items) !== count($allIds)) {
                throw new RuntimeException('One or more selected gear pieces are unavailable.');
            }

            $selected = null;
            foreach ($items as $item) {
                if ((int)$item['id'] === $itemId) {
                    $selected = $item;
                    break;
                }
            }
            if ($selected === null) {
                throw new RuntimeException('Selected gear was not found.');
            }

            $nextLevel = (int)$selected['enhancement_level'] + 1;
            $band = $nextLevel >= 16 ? 16 : ($nextLevel >= 11 ? 15 : ($nextLevel >= 6 ? 10 : 5));
            $odds = $config['enhancement'][$band] ?? ['success' => 0.5, 'destroy' => 0.25];

            $success = random_int(1, 1_000_000) / 1_000_000 <= (float)$odds['success'];
            $destroyed = !$success && random_int(1, 1_000_000) / 1_000_000 < (float)$odds['destroy'];

            $totalSetValue = 0;
            foreach ($items as $item) {
                $totalSetValue += (int)$item['set_value'];
            }

            $pdo->prepare("UPDATE equipment_items SET destroyed=1 WHERE player_id=? AND id IN ({$placeholders})")
                ->execute(array_merge([$playerId], $allIds));

            $fragmentCount = 0;
            foreach ($items as $item) {
                $fragmentCount += max(1, (int)$item['set_value']);
            }
            $pdo->prepare('INSERT INTO gear_fragments (player_id, source_item_id, quantity, reason) VALUES (?,?,?,?)')
                ->execute([$playerId, $itemId, $fragmentCount, $success ? 'enhancement_sacrifice' : 'enhancement_failure']);
            $pdo->prepare('UPDATE players SET gear_fragments = gear_fragments + ? WHERE id=?')
                ->execute([$fragmentCount, $playerId]);

            $newItemId = null;
            $awakenedNow = false;
            if ($success) {
                $awakened = (int)$selected['awakened'];
                if ($nextLevel >= 16 && random_int(1, 100) <= 8) {
                    $awakened = 1;
                    $awakenedNow = true;
                }

                $stmt = $pdo->prepare('INSERT INTO equipment_items
                    (player_id, operative_id, item_name, slot_name, rarity, enhancement_level,
                     awakened, is_core_weapon, weapon_family, talent_tree_key, main_stat_name,
                     main_stat_value, substats_json, visual_json, set_value, destroyed, origin_item_id)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
                $stmt->execute([
                    $playerId,
                    $selected['operative_id'],
                    $selected['item_name'],
                    $selected['slot_name'],
                    $selected['rarity'],
                    $nextLevel,
                    $awakened,
                    $selected['is_core_weapon'],
                    $selected['weapon_family'],
                    $selected['talent_tree_key'],
                    $selected['main_stat_name'],
                    (float)$selected['main_stat_value'] * 1.12,
                    $selected['substats_json'],
                    $selected['visual_json'],
                    (int)$selected['set_value'],
                    0,
                    $itemId,
                ]);
                $newItemId = (int)$pdo->lastInsertId();

                $title = $awakenedNow ? 'A Weapon Awoke' : 'The Forge Answered';
                $summary = sprintf('%s emerged at +%d after %d gear pieces were sacrificed.', $selected['item_name'], $nextLevel, count($items));
                $eventId = EventRecorder::recordInTransaction($pdo, $playerId, 'gear_enhanced', $title, $summary, [
                    'item_id' => $newItemId,
                    'origin_item_id' => $itemId,
                    'sacrifice_ids' => $allIds,
                    'enhancement_level' => $nextLevel,
                    'set_value' => $totalSetValue,
                    'awakened' => $awakenedNow,
                ]);
            } else {
                $eventId = EventRecorder::recordInTransaction($pdo, $playerId, 'gear_destroyed', 'A Relic Was Lost', sprintf('%s was destroyed during a failed enhancement attempt.', $selected['item_name']), [
                    'item_id' => $itemId,
                    'sacrifice_ids' => $allIds,
                    'attempted_level' => $nextLevel,
                    'destroyed' => $destroyed,
                    'set_value' => $totalSetValue,
                    'lost_relic_candidate' => $destroyed && $totalSetValue >= 3,
                ]);
                if ($destroyed && $totalSetValue >= 3) {
                    $pdo->prepare('INSERT INTO lost_relics (player_id, source_item_id, relic_name, power_factor, provenance_json, status) VALUES (?,?,?,?,?,?)')
                        ->execute([
                            $playerId,
                            $itemId,
                            'Echo of ' . $selected['item_name'],
                            0.35,
                            json_encode(['event_id' => $eventId, 'source_item_id' => $itemId, 'enhancement_level' => (int)$selected['enhancement_level']], JSON_THROW_ON_ERROR),
                            'world_pool',
                        ]);
                }
            }

            $pdo->commit();
            return [
                'success' => $success,
                'destroyed' => $destroyed,
                'new_item_id' => $newItemId,
                'fragment_count' => $fragmentCount,
                'world_event_id' => $eventId,
                'attempted_level' => $nextLevel,
                'awakened' => $awakenedNow,
            ];
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
