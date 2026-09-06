# Content Batch 002 — Language + Identity Foundation

Date: 2026-09-05

## Scope

This batch adds documentation-only guardrails for the first safe identity conversion. No PHP, SQL schema, IDs, routes, combat formulas or authentication code are modified by this batch.

## Added

- `docs/content-conversion-rules.md` — rules for converting legacy content without blind string replacement.
- `docs/LORE_STARTER.md` — compact original starter scenario built around existing movement, combat, Recovery and mission systems.

## Design decision

The game should feel like a new world primarily through **context and naming**, not through a risky rewrite of functioning mechanics. A legacy ability can keep its ID and numerical effect while receiving a new name, description and fictional explanation.

## Next conversion order

1. Finish high-visibility static English UI.
2. Convert equipment and ability display names.
3. Convert settlements and Contracts in database content.
4. Convert combat/enemy presentation text.
5. Correct legacy encoding independently.
6. Add the starter arc to existing mission structures.
7. Build automated content/terminology validation.
