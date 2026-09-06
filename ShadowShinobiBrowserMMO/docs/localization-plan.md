# Shadow Shinobi Localization Plan

## Goal

Move all player-facing text to English while keeping engine identifiers and legacy mechanics stable.

## Important distinction

There are three different things in this project:

1. **Engine identifiers** — PHP function names, database table names, column names, and `do=` routes. Keep these stable in the compatibility phase.
2. **Player-facing strings** — headings, buttons, messages, item names, town names, ability names, descriptions, and help text. These are the primary translation/retheme target.
3. **Stored content** — database rows containing names, descriptions, dialogue, missions, and other game text. These require a controlled SQL/content migration rather than broad source-code replacement.

## Migration order

### Phase A — source UI

Translate obvious static strings in templates and player-facing PHP output.

### Phase B — database seed content

Create a repeatable content migration for names/descriptions in the seed database. Do not alter IDs or schema.

### Phase C — terminology normalization

Apply the vocabulary in `docs/SHADOW_TERMINOLOGY.md` so the UI and database use the same terms.

### Phase D — encoding cleanup

The legacy project contains historical Portuguese text with mixed/incorrect encodings. Treat encoding correction as its own pass; do not mix it silently with semantic renaming.

### Phase E — validation

For every modified route, verify:

- HTTP response is successful;
- page has non-zero body content;
- no PHP Warning/Notice/Deprecated/Fatal/Parse output is exposed;
- the intended English terminology appears;
- links and form names remain unchanged unless intentionally redesigned;
- database state is unchanged except for explicitly targeted content rows.

## Why not gettext yet?

PHP supports gettext-based translation, but introducing a full i18n framework into the legacy application would increase surface area during stabilization. The current priority is a deterministic English baseline. A proper localization layer can be introduced after the content model is separated from the legacy engine. citehttps://www.php.net/manual/en/function.gettext.php

## Target architecture

```text
engine
  └── stable legacy identifiers

content
  ├── English player text
  ├── Shadow terminology
  ├── world/lore data
  └── future translations

validation
  └── ensures content does not violate engine contracts
```

## Encoding rule

New text files should use UTF-8 without BOM and Unix line endings. Existing legacy files should only have their encoding changed when the diff is intentionally controlled and verified. The PHP documentation tooling likewise recommends UTF-8 without BOM and Unix line endings for maintained source text. citehttps://doc.php.net/guide/style.md
