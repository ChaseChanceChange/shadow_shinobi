# Shadow Shinobi Terminology Bible

This document defines the canonical player-facing vocabulary for Shadow Shinobi.

## Conversion rule

The current modernization pass changes fiction and language first while preserving the underlying game engine. Database IDs, table names, route names, combat formulas, and internal field names remain unchanged unless a dedicated migration later changes them.

Player-facing content must stand on its own without requiring knowledge of the source project's original franchise terminology.

## Canonical vocabulary

| Legacy concept | Shadow Shinobi term | Usage |
|---|---|---|
| Ninja | Operative | Individual player-controlled field agent |
| Shinobi | Shadow Shinobi | World identity / factional descriptor; not the default noun for a player |
| Jutsu / Technique | Art | Learned combat or utility ability |
| Chakra | Essence | Primary supernatural resource |
| Village | Enclave | Major settled faction/location |
| Kage / village leader | Warden | Leader title |
| Mission / Quest | Contract | Task accepted by an operative |
| Training | Discipline | Character improvement activity |
| Rank / Level | Standing | Progression tier shown to players |
| Experience | Insight | Progress toward the next Standing |
| Duel | Challenge | Formal player-versus-player encounter |
| Monster / Enemy | Threat | Hostile target |
| Drop | Recovery | Material or item recovered from a defeated threat |
| Inventory / Backpack | Pack | Player-held item storage |
| Equipment | Gear | Weapons, armor, shields, and special slots |
| Gold / Ryou | Coin | Player currency |
| Map | World Map | Geographic navigation |
| Town | Settlement | General inhabited location |
| Alchemy | Refinement | Combining or improving materials/items |
| Bank | Vault | Stored currency/item service |
| Global chat | Open Channel | World-wide social chat |
| Map chat | Local Channel | Area-specific social chat |
| Character sheet | Operative Record | Character information screen |

## Standard action language

Prefer:

> Accept Contract
>
> Enter the frontier.
>
> Develop Discipline
>
> Channel Essence
>
> Spend Insight
>
> Recover material
>
> Challenge Operative
>
> Open Pack
>
> Open Vault
>
> View Operative Record
>
> Enter the World Map

Avoid:

> Learn Jutsu
>
> Spend Chakra
>
> Become a Kage
>
> Complete Ninja Mission
>
> Check Backpack

## English-only presentation

Player-facing pages should render in English. Existing Portuguese strings are being translated/rethemed through the centralized player-language layer while individual legacy source files are progressively retired or renamed.

No new Portuguese text should be introduced.

## Franchise terminology policy

Do not introduce the original franchise's named characters, named villages, signature creatures, clans, trademark terminology, or copied plot events into new player-facing content.

Legacy data may still contain historical values or internal identifiers. Those are compatibility concerns and are not part of the player-facing vocabulary.

## Compatibility boundary

The following internal identifiers remain valid during the transition:

- `dk_users`
- `dk_towns`
- `dk_drops`
- `dk_control`
- `dk_babble`
- `dk_chatmap`
- `dk_spells`
- existing `do=` routes
- existing combat/action state values
- legacy PHP function and file names until their replacements are fully wired

Renaming these mechanically would create unnecessary breakage and is a separate refactor.

## Content conversion principle

Do not perform literal word-for-word substitution when rewriting lore. Preserve the gameplay function while changing the fictional explanation.

Example:

Legacy concept:

> Train a Jutsu that restores health.

Shadow Shinobi:

> Develop a restorative Art that draws Essence back into the body.

The mechanic stays compatible with the existing engine while the fiction becomes Shadow Shinobi.

## Canon status

This is the **v0.2 canonical terminology set**. New UI, documentation, assets, and content should follow it. Superseded legacy terminology remains documented only to support migration and auditing.
