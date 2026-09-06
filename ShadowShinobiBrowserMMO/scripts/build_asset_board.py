#!/usr/bin/env python3
"""Shadow Shinobi visual asset board - browse + verify UX."""
from __future__ import annotations
import argparse, base64, csv, hashlib, json, mimetypes, sys
from pathlib import Path

IMAGE_EXTS = {".gif", ".png", ".jpg", ".jpeg", ".webp", ".bmp", ".svg"}

def iter_images(roots):
    found = []
    for root in roots:
        if not root.exists():
            print(f"WARN: missing path {root}", file=sys.stderr)
            continue
        for p in sorted(root.rglob("*")):
            if p.is_file() and p.suffix.lower() in IMAGE_EXTS:
                found.append(p)
    return found

def rel_key(path, roots):
    for root in roots:
        try:
            return str(path.resolve().relative_to(root.resolve())).replace("\\", "/")
        except ValueError:
            continue
    return path.name

def thumb_data_uri(path, max_bytes=180_000):
    try:
        data = path.read_bytes()
    except OSError:
        return None
    if len(data) > max_bytes:
        return None
    mime, _ = mimetypes.guess_type(str(path))
    mime = mime or "application/octet-stream"
    return f"data:{mime};base64," + base64.b64encode(data).decode("ascii")

def file_meta(path):
    st = path.stat()
    return {"bytes": st.st_size, "ext": path.suffix.lower(), "mtime": int(st.st_mtime)}

def build_manifest(legacy_roots, candidate_root):
    legacy = []
    for p in iter_images(legacy_roots):
        legacy.append({
            "id": hashlib.sha1(str(p.resolve()).encode()).hexdigest()[:12],
            "legacy_path": str(p.resolve()),
            "display_path": rel_key(p, legacy_roots),
            "filename": p.name,
            **file_meta(p),
            "thumb": thumb_data_uri(p),
            "replace_with": "",
            "replace_preview": "",
            "status": "unknown",
            "notes": "",
            "verified": False,
        })
    candidates = []
    if candidate_root and candidate_root.exists():
        for p in iter_images([candidate_root]):
            candidates.append({
                "id": hashlib.sha1(str(p.resolve()).encode()).hexdigest()[:12],
                "path": str(p.resolve()),
                "display_path": rel_key(p, [candidate_root]),
                "filename": p.name,
                **file_meta(p),
                "thumb": thumb_data_uri(p),
            })
    return {
        "legacy_roots": [str(r.resolve()) for r in legacy_roots],
        "candidate_root": str(candidate_root.resolve()) if candidate_root else "",
        "legacy": legacy,
        "candidates": candidates,
    }

TEMPLATE_NAME = "asset_board_template.html"

def default_template():
    return Path(__file__).with_name(TEMPLATE_NAME)

def write_board(manifest, out_dir: Path):
    out_dir.mkdir(parents=True, exist_ok=True)
    tpl_path = default_template()
    if tpl_path.exists():
        html = tpl_path.read_text(encoding="utf-8")
    else:
        html = """<!DOCTYPE html><html><head><meta charset=utf-8><title>Asset Board</title></head>
<body><h1>Asset Board</h1><p>Missing asset_board_template.html next to build_asset_board.py</p>
<script>window.ASSET_BOARD = __DATA__;</script>
<pre id=x></pre><script>document.getElementById('x').textContent=JSON.stringify(window.ASSET_BOARD,null,2).slice(0,2000)</script>
</body></html>"""
    data_json = json.dumps(manifest, ensure_ascii=False)
    html = html.replace("__DATA__", data_json)
    (out_dir / "index.html").write_text(html, encoding="utf-8")
    (out_dir / "manifest.json").write_text(json.dumps(manifest, indent=2), encoding="utf-8")
    with (out_dir / "manifest.csv").open("w", newline="", encoding="utf-8") as f:
        w = csv.DictWriter(f, fieldnames=["id","display_path","filename","ext","bytes","status","replace_with","verified","notes","legacy_path"])
        w.writeheader()
        for row in manifest["legacy"]:
            w.writerow({k: row.get(k, "") for k in w.fieldnames})
    print(f"Board written: {out_dir / 'index.html'}")
    print(f"Legacy images: {len(manifest['legacy'])}")
    print(f"Candidates:    {len(manifest['candidates'])}")
    if not manifest["candidates"]:
        print("NOTE: candidates folder empty or missing — add files then rebuild.")

def main():
    ap = argparse.ArgumentParser(description="Build Shadow Shinobi visual asset board")
    ap.add_argument("--legacy", action="append", default=[])
    ap.add_argument("--candidates", default="")
    ap.add_argument("--out", default="assets-work/board")
    args = ap.parse_args()
    if not args.legacy:
        print("Provide at least one --legacy path", file=sys.stderr)
        return 2
    cand = Path(args.candidates) if args.candidates else None
    write_board(build_manifest([Path(p) for p in args.legacy], cand), Path(args.out))
    return 0

if __name__ == "__main__":
    raise SystemExit(main())
