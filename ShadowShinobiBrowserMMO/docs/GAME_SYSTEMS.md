# Shadow Shinobi Game Systems Inventory

This document is an evolving map of the legacy game. It is intentionally based on source/database evidence and should be updated whenever a subsystem is verified or replaced.

## Current known systems

| System | Current source evidence | Database dependency | Status |
|---|---|---|---|
| Authentication | `login.php`, `cookies.php`, activation/recovery flows | `dk_users` and related auth fields | Needs live verification |
| Registration | registration entry point | user/account tables | Needs live verification |
| Account/profile | account and character views | user/character fields | Needs live verification |
| Character stats | character/player views | character/user state | Needs live verification |
| Equipment | equipment view | item/equipment state | Needs live verification |
| Backpack/inventory | `backpack.php` | inventory/item tables | Needs live verification |
| Techniques/abilities | technique-related views and legacy dispatcher | technique/stat tables | Needs live verification |
| Town/world navigation | city/town content and map-related code | world/location tables | Needs live verification |
| Map chat | `chat.php`, `dk_chatmap` | `dk_chatmap` | Needs live verification |
| Global chat/babble | chat/babble code | `dk_babble` | Needs live verification |
| PvE combat | combat entry points and battle flow | combat/monster/drop data | Needs live verification |
| Monster drops | drop definitions | `dk_drops` | Needs live verification |
| PvP/duels | duel entry/presentation | player/combat state | Needs live verification |
| Bank | bank controls/views | bank/item state | Needs live verification |
| Alchemy | `alquimia.php` | item/material data | Needs live verification |
| Administration | `admin.php` | control/content tables | Needs live verification; do not expose publicly |
| Game configuration | `dk_control` | `dk_control` | Needs live verification |

## Evidence already confirmed

The seed database contains at least the following legacy tables/data classes:

- `dk_babble` — chat messages
- `dk_chatmap` — map-local chat with latitude/longitude fields
- `dk_control` — game-wide configuration such as game name, classes, difficulty and display flags
- `dk_drops` — monster/item drop definitions and stat modifiers

The legacy control record still contains the historical game identity. This is content to be replaced during the Shadow Shinobi conversion, not evidence that the modern project should keep the original branding.

## Verification protocol

Each subsystem should be tested with a disposable account and marked:

- `WORKING` — complete user flow succeeds and state persists after reload.
- `PARTIAL` — UI/entry works but one or more actions fail.
- `BROKEN` — flow is reachable but fails materially.
- `BLOCKED` — dependency prevents meaningful testing.
- `LEGACY` — retained for archaeology/reference but no longer part of the target design.
- `REPLACED` — a Shadow Shinobi implementation has superseded the legacy implementation.

For every `BROKEN` or `PARTIAL` result, record the URL/entry point, action taken, visible error, server/PHP error, and relevant database row/table changes.

## Gameplay model to preserve initially

The current project direction is:

```text
Build squad
   -> train
   -> equip
   -> mission/explore
   -> battle
   -> loot
   -> improve
   -> unlock
   -> PvP/social
   -> repeat
```

This is the compatibility-level gameplay loop. Exact mechanics should be discovered from the legacy implementation before we permanently redesign them.

## Creative phase gate

We can call the baseline ready for major creative work when all of these are true:

- Local Docker stack reproduces the application from a clean checkout.
- Database imports successfully without manual repair.
- Authentication and account creation work.
- A test character can reach the main gameplay area.
- Character state can be changed and persists.
- Inventory/equipment can be changed and persists.
- At least one PvE combat loop completes end-to-end.
- Navigation/world state is understood well enough to avoid dead-end screens.
- Known failures are documented.
- Original/Naruto-specific content is identified separately from engine mechanics.

Until then, changes should favor compatibility, observability, and documentation over wholesale mechanic replacement.
