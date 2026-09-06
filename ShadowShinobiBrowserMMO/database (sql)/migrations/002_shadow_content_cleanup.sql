-- Shadow Shinobi content migration 002
--
-- Converts the bundled historical seed content into neutral Shadow Shinobi
-- content without changing the legacy dk_* schema, route names, IDs, or
-- combat/state contracts.
--
-- The historical seed users are only sanitized when their registration date
-- is before 2020-01-01. Newly created/current player accounts are left alone.
-- This keeps local development accounts from being rewritten by the cleanup.

-- Core game identity.
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

-- Settlement leadership labels. IDs remain unchanged because the game engine
-- addresses settlements numerically.
UPDATE dk_towns
SET kage = CASE id
  WHEN 1 THEN 'Blackleaf Warden'
  WHEN 2 THEN 'Spirit Warden'
  WHEN 3 THEN 'Stonecrest Warden'
  WHEN 4 THEN 'Mistveil Warden'
  WHEN 5 THEN 'Dune Warden'
  ELSE kage
END
WHERE id IN (1,2,3,4,5);

-- Replace every bundled drop name with a deterministic Shadow name derived
-- from its preserved gameplay attributes. Stats and IDs remain untouched.
UPDATE dk_drops
SET name = CASE
  WHEN attribute1 LIKE 'maxhp,%' THEN CONCAT('Vitality Fragment ', id)
  WHEN attribute1 LIKE 'maxmp,%' THEN CONCAT('Essence Fragment ', id)
  WHEN attribute1 LIKE 'attackpower,%' THEN CONCAT('Force Sigil ', id)
  WHEN attribute1 LIKE 'defensepower,%' THEN CONCAT('Guard Sigil ', id)
  WHEN attribute1 LIKE 'strength,%' THEN CONCAT('Might Stone ', id)
  WHEN attribute1 LIKE 'dexterity,%' THEN CONCAT('Finesse Stone ', id)
  WHEN attribute1 LIKE 'gold,%' THEN CONCAT('Coin Cache ', id)
  ELSE CONCAT('Recovered Relic ', id)
END;

-- Replace every bundled monster name with a clean Shadow designation while
-- preserving all combat stats and the original monster IDs.
UPDATE dk_monsters
SET name = CONCAT(
  CASE LOWER(elemento)
    WHEN 'fogo' THEN 'Ember'
    WHEN 'agua' THEN 'Tide'
    WHEN 'vento' THEN 'Gale'
    WHEN 'terra' THEN 'Stone'
    WHEN 'raio' THEN 'Storm'
    ELSE 'Neutral'
  END,
  ' Threat ', LPAD(id, 3, '0')
);

-- Historical news/chat seed data is old test conversation, not game content.
-- Remove it so Portuguese/franchise chatter cannot leak into the player UI.
DELETE FROM dk_babble;
DELETE FROM dk_chatmap;
DELETE FROM dk_news;
INSERT INTO dk_news (id, postdate, content)
VALUES (1, NOW(), 'Shadow Shinobi is online. Enter the field, build your discipline, and shape the world through your choices.');

-- Hide historical seed characters from online-player presentation and remove
-- their old transient UI payloads. Current accounts (2020+) are untouched.
UPDATE dk_users
SET
  onlinetime = '0000-00-00 00:00:00',
  mainmsg = '',
  caixadepm = '',
  historico = '',
  techniquedebuscahtml = '',
  bancogeral = 'None',
  bp1 = 'None',
  bp2 = 'None',
  bp3 = 'None',
  bp4 = 'None',
  bpimagem = 'backpack1',
  trocajogador1 = 'None',
  trocajogador2 = 'None',
  trocaswitch = 0,
  dropcode = 0,
  graduacao = 'Initiate',
  treinamento = 'None',
  weaponname = CASE WHEN weaponid > 0 THEN CONCAT('Weapon Gear ', weaponid) ELSE 'None' END,
  armorname = CASE WHEN armorid > 0 THEN CONCAT('Armor Gear ', armorid) ELSE 'None' END,
  shieldname = CASE WHEN shieldid > 0 THEN CONCAT('Shield Gear ', shieldid) ELSE 'None' END,
  slot1name = CASE WHEN slot1id > 0 THEN CONCAT('Relic ', slot1id) ELSE 'None' END,
  slot2name = CASE WHEN slot2id > 0 THEN CONCAT('Relic ', slot2id) ELSE 'None' END,
  slot3name = CASE WHEN slot3id > 0 THEN CONCAT('Relic ', slot3id) ELSE 'None' END
WHERE regdate < '2020-01-01 00:00:00';

-- Verification examples:
-- SELECT id, gamename, class1name, class2name, class3name FROM dk_control WHERE id=1;
-- SELECT id, name FROM dk_towns ORDER BY id;
-- SELECT id, name FROM dk_drops ORDER BY id;
-- SELECT id, name FROM dk_monsters ORDER BY id;
-- SELECT COUNT(*) FROM dk_babble;
-- SELECT COUNT(*) FROM dk_chatmap;
-- SELECT COUNT(*) FROM dk_users WHERE regdate < '2020-01-01 00:00:00' AND onlinetime <> '0000-00-00 00:00:00';
