# Content Builder (future application)

## Purpose
A **separate** application (not embedded in the legacy engine) for authors to create and validate game content that conforms to Shadow Shinobi’s schema and rules.

The builder must treat the legacy engine as an execution target, not as the authoring model.

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

## Shadow authoring vocabulary
The canonical player-facing terminology lives in:

- `docs/SHADOW_TERMINOLOGY.md` — human-readable rules and examples.
- `docs/shadow-terminology.json` — machine-readable terminology for tools and AI agents.
- `docs/LORE_FOUNDATION.md` — current setting and canon rules.

The builder should load these definitions rather than hard-coding terminology into individual editor screens.

## Validation rules (engine-facing)
1. **IDs**: unique within table; no reuse of deleted combat IDs without migration notes.
2. **Types**: item `type` in allowed set (weapon/armor/shield/etc.).
3. **Attributes**: only known attribute keys (`maxhp`, `strength`, `expbonus`, …).
4. **Economy**: buy costs non-negative; level gates consistent with `dk_levels`.
5. **Assets**: every referenced filename exists and is an allowed extension.
6. **Localization**: UI keys exist in the i18n map; lore text may stay source language until a translation pass.
7. **Terminology**: published player-facing text uses the current Shadow Shinobi vocabulary and does not reintroduce legacy franchise-specific terms.
8. **Lore**: new content must fit `docs/LORE_FOUNDATION.md` or explicitly declare a future canon extension.
9. **Gameplay safety**: no editor path silently changes player rows or combat formulas.
10. **Migration safety**: every database change is exportable as a deterministic migration and is reversible where practical.

## Suggested pipeline
```text
Author / AI
    -> Content Builder UI
    -> schema validation
    -> terminology validation
    -> lore validation
    -> JSON export
    -> SQL migration/import job
    -> staging
    -> smoke test
    -> production
```

## Human and AI parity
Humans and AI agents must use the same content schema and validators.

An AI content prompt should explicitly instruct the agent to:

- preserve engine-facing IDs/types where required;
- use the Shadow Shinobi terminology map;
- write original setting/lore;
- avoid importing characters, factions, locations, or plot events from unrelated franchises;
- produce machine-readable content plus a short authoring note;
- run validation before proposing a publishable package.

## Relationship to MCP
The future MCP `ss_content_validate` tool should call the same validator library the Builder uses, so AI and humans share one rule set.

Future MCP operations can be split into safe stages:

```text
ss_content_validate
    -> read-only validation

ss_content_preview
    -> render/preview without database writes

ss_content_export
    -> produce JSON/SQL artifacts

ss_content_apply
    -> explicitly authorized staging mutation
```

`ss_content_apply` should never be the default operation for an AI agent.
