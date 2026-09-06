# Shadow Shinobi Browser MMO

Shadow Shinobi is a persistent browser-based squad RPG built from an open-source PHP/JavaScript/CSS/HTML/SQL game engine.

The project is being progressively transformed into its own setting, terminology, presentation, content and artwork while keeping the proven gameplay machinery stable during the modernization pass.

## Current state

- **Runtime:** PHP 8.2 + Apache in Docker Compose
- **Database:** MariaDB
- **Active game source:** `src/0-August-2018(latest)`
- **Primary branch:** `modernize-safe-pass`
- **Database table prefix:** `dk` (preserved for compatibility)
- **UI identity:** Shadow Shinobi

## Shadow terminology

The player-facing vocabulary is being replaced with the Shadow Shinobi setting while engine identifiers remain stable.

| Legacy concept | Shadow Shinobi term |
| --- | --- |
| Player | Operative |
| Character | Operative Record |
| Mission / Quest | Contract |
| Training | Discipline |
| Jutsu / Technique | Art |
| Chakra | Essence |
| Village | Enclave |
| Kage / village leader | Warden |
| Rank / Level | Standing |
| Experience | Insight |
| Equipment | Gear |
| Inventory / Backpack | Pack |
| Bank | Vault |
| Drop | Recovery |
| Enemy / Monster | Threat |

These labels are presentation-layer terminology. Existing routes, database keys, state values and other engine contracts are intentionally preserved unless a dedicated migration explicitly changes them.

## Development approach

The modernization is being performed in controlled passes:

1. Keep the original gameplay and data contracts working.
2. Keep PHP 8 compatibility fixes at the runtime boundary.
3. Replace the visible presentation and terminology.
4. Replace franchise-specific content with Shadow Shinobi content using deterministic data changes.
5. Replace artwork and other third-party assets through a separate asset/license review.
6. Add regression checks before deeper mechanics changes.

Do not change the `dk_*` schema prefix, existing action routes, or combat state values casually. Those are part of the current engine contract.

## Asset replacement

Most artwork is referenced by an existing filename/path. A replacement image can generally be dropped in under the same path and filename so the PHP/HTML code does not need to change. Keep the expected file extension and use compatible dimensions/aspect ratio where practical.

Artwork is being treated separately from the code retheme so that gameplay logic can remain stable while the visual identity is rebuilt.

## Attribution and licensing

This project is derived from the open-source Oyatsumi/NarutoBrowserMmorpg codebase. The upstream project identifies the source code as MIT licensed and requests attribution.

The MIT notice for the applicable source remains part of this project. Third-party artwork, fonts, music, icons and replacement assets must be reviewed under their own licenses; an open-source engine license does not automatically license third-party assets.

## Local development

From the project directory:

```powershell
git switch modernize-safe-pass
git pull --ff-only origin modernize-safe-pass
docker compose up -d --build
docker compose ps
```

Then open:

`http://localhost:8080/`

The phpMyAdmin service is exposed at `http://localhost:8081/` in the current Docker setup.

## Credits

Original engine: Oyatsumi / contributors to the upstream browser MMO project.

Shadow Shinobi modernization, retheme and original content are maintained separately from the upstream game's identity and assets.
