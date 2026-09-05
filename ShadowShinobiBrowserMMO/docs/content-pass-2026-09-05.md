# Content / Retheme Pass — 2026-09-05

## Objective

Begin the Shadow Shinobi identity conversion without disturbing the recovered engine.

## Changes committed on `modernize-safe-pass`

### Documentation

- `docs/SHADOW_TERMINOLOGY.md`
  - Establishes the player-facing vocabulary.
  - Separates player terminology from engine identifiers.
  - Defines writing style and conversion rules.

- `docs/shadow-terminology.json`
  - Machine-readable version of the terminology map for future builders and AI agents.

- `docs/LORE_FOUNDATION.md`
  - Establishes the Veiling, Essence, Enclaves, Arts, Contracts, Threats, Recovery, and Standing as the first lore foundation.

- `docs/localization-plan.md`
  - Defines the staged English/localization process.
  - Explicitly separates static UI, database content, terminology, and encoding cleanup.

- `docs/AI_CONTENT_AUTHORING_PROMPT.md`
  - Base authoring contract for Grok, DeepSeek, and future content agents.
  - Requires engine compatibility while enforcing original Shadow Shinobi fiction.

- `docs/content-builder.md`
  - Updated to consume the terminology and lore contracts.
  - Adds terminology/lore validation and safe AI workflow guidance.

### Tooling

- `scripts/content-audit.ps1`
  - Scans the current game source for common legacy/franchise-facing strings.
  - Produces `docs/content-audit.csv` for future translation batches.

### Game content

- `database (sql)/migrations/001_shadow_identity.sql`
  - First deterministic player-facing content migration.
  - Changes the control record's displayed game name, classes, and difficulty labels.
  - Changes several directly verified legacy drop names while preserving their IDs and attributes.

- `src/0-August-2018(latest)/cidadesconteudo.php`
  - Translated settlement action labels/tooltips to English.
  - Replaced player-facing terminology with Shadow vocabulary.
  - Preserved every route, town ID, and mechanic.

## Deliberately not changed

- Database schema
- `dk_*` table names
- Player IDs
- Item/drop IDs
- PHP dispatcher routes
- Combat formulas
- Authentication architecture
- Character progression mathematics
- Movement/map calculations
- Player-state updates

## Verification policy

This pass is content-first. Local Docker runtime remains the authoritative test for the user's actual checkout.

Every source/content batch should be followed by PHP linting and route smoke tests before additional semantic rewrites are layered on top.

## Next content batch

Use the audit output to translate/retheme the highest-visibility player-facing surfaces first:

1. login/account flows;
2. character and equipment surfaces;
3. town/exploration labels;
4. contracts/training text;
5. combat messages;
6. item/drop names and descriptions;
7. help/codex text.

After those are stable, add the first fully original starter storyline using existing mission/quest structures.
