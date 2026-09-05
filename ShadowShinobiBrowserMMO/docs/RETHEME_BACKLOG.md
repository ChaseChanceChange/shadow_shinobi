# Shadow Shinobi Retheme Backlog

This backlog is ordered by risk, not by visual excitement.

## Gate 0 — Runtime

- Home route produces visible content after developer login.
- Character, Pack, Rankings, Codex, Settlement, Exploration and Combat routes return non-empty responses.
- No PHP diagnostics leak into HTML.

## Gate 1 — English baseline

- Login/account pages
- Navigation labels
- Character/equipment labels
- Settlement actions
- Exploration messages
- Combat messages
- Training/Contract pages
- Codex/help

## Gate 2 — Terminology

- Replace player-visible franchise vocabulary with Shadow vocabulary.
- Preserve engine identifiers and route contracts.
- Use exact terminology from `SHADOW_CONTENT_GLOSSARY.md`.

## Gate 3 — Content data

- Convert settlement names.
- Convert ability names/descriptions.
- Convert enemy names/descriptions.
- Convert item/drop names/descriptions.
- Convert Contract/quest text.
- Convert tutorial/news/dialogue text.

Every database change must be an explicit deterministic migration.

## Gate 4 — Encoding

- Identify latin1/UTF-8 corruption.
- Correct content in a dedicated batch.
- Verify accented characters render correctly.
- Do not mix encoding repair with unrelated mechanical changes.

## Gate 5 — Original starter campaign

Use the legacy mission structures to implement the `Broken Marker` starter arc from `LORE_STARTER.md`.

## Gate 6 — Authoring infrastructure

- Content schema
- Content validator
- Preview renderer
- Deterministic migration exporter
- Editor UI
- AI authoring prompt integration
- MCP read-only validation tools

## Rule

No batch is considered complete merely because PHP parses. Runtime visibility is part of correctness.
