# Shadow Shinobi — browser MMO resurrection

This working tree starts from **Oyatsumi/NarutoBrowserMmorpg** and is being modernized into an original browser-based squad RPG.

## Current foundation
- PHP + MySQL browser MMO architecture retained.
- Existing database schema retained as the first compatibility layer.
- PHP 8 compatibility fixes started.
- UTF-8 output enabled.
- Credentials moved to environment-variable defaults.
- English/neutral branding pass started.
- Legacy UI retained while a modern CSS layer is added.
- Genericized SQL seed created as `database (sql)/shadow_shinobi.sql`.

## Direction
The target is a persistent squad-management RPG inspired by the *gameplay loop* of old browser MMOs:

**build squad → train → equip → mission → battle → loot → improve → unlock → PvP/social → repeat**

The Naruto-specific identity is being removed from the game design. The repository's original MIT license and attribution remain in place; third-party/character artwork will be audited before any public/commercial release.

## Attribution
Original engine: Oyatsumi / NarutoBrowserMmorpg. See `LICENSE` and the original `README.md`.

## Translation status
The original engine is Portuguese. A transitional `i18n.php` layer now translates common UI labels and status text at render time. It is intentionally incremental: long quest/dialogue text still needs a proper content pass, which will be moved into database/content localization rather than left hard-coded in PHP.
