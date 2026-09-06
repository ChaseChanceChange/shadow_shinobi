-- Shadow Shinobi content migration 001
-- Content-only identity pass.
--
-- This migration intentionally does NOT rename tables, columns, IDs, routes,
-- combat fields, or authentication fields. It changes the player-facing game
-- identity stored in dk_control and removes a few directly verified legacy
-- item-name references from the seed data.

START TRANSACTION;

UPDATE dk_control
SET
  gamename = 'Shadow Shinobi',
  class1name = 'Veilblade',
  class2name = 'Ironhand',
  class3name = 'Threadseer',
  diff1name = 'Steady',
  diff2name = 'Severe',
  diff3name = 'Deadly'
WHERE id = 1;

-- Directly verified seed names from the legacy drop table.
-- Keep IDs and attribute values intact; only the displayed names change.
UPDATE dk_drops
SET name = 'Veilborn Relic: Hide'
WHERE id = 7 AND name = 'Pele da Nine-Tail Relic';

UPDATE dk_drops
SET name = 'Veilborn Relic: Guard'
WHERE id = 8 AND name = 'Prote??o da Nine-Tail Relic';

UPDATE dk_drops
SET name = 'Veilborn Relic: Hope'
WHERE id = 9 AND name = 'Esperan?a da Nine-Tail Relic';

UPDATE dk_drops
SET name = 'Veilborn Relic: Fang'
WHERE id = 10 AND name = 'Dente da Nine-Tail Relic';

UPDATE dk_drops
SET name = 'Veilborn Relic: Focus'
WHERE id = 11 AND name = 'Focus da Nine-Tail Relic';

UPDATE dk_drops
SET name = 'Veilborn Relic: Tail'
WHERE id = 12 AND name = 'Cauda da Nine-Tail Relic';

UPDATE dk_drops
SET name = 'Triune Focus Stone'
WHERE id = 20 AND name = 'Pedra Sannin';

COMMIT;

-- Validation queries:
-- SELECT id, gamename, class1name, class2name, class3name,
--        diff1name, diff2name, diff3name FROM dk_control WHERE id = 1;
-- SELECT id, name, mlevel, type, attribute1, attribute2
--   FROM dk_drops WHERE id BETWEEN 7 AND 12 OR id = 20;
