# Content Builder (future application)

## Purpose
A **separate** application (not embedded in the legacy engine) for authors to create and validate game content that conforms to Shadow Shinobi’s schema and rules.

## Content domains
| Domain | Examples | Storage today |
|---|---|---|
| Items | weapons, armor, shields, costs | `dk_items` |
| Drops / relics | attribute packs | `dk_drops` |
| Enemies | monsters, HP, immunities | monster tables in seed |
| Abilities | spells / techniques | `dk_spells` |
| Quests / missions | stages, rewards | mission fields on users + mission tables |
| Locations | towns, map coords | `dk_towns` |
| Dialogue | NPC / story text | content tables / templates |
| Rewards | gold, exp, items | linked from quests/drops |
| Localization | UI strings vs DB lore | `i18n.php` transitional + future packs |
| Assets | GIF/PNG/JPG paths | `images/`, `layoutnovo/` |

## Validation rules (engine-facing)
1. **IDs**: unique within table; no reuse of deleted combat IDs without migration notes.
2. **Types**: item `type` in allowed set (weapon/armor/shield/etc.).
3. **Attributes**: only known attribute keys (`maxhp`, `strength`, `expbonus`, …).
4. **Economy**: buy costs non-negative; level gates consistent with `dk_levels`.
5. **Assets**: every referenced filename exists and is an allowed extension.
6. **Localization**: UI keys exist in the i18n map; lore text may stay source language until a translation pass.
7. **Gameplay safety**: no editor path silently changes player rows or combat formulas.

## Suggested pipeline
```
Author → Content Builder UI → JSON/YAML export → validate → SQL migration or import job → staging smoke → production
```

## Relationship to MCP
The future MCP `ss_content_validate` tool should call the same validator library the Builder uses, so AI and humans share one rule set.
