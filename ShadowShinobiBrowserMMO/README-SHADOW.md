# Shadow Shinobi — browser MMO resurrection

This working tree starts from **Oyatsumi/NarutoBrowserMmorpg** and is being modernized into an original browser-based squad RPG.

## Current foundation
- PHP + MySQL browser MMO architecture retained.
- Existing database schema retained as the first compatibility layer.
- Active 2018 runtime linted under PHP 8.2 in CI.
- Docker web image builds successfully in CI.
- UTF-8 output enabled.
- Credentials moved to environment-variable defaults.
- Hardened legacy `dkgame` authentication cookie handling without changing its stored format.
- PHP 8 request defaults added at the runtime boundary to eliminate legacy undefined-key warnings.
- Responsive Shadow Shinobi application shell replaces the fixed 2018 page chrome.
- Player, navigation, equipment, backpack, techniques, account, character, town, combat, duel, bank, and popup views have modern presentation layers.
- Legacy gameplay dispatch, database schema, combat flow, item identifiers, and form parameter contracts remain in place for compatibility.
- Genericized SQL seed created as `database (sql)/shadow_shinobi.sql`.

## Direction
The target is a persistent squad-management RPG inspired by the *gameplay loop* of old browser MMOs:

**build squad → train → equip → mission → battle → loot → improve → unlock → PvP/social → repeat**

The Naruto-specific identity is being removed from the game design. The repository's original MIT license and attribution remain in place; third-party/character artwork will be audited before any public/commercial release.

## Modernization boundary
The `0-August-2018(latest)` tree is the active runtime. Older dated source directories are retained as historical snapshots and are not part of the PHP 8 compatibility gate.

Modernization is deliberately being performed at the presentation/runtime boundary first. Deeper database and gameplay-engine refactors should preserve the current game rules and be introduced behind tests/regression checks rather than rewriting the legacy dispatcher wholesale.

## Attribution
Original engine: Oyatsumi / NarutoBrowserMmorpg. See `LICENSE` and the original `README.md`.

## Translation status
The original engine is Portuguese. A transitional `i18n.php` layer now translates common UI labels and status text at render time. Long quest/dialogue text remains a content-localization task and should move into database/content localization rather than stay hard-coded in PHP.
