# Shadow Shinobi — Architecture

## Purpose
Shadow Shinobi is a modernization of a legacy PHP/MySQL browser MMO (derived from the Oyatsumi/Naruto browser engine). The active goal is a **reproducible, playable development platform** on PHP 8.2 + MariaDB 11 while preserving the legacy gameplay engine.

Upstream reference: https://github.com/Oyatsumi/NarutoBrowserMmorpg  
Fork: https://github.com/ChaseChanceChange/shadow_shinobi

## Runtime stack
| Layer | Technology | Notes |
|---|---|---|
| App | PHP 8.2 + Apache (`php:8.2-apache`) | `mysqli` extension |
| DB | MariaDB 11 | Seeded from `database (sql)/shadow_shinobi.sql` |
| Orchestration | Docker Compose | `ShadowShinobiBrowserMMO/docker-compose.yml` |
| Compat boundary | `legacy_compat.php` via `auto_prepend_file` | GET/POST defaults, quiet display_errors, raw auth cookie capture |
| Auth cookie | `dkgame` | Legacy format: `{id} {username} {md5(password--secret)} {remember}` |

## Source layout
```
ShadowShinobiBrowserMMO/
  docker-compose.yml
  Dockerfile
  database (sql)/shadow_shinobi.sql
  src/
    legacy_compat.php          # baked into image for auto_prepend
    0-August-2018(latest)/     # active game document root (volume-mounted)
      index.php, login.php, cookies.php, lib.php, ...
      templates/, images/, layoutnovo/
  docs/                        # platform documentation
  scripts/smoke-test.sh
```

## Request path
1. Apache serves `/var/www/html` (mounted game tree).
2. PHP `auto_prepend_file` loads compatibility boundary.
3. Entry scripts include `lib.php` (DB helpers, `display()`/`parsetemplate()`).
4. Authenticated pages call `checkcookies()` then branch on `$_GET['do']`.

## Developer login
- Enabled only when `DEV_MODE=1`.
- Route: `GET /login.php?do=dev`
- Loads `DEV_USERNAME` (default `Oyatsumi`) from the seeded DB and issues a real `dkgame` cookie.
- Does not bypass the database; every system sees a normal player row.

## Future integrations (documented, not fully implemented)
- **Discord**: bot/API for announcements, account linking, status — see `docs/production-hosting.md`
- **Content Builder**: separate app validating items/enemies/quests against schema — see `docs/content-builder.md`
- **MCP server**: safe project tools (status, logs, lint, smoke, read-only DB) — see `docs/mcp-design.md`

## Non-goals (current phase)
- Wholesale engine rewrite or framework migration
- Gameplay balance changes
- Schema redesign
- Full English localization of DB content
