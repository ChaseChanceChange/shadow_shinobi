-- Shadow Shinobi content migration 003
-- Final player-facing content pass for the bundled legacy seed database.
-- Do not rename legacy schema identifiers, routes, IDs, or state values.

START TRANSACTION;

-- Final settlement names.
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
  WHEN 1 THEN 'Blackleaf Warden'
  WHEN 2 THEN 'Peak Warden'
  WHEN 3 THEN 'Stonecrest Warden'
  WHEN 4 THEN 'Mistveil Warden'
  WHEN 5 THEN 'Dune Warden'
  ELSE kage
END
WHERE id IN (1,2,3,4,5);

-- Full item catalogue retheme. IDs, prices, attributes, and types are intact.
UPDATE dk_items
SET name = CASE id
  WHEN 1 THEN 'Field Bandage'
  WHEN 2 THEN 'Focus Glove'
  WHEN 3 THEN 'Steel Dart'
  WHEN 4 THEN 'Throwing Star'
  WHEN 5 THEN 'Twin-Edge Knife'
  WHEN 6 THEN 'Double-Edge Blade'
  WHEN 7 THEN 'Bone Talon'
  WHEN 8 THEN 'Burst Knife'
  WHEN 9 THEN 'Gale Disc'
  WHEN 10 THEN 'Wind Fan'
  WHEN 11 THEN 'Blackguard Dagger'
  WHEN 12 THEN 'Storm Blades'
  WHEN 13 THEN 'Blast Seal Scroll'
  WHEN 14 THEN 'Tidefang'
  WHEN 15 THEN 'Focus Blade'
  WHEN 16 THEN 'Shadowblade Relic'
  WHEN 17 THEN 'Basic Tunic'
  WHEN 18 THEN 'Blackleaf Tunic'
  WHEN 19 THEN 'Initiate Vest'
  WHEN 20 THEN 'Shadowblade Garb'
  WHEN 21 THEN 'Adept Vest'
  WHEN 22 THEN 'Sacred Blackleaf Mantle'
  WHEN 23 THEN 'Blackguard Vest'
  WHEN 24 THEN 'Focus Mantle'
  WHEN 25 THEN 'Warplate'
  WHEN 26 THEN 'Nightfall Mantle'
  WHEN 27 THEN 'Warden Aegis'
  WHEN 28 THEN 'Shadowblade Hood'
  WHEN 29 THEN 'White Guard'
  WHEN 30 THEN 'Blackleaf Guard'
  WHEN 31 THEN 'Blackguard Mask'
  WHEN 32 THEN 'Nightfall Hat'
  WHEN 33 THEN 'Warden Helm'
  WHEN 34 THEN 'Twin Storm Blades'
  WHEN 35 THEN 'Twin Katanas'
  WHEN 36 THEN 'Dune Guard'
  WHEN 37 THEN 'Mistveil Guard'
  WHEN 38 THEN 'Echo Guard'
  WHEN 39 THEN 'Dune Warden Helm'
  WHEN 40 THEN 'Stone Warden Helm'
  WHEN 41 THEN 'Hunter Mask'
  WHEN 42 THEN 'Mist Spirit Garb'
  WHEN 43 THEN 'Blackleaf Honor Guard'
  WHEN 44 THEN 'Junior Guard Shirt'
  WHEN 45 THEN 'Guardian Staff'
  WHEN 46 THEN 'Mistveil Honor Guard'
  ELSE CONCAT('Gear ', id)
END;

-- Ability catalogue. Keep the spell mechanics and IDs untouched; only the display names change.
UPDATE dk_spells
SET name = CONCAT('Shadow Art ', LPAD(id, 2, '0'));

-- Historical seed accounts are test data. Keep credentials/IDs intact but remove
-- franchise character names from any player-facing character field.
UPDATE dk_users
SET
  charname = CONCAT('Operative ', id),
  batalha_nome = 'None',
  ultimoinimigo = 'None'
WHERE regdate < '2020-01-01 00:00:00';

COMMIT;

-- Validation:
-- SELECT id,name FROM dk_items ORDER BY id;
-- SELECT id,name FROM dk_spells ORDER BY id;
-- SELECT id,charname FROM dk_users WHERE regdate < '2020-01-01 00:00:00' ORDER BY id;
