-- Shadow Shinobi content migration 002
-- Replace verified legacy world-facing seed text while preserving IDs,
-- coordinates, mechanics, and database column names.

START TRANSACTION;

UPDATE dk_monsters
SET name = CASE id
  WHEN 1 THEN 'Wild Hound'
  WHEN 2 THEN 'Grey Wolf'
  WHEN 3 THEN 'Crimson Wolf'
  WHEN 4 THEN 'Black Tiger'
  WHEN 5 THEN 'Veil Stalker'
  ELSE name
END
WHERE id IN (1,2,3,4,5);

UPDATE dk_towns
SET name = CASE id
  WHEN 1 THEN 'Blackleaf Enclave'
  WHEN 2 THEN 'Aether Peak'
  WHEN 3 THEN 'Stonecrest Enclave'
  WHEN 4 THEN 'Mistveil Enclave'
  WHEN 5 THEN 'Red Dune Enclave'
  ELSE name
END,
kage = CASE id
  WHEN 1 THEN 'Enclave Warden'
  WHEN 2 THEN 'Peak Sage'
  WHEN 3 THEN 'Stone Warden'
  WHEN 4 THEN 'Mist Warden'
  WHEN 5 THEN 'Dune Warden'
  ELSE kage
END
WHERE id IN (1,2,3,4,5);

COMMIT;
