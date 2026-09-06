-- Shadow Shinobi content migration 004
-- Remove legacy player-facing defaults from new accounts and server control data.
-- Internal schema/field names remain unchanged for compatibility.

ALTER TABLE dk_users
  MODIFY graduacao varchar(30) NOT NULL DEFAULT 'Initiate';

UPDATE dk_control
SET
  gamename = 'Shadow Shinobi',
  gameurl = 'http://localhost:8080/',
  adminemail = 'admin@shadow-shinobi.local',
  class1name = 'Veilblade',
  class2name = 'Ironhand',
  class3name = 'Threadseer',
  diff1name = 'Steady',
  diff2name = 'Severe',
  diff3name = 'Deadly'
WHERE id = 1;
