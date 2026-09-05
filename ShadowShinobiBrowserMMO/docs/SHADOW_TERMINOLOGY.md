# Shadow Shinobi Terminology Bible

This document defines the player-facing vocabulary for the conversion from the original Naruto-themed browser MMO into Shadow Shinobi.

## Conversion rule

The first conversion pass changes fiction and language, not the underlying engine. Database IDs, table names, route names, combat formulas, and internal field names remain unchanged unless a later migration explicitly requires otherwise.

Player-facing text should never require the original franchise terminology to make sense.

## Core vocabulary

| Legacy concept | Shadow Shinobi term | Usage |
|---|---|---|
| Ninja | Shinobi | Generic player profession |
| Jutsu / Technique | Art | A learned combat or utility ability |
| Chakra | Essence | Primary supernatural resource |
| Village | Enclave | Major settled faction/location |
| Kage / village leader | Warden | Leader title; singular title can vary by faction |
| Mission / Quest | Contract | A task accepted by the player |
| Training | Discipline | Character improvement activity |
| Rank | Standing | Player progression tier |
| Duel | Challenge | Formal player-versus-player encounter |
| Monster / enemy | Threat | Generic hostile target |
| Drop | Recovery | Material or item recovered from a defeated threat |
| Inventory / Backpack | Pack | Player-held item storage |
| Equipment | Gear | Weapons, armor, shields, and special slots |
| Gold / Ryou | Coin | Player currency; exact final name remains provisional |
| Map | World Map | Geographic navigation |
| Town | Settlement | Neutral/general location label |
| Alchemy | Refinement | Combining or improving materials/items |
| Bank | Vault | Stored currency/item service |
| Global chat | Open Channel | World-wide social chat |
| Map chat | Local Channel | Area-specific chat |
| Character sheet | Operative Record | Character information screen |
| Experience | Insight | Progress toward the next Standing |
| Level | Standing | Numerical progression represented to players as a rank/standing |

## Terms deliberately not fixed yet

The following remain provisional so the game design can determine them from the lore instead of forcing names early:

- Final currency name
- Final names for the three legacy classes
- Final rank ladder
- Faction-specific leader titles
- Name of the supernatural source behind Essence
- Name for special passive systems such as Senjutsu
- Name for tracking/search abilities

## Writing style

Shadow Shinobi uses concise, slightly mysterious language rather than direct anime terminology.

Prefer:

> Accept Contract
>
> Enter the frontier.
>
> Develop Discipline
>
> Spend Insight
>
> Recover material
>
> Challenge Operative

Avoid:

> Learn Jutsu
>
> Spend Chakra
>
> Become a Kage
>
> Complete Ninja Mission

## Mechanical compatibility

The following internal identifiers remain valid during the conversion and should not be renamed merely for aesthetics:

- `dk_users`
- `dk_towns`
- `dk_drops`
- `dk_control`
- `dk_babble`
- `dk_chatmap`
- `dk_spells`
- existing `do=` routes
- existing combat/action state values

A future refactor may introduce cleaner engine-facing names, but that is a separate engineering project.

## Content conversion principle

Do not perform literal word-for-word substitution when rewriting lore. Preserve the gameplay function while changing the fictional explanation.

Example:

Legacy concept:

> Train a Jutsu that restores health.

Shadow Shinobi:

> Develop a restorative Art that draws Essence back into the body.

The mechanic remains recognizable to the engine while the fiction becomes original.

## Canon status

This is the **v0.1 terminology set**. New content should use these terms unless a future lore decision supersedes one of them. Superseded terms should remain documented rather than silently changed, so database content and future AI-generated content can be audited.
