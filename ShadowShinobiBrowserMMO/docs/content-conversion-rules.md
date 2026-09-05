# Shadow Shinobi Content Conversion Rules

## Purpose

This document defines the safe method for converting the recovered browser MMO into Shadow Shinobi without rewriting the working engine.

## Core rule

Change what the player sees before changing how the engine works.

### Keep stable

- Database schema and `dk_*` table names
- Numeric IDs
- Existing `do=` routes
- Existing combat/action state values
- Attribute keys used by the PHP engine
- Movement and combat formulas
- Authentication/session mechanics
- Asset filenames until replacements have been verified

### Convert deliberately

- Page titles and headings
- Buttons, labels, tooltips and error messages
- Item, ability, enemy and location names
- Mission and dialogue text
- Help/codex language
- World terminology
- Lore explanations

## Do not perform blind replacement

Never replace a legacy franchise term everywhere in source code.

A word can be:

1. player-facing prose;
2. an engine identifier;
3. a database value;
4. an asset filename;
5. a route or compatibility contract.

Only the first and third categories should normally change during the initial retheme.

## Content conversion pattern

For each piece of old content:

1. Identify its gameplay function.
2. Preserve its mechanical effect.
3. Replace the fictional explanation with Shadow Shinobi lore.
4. Apply the terminology bible.
5. Check that no legacy franchise term remains in the player-facing result.
6. Test the affected route.

Example:

Legacy concept: a learned ability that restores health.

Shadow version: **Restorative Art** — an established procedure that redirects Essence through damaged tissue.

The cost, target, healing formula and underlying ability ID can remain unchanged.

## Database migration rule

All bulk content changes must be represented by deterministic SQL migrations.

A migration should:

- target an explicit ID or exact legacy value;
- preserve IDs and attributes unless the change is explicitly mechanical;
- be safe to run against a fresh database;
- document what was changed;
- include validation queries where practical.

## Encoding rule

New maintained source and content files use UTF-8 without BOM. Existing legacy encoding is corrected separately from semantic retheming so encoding damage is easy to identify in Git history.

## Review gate

A batch is not complete until:

- PHP lint passes for modified PHP;
- the relevant Docker route returns a non-empty body;
- no PHP diagnostics leak into the response;
- the expected Shadow terminology is visible;
- IDs/routes/forms remain compatible;
- the migration is deterministic;
- a short change note records the batch.

## AI authoring

AI-generated content must use the same terminology and lore documents as human authors. AI output is considered a proposal until it passes the same validator used by the future content builder.
