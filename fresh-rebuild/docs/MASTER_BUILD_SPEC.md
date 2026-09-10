# Shadow Shinobi — Fresh Rebuild Master Specification

This branch stages a clean Shadow Shinobi architecture separate from the legacy implementation.

Core pillars:
- Squad and Operatives
- Gear and Equipment
- Sacrifice-based Enhancement
- Core Weapons with rarity-dependent Talent Trees
- World Memory
- Lost Relics created from major gear failures
- Living Wilds as a separate procedural/ecosystem game mode

Non-negotiables:
- 100% English maintained code, database, documentation and UI
- No legacy Portuguese naming
- No Naruto franchise identity in the new core
- Server-authoritative gameplay and RNG
- Configuration/data-driven systems
- Modular architecture
- Gear is a primary progression pillar

## Enhancement rule
An enhancement attempt consumes at least three equipment pieces, including the selected target. The target and sacrifices are consumed as part of the transaction. On success, the selected item's next enhancement state is created. On failure at configured high-risk tiers, the selected item may be destroyed. Consumed gear yields fragments/resources according to its value and state.

## Lost Relic rule
Major destroyed gear can generate a historical Lost Relic. The relic is deliberately weaker than the original, but carries provenance/history from the destroyed item. Its discovery can become a World Memory event and future game-wide content hook.

## Core Weapon rule
Operatives can have a personal Core Weapon. Equipping the relevant weapon unlocks its hidden Talent Tree. Tree depth/complexity is controlled by rarity and can expand later without changing the weapon engine.

## Living Wilds
Living Wilds is isolated from the normal deterministic mission loop. It is intended to simulate persistent creatures/resources/ecological activity and provide exploration/resource gameplay without making the main progression system dependent on procedural simulation.

## Asset strategy
The supplied Shadow Shinobi asset library is a source reservoir. Production assets should be approved, tagged, licensed, optimized and assigned to stable AST-* IDs. Gear visuals should be layered: base art + rarity + enhancement + effects + awakened overlays.

## First playable loop
Create/Log in -> Dashboard -> Recruit Operative -> Form Squad -> Equip Starter Gear -> Train -> Run Mission -> Resolve Combat -> Receive Loot -> Attempt Enhancement -> Record World Memory.
