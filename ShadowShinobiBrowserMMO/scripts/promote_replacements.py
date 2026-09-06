#!/usr/bin/env python3
"""
Promote mapped replacements onto legacy paths, KEEPING legacy filenames.

Reads asset-replacements.json from the board export:
{
  "mappings": [
    {
      "legacy_path": "/abs/path/to/images/login.gif",
      "replace_with": "/abs/path/to/candidates/hero.png"
    }
  ]
}

Dry-run by default. Use --apply to write files.
Creates a .bak next to each replaced file unless --no-bak.
"""

from __future__ import annotations

import argparse
import json
import shutil
import sys
from pathlib import Path


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("mapping_json", help="asset-replacements.json from the board")
    ap.add_argument("--apply", action="store_true", help="Actually copy files")
    ap.add_argument("--no-bak", action="store_true", help="Do not write .bak backups")
    args = ap.parse_args()

    data = json.loads(Path(args.mapping_json).read_text(encoding="utf-8"))
    mappings = data.get("mappings") or []
    if not mappings:
        print("No mappings found.")
        return 0

    for m in mappings:
        src = Path(m["replace_with"])
        dst = Path(m["legacy_path"])
        print(f"{src}  ->  {dst}")
        if not src.exists():
            print(f"  SKIP missing source: {src}", file=sys.stderr)
            continue
        if not args.apply:
            continue
        dst.parent.mkdir(parents=True, exist_ok=True)
        if dst.exists() and not args.no_bak:
            bak = dst.with_suffix(dst.suffix + ".bak")
            shutil.copy2(dst, bak)
            print(f"  bak: {bak}")
        # Preserve destination filename. Convert formats separately if needed.
        shutil.copy2(src, dst)
        print("  applied")

    if not args.apply:
        print("\nDry-run only. Re-run with --apply to write files.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
