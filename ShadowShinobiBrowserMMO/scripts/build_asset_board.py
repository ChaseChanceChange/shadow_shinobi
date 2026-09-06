#!/usr/bin/env python3
"""
Shadow Shinobi - Visual Asset Board
===================================
Scan game image folders, build a visual board, and map replacements
while PRESERVING legacy filenames (no PHP/template path changes).

Usage:
  python3 build_asset_board.py \
      --legacy "ShadowShinobiBrowserMMO/src/0-August-2018(latest)/images" \
      --legacy "ShadowShinobiBrowserMMO/src/0-August-2018(latest)/layoutnovo" \
      --candidates "./assets-work/candidates" \
      --out "./assets-work/board"

Then open:  assets-work/board/index.html
"""

from __future__ import annotations

import argparse
import base64
import csv
import hashlib
import json
import mimetypes
import sys
from pathlib import Path

IMAGE_EXTS = {".gif", ".png", ".jpg", ".jpeg", ".webp", ".bmp", ".svg"}


def iter_images(roots: list[Path]) -> list[Path]:
    found: list[Path] = []
    for root in roots:
        if not root.exists():
            print(f"WARN: missing path {root}", file=sys.stderr)
            continue
        for p in sorted(root.rglob("*")):
            if p.is_file() and p.suffix.lower() in IMAGE_EXTS:
                found.append(p)
    return found


def rel_key(path: Path, roots: list[Path]) -> str:
    for root in roots:
        try:
            return str(path.resolve().relative_to(root.resolve())).replace("\\", "/")
        except ValueError:
            continue
    return path.name


def thumb_data_uri(path: Path, max_bytes: int = 120_000) -> str | None:
    """Embed small images directly; skip huge ones (board still lists them)."""
    try:
        data = path.read_bytes()
    except OSError:
        return None
    if len(data) > max_bytes:
        return None
    mime, _ = mimetypes.guess_type(str(path))
    if not mime:
        mime = "application/octet-stream"
    b64 = base64.b64encode(data).decode("ascii")
    return f"data:{mime};base64,{b64}"


def file_meta(path: Path) -> dict:
    st = path.stat()
    return {
        "bytes": st.st_size,
        "ext": path.suffix.lower(),
        "mtime": int(st.st_mtime),
    }


def build_manifest(legacy_roots: list[Path], candidate_root: Path | None) -> dict:
    legacy = []
    for p in iter_images(legacy_roots):
        key = rel_key(p, legacy_roots)
        entry = {
            "id": hashlib.sha1(str(p.resolve()).encode()).hexdigest()[:12],
            "legacy_path": str(p.resolve()),
            "display_path": key,
            "filename": p.name,
            **file_meta(p),
            "thumb": thumb_data_uri(p),
            "replace_with": "",
            "status": "unknown",
            "notes": "",
        }
        legacy.append(entry)

    candidates = []
    if candidate_root and candidate_root.exists():
        for p in iter_images([candidate_root]):
            candidates.append(
                {
                    "id": hashlib.sha1(str(p.resolve()).encode()).hexdigest()[:12],
                    "path": str(p.resolve()),
                    "display_path": rel_key(p, [candidate_root]),
                    "filename": p.name,
                    **file_meta(p),
                    "thumb": thumb_data_uri(p),
                }
            )

    return {
        "legacy_roots": [str(r.resolve()) for r in legacy_roots],
        "candidate_root": str(candidate_root.resolve()) if candidate_root else "",
        "legacy": legacy,
        "candidates": candidates,
    }


BOARD_HTML = r"""<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Shadow Shinobi Asset Board</title>
<style>
  :root {
    --bg: #0b0e13;
    --panel: #151a22;
    --border: #2a3340;
    --text: #e9edf4;
    --muted: #9aa6b5;
    --accent: #6ec8ff;
  }
  * { box-sizing: border-box; }
  body {
    margin: 0; font-family: system-ui, -apple-system, Segoe UI, sans-serif;
    background: var(--bg); color: var(--text);
  }
  header {
    position: sticky; top: 0; z-index: 10;
    background: rgba(11,14,19,.92); border-bottom: 1px solid var(--border);
    padding: 14px 20px; backdrop-filter: blur(8px);
  }
  header h1 { margin: 0 0 6px; font-size: 1.15rem; }
  header p { margin: 0; color: var(--muted); font-size: .9rem; }
  .toolbar {
    display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; align-items: center;
  }
  input[type="search"], select, button, textarea {
    background: var(--panel); color: var(--text); border: 1px solid var(--border);
    border-radius: 8px; padding: 8px 10px; font: inherit;
  }
  button { cursor: pointer; }
  button.primary { border-color: var(--accent); color: var(--accent); }
  main { padding: 16px 20px 48px; }
  .stats { color: var(--muted); margin-bottom: 12px; font-size: .9rem; }
  .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 14px; }
  .card {
    background: var(--panel); border: 1px solid var(--border); border-radius: 12px;
    padding: 12px; display: flex; flex-direction: column; gap: 10px;
  }
  .card.replace { border-color: #3d6b4f; }
  .card.keep { border-color: #3a4554; opacity: .85; }
  .card.ignore { opacity: .45; }
  .previews {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px; min-height: 120px;
  }
  .preview-box {
    background: #0a0d12; border: 1px dashed var(--border); border-radius: 8px;
    display: flex; align-items: center; justify-content: center; min-height: 120px;
    position: relative; overflow: hidden;
  }
  .preview-box img { max-width: 100%; max-height: 140px; image-rendering: auto; }
  .preview-box .label {
    position: absolute; left: 6px; top: 6px; font-size: 10px; color: var(--muted);
    background: rgba(0,0,0,.55); padding: 2px 6px; border-radius: 4px;
  }
  .meta { font-size: .8rem; color: var(--muted); word-break: break-all; }
  .meta strong { color: var(--text); }
  .row { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
  .row select, .row input { flex: 1; min-width: 120px; }
  textarea { width: 100%; min-height: 48px; resize: vertical; }
  .cand-bar {
    margin: 0 0 16px; padding: 12px; background: var(--panel);
    border: 1px solid var(--border); border-radius: 12px;
  }
  .cand-strip { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 6px; }
  .cand-chip {
    flex: 0 0 auto; width: 88px; text-align: center; cursor: pointer;
    border: 1px solid var(--border); border-radius: 8px; padding: 6px; background: #0a0d12;
  }
  .cand-chip img { max-width: 72px; max-height: 56px; }
  .cand-chip small {
    display: block; font-size: 10px; color: var(--muted);
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
  }
  .cand-chip.selected { border-color: var(--accent); }
  #toast {
    position: fixed; bottom: 16px; right: 16px; background: #1b2430;
    border: 1px solid var(--border); padding: 10px 14px; border-radius: 8px;
    display: none; z-index: 20;
  }
</style>
</head>
<body>
<header>
  <h1>Shadow Shinobi Asset Board</h1>
  <p>Pick replacements visually. Promote keeps the <strong>same legacy filename</strong> so game code does not change.</p>
  <div class="toolbar">
    <input type="search" id="q" placeholder="Filter by path / filename..." />
    <select id="statusFilter">
      <option value="">All statuses</option>
      <option value="unknown">unknown</option>
      <option value="replace">replace</option>
      <option value="keep">keep</option>
      <option value="ignore">ignore</option>
    </select>
    <select id="extFilter">
      <option value="">All types</option>
    </select>
    <button class="primary" id="btnExport">Export mapping JSON</button>
    <button id="btnSaveLocal">Save progress (browser)</button>
    <button id="btnLoadLocal">Load progress</button>
  </div>
</header>
<main>
  <div class="stats" id="stats"></div>
  <div class="cand-bar">
    <div class="meta" style="margin-bottom:8px"><strong>Candidates</strong> - click one, then click "Use selected candidate" on a legacy card</div>
    <div class="cand-strip" id="candStrip"></div>
  </div>
  <div class="grid" id="grid"></div>
</main>
<div id="toast"></div>
<script>
window.ASSET_BOARD = __DATA__;
</script>
<script>
(function () {
  const data = window.ASSET_BOARD;
  const grid = document.getElementById('grid');
  const stats = document.getElementById('stats');
  const candStrip = document.getElementById('candStrip');
  const toast = document.getElementById('toast');
  let selectedCand = null;

  const extFilter = document.getElementById('extFilter');
  const exts = [...new Set(data.legacy.map(x => x.ext))].sort();
  for (const e of exts) {
    const o = document.createElement('option');
    o.value = e; o.textContent = e;
    extFilter.appendChild(o);
  }

  function showToast(msg) {
    toast.textContent = msg;
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', 2200);
  }

  function renderCandidates() {
    candStrip.innerHTML = '';
    if (!data.candidates.length) {
      candStrip.innerHTML = '<span class="meta">No candidates folder (or empty). Add files under candidates/ and rebuild.</span>';
      return;
    }
    for (const c of data.candidates) {
      const chip = document.createElement('div');
      chip.className = 'cand-chip' + (selectedCand && selectedCand.id === c.id ? ' selected' : '');
      chip.title = c.display_path;
      chip.innerHTML = (c.thumb ? '<img src="' + c.thumb + '" alt="">' : '<div class="meta">no preview</div>')
        + '<small>' + c.filename + '</small>';
      chip.onclick = () => { selectedCand = c; renderCandidates(); showToast('Selected: ' + c.filename); };
      candStrip.appendChild(chip);
    }
  }

  function filtered() {
    const q = document.getElementById('q').value.trim().toLowerCase();
    const st = document.getElementById('statusFilter').value;
    const ex = document.getElementById('extFilter').value;
    return data.legacy.filter(item => {
      if (st && item.status !== st) return false;
      if (ex && item.ext !== ex) return false;
      if (q && !(item.display_path.toLowerCase().includes(q) || item.filename.toLowerCase().includes(q))) return false;
      return true;
    });
  }

  function findCandThumb(path) {
    if (!path) return null;
    const c = data.candidates.find(x => x.path === path || x.display_path === path || x.filename === path);
    return c && c.thumb ? c.thumb : null;
  }

  function render() {
    const items = filtered();
    const counts = { unknown:0, replace:0, keep:0, ignore:0 };
    data.legacy.forEach(i => { counts[i.status] = (counts[i.status] || 0) + 1; });
    stats.textContent = 'Showing ' + items.length + ' / ' + data.legacy.length
      + ' | replace:' + (counts.replace || 0)
      + ' keep:' + (counts.keep || 0)
      + ' ignore:' + (counts.ignore || 0)
      + ' unknown:' + (counts.unknown || 0);

    grid.innerHTML = '';
    for (const item of items) {
      const card = document.createElement('article');
      card.className = 'card ' + (item.status || 'unknown');
      const repThumb = findCandThumb(item.replace_with);
      card.innerHTML =
        '<div class="previews">'
        + '<div class="preview-box"><span class="label">LEGACY</span>'
        + (item.thumb ? '<img src="' + item.thumb + '" alt="">' : '<span class="meta">no embed<br>' + item.bytes + ' bytes</span>')
        + '</div>'
        + '<div class="preview-box"><span class="label">REPLACEMENT</span>'
        + (repThumb ? '<img src="' + repThumb + '" alt="">' : (item.replace_with ? '<span class="meta">' + item.replace_with + '</span>' : '<span class="meta">none</span>'))
        + '</div></div>'
        + '<div class="meta"><strong>' + item.filename + '</strong><br>' + item.display_path + '<br>'
        + item.bytes + ' bytes · ' + item.ext + '</div>'
        + '<div class="row">'
        + '<select data-k="status">'
        + '<option value="unknown">unknown</option>'
        + '<option value="replace">replace</option>'
        + '<option value="keep">keep</option>'
        + '<option value="ignore">ignore</option>'
        + '</select>'
        + '<button type="button" data-act="use-cand">Use selected candidate</button>'
        + '</div>'
        + '<div class="row">'
        + '<input data-k="replace_with" placeholder="Replacement path (candidate file)" value="" />'
        + '<button type="button" data-act="clear">Clear</button>'
        + '</div>'
        + '<textarea data-k="notes" placeholder="Notes..."></textarea>';

      card.querySelector('[data-k="status"]').value = item.status || 'unknown';
      card.querySelector('[data-k="replace_with"]').value = item.replace_with || '';
      card.querySelector('[data-k="notes"]').value = item.notes || '';

      card.querySelector('[data-k="status"]').onchange = (e) => { item.status = e.target.value; render(); };
      card.querySelector('[data-k="replace_with"]').onchange = (e) => {
        item.replace_with = e.target.value.trim();
        if (item.replace_with) item.status = 'replace';
        render();
      };
      card.querySelector('[data-k="notes"]').onchange = (e) => { item.notes = e.target.value; };
      card.querySelector('[data-act="use-cand"]').onclick = () => {
        if (!selectedCand) { showToast('Select a candidate first'); return; }
        item.replace_with = selectedCand.path;
        item.status = 'replace';
        render();
      };
      card.querySelector('[data-act="clear"]').onclick = () => {
        item.replace_with = '';
        if (item.status === 'replace') item.status = 'unknown';
        render();
      };
      grid.appendChild(card);
    }
  }

  document.getElementById('q').oninput = render;
  document.getElementById('statusFilter').onchange = render;
  document.getElementById('extFilter').onchange = render;

  document.getElementById('btnExport').onclick = () => {
    const mapping = data.legacy
      .filter(i => i.status === 'replace' && i.replace_with)
      .map(i => ({
        legacy_path: i.legacy_path,
        display_path: i.display_path,
        filename: i.filename,
        replace_with: i.replace_with,
        notes: i.notes,
      }));
    const blob = new Blob([JSON.stringify({ mappings: mapping }, null, 2)], { type: 'application/json' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'asset-replacements.json';
    a.click();
    showToast('Exported ' + mapping.length + ' replacements');
  };

  document.getElementById('btnSaveLocal').onclick = () => {
    localStorage.setItem('ss_asset_board_progress', JSON.stringify(data.legacy.map(i => ({
      id: i.id, status: i.status, replace_with: i.replace_with, notes: i.notes
    }))));
    showToast('Saved progress in this browser');
  };
  document.getElementById('btnLoadLocal').onclick = () => {
    const raw = localStorage.getItem('ss_asset_board_progress');
    if (!raw) { showToast('No saved progress'); return; }
    const rows = JSON.parse(raw);
    const byId = Object.fromEntries(rows.map(r => [r.id, r]));
    for (const item of data.legacy) {
      const r = byId[item.id];
      if (!r) continue;
      item.status = r.status || item.status;
      item.replace_with = r.replace_with || '';
      item.notes = r.notes || '';
    }
    render();
    showToast('Loaded progress');
  };

  renderCandidates();
  render();
})();
</script>
</body>
</html>
"""


def write_board(manifest: dict, out_dir: Path) -> None:
    out_dir.mkdir(parents=True, exist_ok=True)
    data_json = json.dumps(manifest, ensure_ascii=False)
    html = BOARD_HTML.replace("__DATA__", data_json)
    (out_dir / "index.html").write_text(html, encoding="utf-8")
    (out_dir / "manifest.json").write_text(json.dumps(manifest, indent=2), encoding="utf-8")

    with (out_dir / "manifest.csv").open("w", newline="", encoding="utf-8") as f:
        w = csv.DictWriter(
            f,
            fieldnames=[
                "id",
                "display_path",
                "filename",
                "ext",
                "bytes",
                "status",
                "replace_with",
                "notes",
                "legacy_path",
            ],
        )
        w.writeheader()
        for row in manifest["legacy"]:
            w.writerow({k: row.get(k, "") for k in w.fieldnames})

    print(f"Board written: {out_dir / 'index.html'}")
    print(f"Legacy images: {len(manifest['legacy'])}")
    print(f"Candidates:    {len(manifest['candidates'])}")


def main() -> int:
    ap = argparse.ArgumentParser(description="Build Shadow Shinobi visual asset board")
    ap.add_argument("--legacy", action="append", default=[], help="Legacy asset root (repeatable)")
    ap.add_argument("--candidates", default="", help="Folder of replacement candidates")
    ap.add_argument("--out", default="assets-work/board", help="Output board directory")
    args = ap.parse_args()

    if not args.legacy:
        print("Provide at least one --legacy path", file=sys.stderr)
        return 2

    legacy_roots = [Path(p) for p in args.legacy]
    cand = Path(args.candidates) if args.candidates else None
    manifest = build_manifest(legacy_roots, cand)
    write_board(manifest, Path(args.out))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
