# Future Shadow Shinobi MCP server (design)

## Goal
Expose **safe, project-specific** tools to AI agents (Cursor, Claude Code, etc.) for operating the Shadow Shinobi development platform—without arbitrary shell execution.

## Non-goals
- Generic `run_shell` / unrestricted filesystem write
- Live production mutation without explicit human approval
- Changing combat formulas or granting free resources in production

## Proposed tools
| Tool | Mode | Description |
|---|---|---|
| `ss_status` | read | Compose service health, DEV_MODE, DB ping |
| `ss_logs` | read | Tail `web`/`db` container logs (bounded) |
| `ss_php_lint` | read | Run `php -l` on active game tree; return failures |
| `ss_smoke` | read | Run `scripts/smoke-test.sh`; return pass/fail + excerpts |
| `ss_db_inspect` | read | Read-only SQL against allowlisted tables/columns |
| `ss_content_list` | read | List items, monsters, spells, towns from DB |
| `ss_content_validate` | read | Validate a content payload against schema rules |
| `ss_asset_validate` | read | Check referenced image paths exist under `images/` / `layoutnovo/` |
| `ss_git_status` | read | `git status` / `git diff --stat` for the repo worktree |

## Safety rules
1. No arbitrary command execution.
2. DB tool is **read-only** (SELECT only) with table allowlist.
3. Write tools (if added later) require a confirmed dry-run plan and branch protection.
4. Secrets (`GAME_SECRET`, DB passwords) are never returned in tool output.
5. Production profiles disable mutate-capable tools entirely.

## Implementation sketch
- Transport: MCP stdio or HTTP sidecar next to Compose.
- Auth: local-only bind + shared token for remote agents.
- Implementation language: Node or Python, calling existing scripts and `docker compose`.

## Phased delivery
1. **Phase A**: status, logs, php_lint, smoke, git_status  
2. **Phase B**: db_inspect, content_list, asset_validate  
3. **Phase C**: content_validate + Content Builder integration  
