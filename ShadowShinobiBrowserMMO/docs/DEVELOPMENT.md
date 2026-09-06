# Shadow Shinobi Development Guide

## What this repository is

Shadow Shinobi is a browser-based persistent squad RPG built from the open-source Oyatsumi/NarutoBrowserMmorpg engine. The original runtime is PHP + MySQL/MariaDB with HTML/CSS/JavaScript presentation.

The active legacy runtime is:

`src/0-August-2018(latest)`

Older dated source trees are historical snapshots and are not the active compatibility target.

## Local runtime

The supported development stack is Docker Compose:

```text
Browser
  |
  v
PHP 8.2 + Apache  :8080
  |
  v
MariaDB 11
```

Start the stack from `ShadowShinobiBrowserMMO`:

```powershell
docker compose up -d --build
```

Open:

`http://localhost:8080`

The database service is initialized from:

`database (sql)/shadow_shinobi.sql`

The database volume is named `shadow-db` and persists between normal `docker compose up/down` cycles.

### Full database reset

Only use this when intentionally throwing away the local database state:

```powershell
docker compose down -v
docker compose up -d --build
```

## Database connection

The web container receives these environment variables:

- `DB_HOST=db`
- `DB_USER=shadow`
- `DB_PASSWORD=shadow_dev_password`
- `DB_NAME=shadow_shinobi`
- `DB_PREFIX=dk`
- `GAME_SECRET` for legacy session-cookie signing compatibility

The `dk` prefix is part of the existing engine/database contract and must not be changed casually.

## Database inspection

The Compose stack includes a MariaDB container. For direct inspection from the container network:

```powershell
docker compose exec db mariadb -u shadow -pshadow_dev_password shadow_shinobi
```

Useful first checks:

```sql
SHOW TABLES;
SELECT COUNT(*) FROM dk_users;
SELECT * FROM dk_control LIMIT 1;
```

Never copy production credentials into source control. Local development credentials are intentionally disposable.

## Runtime architecture

The current modernization strategy is deliberately incremental:

1. Keep the legacy gameplay/data contracts working.
2. Put PHP 8 compatibility fixes at the runtime boundary.
3. Modernize presentation without changing game rules unnecessarily.
4. Map each gameplay subsystem to its real PHP entry points and database tables.
5. Add regression checks before deeper engine refactors.
6. Replace historical/Naruto-specific content only after its dependencies are understood.

This keeps the project playable while we learn the engine instead of creating a new framework before we know what needs preserving.

## Development milestones

### Phase A — Runtime baseline

- Docker web container starts.
- MariaDB starts.
- SQL seed imports.
- Login page renders.
- Registration and authentication can be exercised.

### Phase B — Gameplay verification

Create a disposable test account and walk through every discovered subsystem. Record the result as `WORKING`, `BROKEN`, `PARTIAL`, or `UNUSED/LEGACY`.

### Phase C — Safe creative changes

Creative work starts only after:

- the database can be recreated from the repository;
- login/session flow is verified;
- the main navigation is mapped;
- core PvE/combat flow is verified;
- character/equipment/inventory state can be persisted;
- failures can be reproduced locally.

At that point the project is safe to redesign aggressively because we have a known baseline to compare against.

## Hosting direction

Vercel is not the primary runtime target for the current stack because this project is a PHP + persistent MariaDB application represented as a multi-container Compose deployment.

Recommended topology:

```text
Public domain
    |
    +--> web application/container host (PHP + Apache)
    |
    +--> managed MariaDB/MySQL database

Optional:
    Vercel -> landing page, documentation, or a separate modern frontend
```

Do not publish the local development `GAME_SECRET` or development database password.

## Attribution and licensing

See `LICENSE` and the original `README.md`. The original project is identified as MIT licensed in its source documentation. Third-party artwork and any replacement assets still require a separate asset/license audit before public or commercial release.
