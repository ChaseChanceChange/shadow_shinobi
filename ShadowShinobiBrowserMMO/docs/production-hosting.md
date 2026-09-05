# Production hosting notes

## Recommended shape
- VPS (or similar) running Docker Compose
- Reverse access via **Cloudflare Tunnel** (no public origin ports required)
- Managed or volume-backed MariaDB with scheduled backups
- Secrets only via environment / Docker secrets — never commit

## Domain & HTTPS
1. Point DNS at Cloudflare.
2. Run `cloudflared` tunnel to `http://web:80` inside the Compose network.
3. Enforce HTTPS at Cloudflare; origin may stay HTTP inside the tunnel.
4. Set cookie `secure` appropriately when the public site is HTTPS (the app already sets `secure` when `HTTPS` is detected).

## Environment secrets
| Variable | Purpose |
|---|---|
| `DB_HOST` / `DB_USER` / `DB_PASSWORD` / `DB_NAME` / `DB_PREFIX` | Database |
| `GAME_SECRET` | Auth cookie HMAC material — **rotate if leaked** |
| `DEV_MODE` | Must be `0` or unset in production |
| `DEV_USERNAME` | Irrelevant when DEV_MODE is off |

## Database backup
```bash
docker compose exec -T db mariadb-dump -u shadow -p"$DB_PASSWORD" shadow_shinobi \
  > backup-$(date +%Y%m%d).sql
```
Store off-box; test restores quarterly.

## Discord integration (future)
- Bot token + application ID as secrets
- Channels: announcements, support, status webhooks
- Optional account link table (do not overload `dk_users` without a migration plan)
- Never expose admin/dev login through Discord

## Checklist before public launch
- [ ] `DEV_MODE=0`
- [ ] Unique `GAME_SECRET`
- [ ] `verifyemail` / registration policy decided
- [ ] Backups automated
- [ ] Cloudflare WAF / rate limits for login and register
- [ ] Log retention and monitoring
