# Developer workflow

## Prerequisites
- Docker + Docker Compose v2
- Ports free: `8080` (game), `8081` (phpMyAdmin)

## Clean startup
```bash
cd ShadowShinobiBrowserMMO
docker compose down -v          # optional: wipe DB volume
docker compose up -d --build
```

Wait until the DB is healthy, then open:
- Game: http://127.0.0.1:8080/
- phpMyAdmin: http://127.0.0.1:8081/

## Database initialization
On first boot, MariaDB loads:
`database (sql)/shadow_shinobi.sql` → schema + seed data (includes developer account `Oyatsumi`).

Environment (compose defaults):
```
DB_HOST=db
DB_USER=shadow
DB_PASSWORD=shadow_dev_password
DB_NAME=shadow_shinobi
DB_PREFIX=dk
GAME_SECRET=local-development-secret-change-me
DEV_MODE=1
DEV_USERNAME=Oyatsumi
```

## Developer login
1. Open http://127.0.0.1:8080/dev_health.php — expect “Core checks passing”.
2. Open http://127.0.0.1:8080/login.php?do=dev — should set cookie and land on the game shell.
3. Or use the “Developer login · local only” link on the login page.

Password for seeded `Oyatsumi` (if using form login): `123456` (MD5 in seed dump).

## Smoke tests
With the stack up:
```bash
# from repo root
bash ShadowShinobiBrowserMMO/scripts/smoke-test.sh
```

CI runs an equivalent flow in `.github/workflows/php-compat.yml`.

## Logs
```bash
cd ShadowShinobiBrowserMMO
docker compose logs -f web
docker compose logs -f db
```

PHP errors are logged server-side (`log_errors=1`); they are not displayed in HTML.

## Reset development data
```bash
cd ShadowShinobiBrowserMMO
docker compose down -v
docker compose up -d --build
```
`-v` removes the named volume `shadow-db` and reloads the seed SQL.

## Rebuild containers
```bash
docker compose build --no-cache
docker compose up -d
```

## PHP lint (local)
```bash
find 'ShadowShinobiBrowserMMO/src/0-August-2018(latest)' -name '*.php' -print0 | xargs -0 -n1 php -l
```
