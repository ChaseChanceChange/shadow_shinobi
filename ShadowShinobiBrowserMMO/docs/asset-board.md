# Asset Board

Visual inventory + replacement mapper that **keeps legacy filenames** so PHP/templates do not need path changes.

## Idea

- See every legacy image in a browser grid
- Drop new art into a `candidates/` folder
- Point **this old asset → that new asset** by eye
- Promote copies the new file **onto the old path/name**
- Game code keeps using `images/login.gif` etc.

## Quick start

From the `ShadowShinobiBrowserMMO` directory (or repo root with adjusted paths):

```bash
mkdir -p assets-work/candidates

python3 scripts/build_asset_board.py \
  --legacy "src/0-August-2018(latest)/images" \
  --legacy "src/0-August-2018(latest)/layoutnovo" \
  --candidates "assets-work/candidates" \
  --out "assets-work/board"

# open in browser
# mac: open assets-work/board/index.html
# linux: xdg-open assets-work/board/index.html
```

## Workflow

1. Put replacement art in `assets-work/candidates/` (any filenames).
2. Rebuild the board (command above).
3. In the HTML board:
   - search/filter legacy assets
   - click a candidate in the top strip
   - on a legacy card, click **Use selected candidate**
   - set status: `replace` / `keep` / `ignore`
4. Click **Export mapping JSON**
5. Promote (dry-run first):

```bash
python3 scripts/promote_replacements.py path/to/asset-replacements.json
python3 scripts/promote_replacements.py path/to/asset-replacements.json --apply
```

## Format note

If a candidate is PNG but the legacy path is `.gif`, either:

- convert the candidate to GIF before promote, or
- only promote when the engine/path can accept the new format

## Files

- `scripts/build_asset_board.py` – scan + generate board
- `scripts/promote_replacements.py` – apply mapping onto legacy names
- `assets-work/board/index.html` – visual UI (generated, gitignored)
- `assets-work/board/manifest.csv` – spreadsheet-friendly list (generated)
