"""Instance + subset the three brand fonts to Latin WOFF2 and compute metric-matched fallback overrides.
Usage: python -I fonts.py <project_root>
"""
import io
import json
import sys
from pathlib import Path

from fontTools.ttLib import TTFont
from fontTools.varLib import instancer
from fontTools import subset

root = Path(sys.argv[1])
nm = root / "node_modules"
out_dir = root / "public" / "assets" / "fonts"
out_dir.mkdir(parents=True, exist_ok=True)

# Latin + Latin-1 punctuation/symbols used on the site (£, €, ©, –, —, ‘’“”, …, •, →, ✓)
UNICODES = "U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0300-0301,U+0303-0304,U+0308-0309,U+0323,U+0329,U+2000-206F,U+20AC,U+2122,U+2190-2199,U+2212,U+2215,U+2713,U+FEFF,U+FFFD"

JOBS = [
    {
        "name": "mona-sans-var",
        "src": nm / "@fontsource-variable/mona-sans/files/mona-sans-latin-wght-normal.woff2",
        "axes": {"wght": (500, 700)},
        "fallback": "C:/Windows/Fonts/arial.ttf",
    },
    {
        "name": "google-sans-var",
        "src": nm / "@fontsource-variable/google-sans/files/google-sans-latin-wght-normal.woff2",
        "axes": {"wght": (400, 500)},
        "fallback": "C:/Windows/Fonts/arial.ttf",
    },
    {
        "name": "playfair-display-italic-500",
        "src": nm / "@fontsource/playfair-display/files/playfair-display-latin-500-italic.woff2",
        "axes": None,
        "fallback": "C:/Windows/Fonts/georgia.ttf",
    },
]


def metrics(font: TTFont) -> dict:
    upm = font["head"].unitsPerEm
    hhea = font["hhea"]
    os2 = font["OS/2"]
    # Browsers (Chromium/Firefox on Windows) use OS/2 typo metrics when USE_TYPO_METRICS is set, else hhea
    use_typo = bool(os2.fsSelection & (1 << 7))
    if use_typo:
        asc, desc, gap = os2.sTypoAscender, abs(os2.sTypoDescender), os2.sTypoLineGap
    else:
        asc, desc, gap = hhea.ascent, abs(hhea.descent), hhea.lineGap
    # Average width over the lowercase Latin alphabet weighted like typical English text
    cmap = font.getBestCmap()
    hmtx = font["hmtx"]
    weights = {"a": 8.2, "b": 1.5, "c": 2.8, "d": 4.3, "e": 12.7, "f": 2.2, "g": 2.0, "h": 6.1, "i": 7.0, "j": 0.2,
               "k": 0.8, "l": 4.0, "m": 2.4, "n": 6.7, "o": 7.5, "p": 1.9, "q": 0.1, "r": 6.0, "s": 6.3, "t": 9.1,
               "u": 2.8, "v": 1.0, "w": 2.4, "x": 0.2, "y": 2.0, "z": 0.1, " ": 18.0}
    total = 0.0
    wsum = 0.0
    for ch, wgt in weights.items():
        gid = cmap.get(ord(ch))
        if gid is None:
            continue
        total += hmtx[gid][0] / upm * wgt
        wsum += wgt
    return {"upm": upm, "ascent": asc / upm, "descent": desc / upm, "lineGap": gap / upm, "avgWidth": total / wsum}


report = {}
for job in JOBS:
    font = TTFont(job["src"], lazy=False)
    if job["axes"]:
        font = instancer.instantiateVariableFont(font, job["axes"], inplace=False, updateFontNames=False)
        # Round-trip through memory so every table is fully materialised before subsetting
        buf = io.BytesIO()
        font.flavor = None
        font.save(buf)
        buf.seek(0)
        font = TTFont(buf, lazy=False)
    options = subset.Options()
    options.flavor = "woff2"
    options.layout_features = ["*"]
    options.name_IDs = ["*"]
    options.notdef_outline = True
    options.recalc_bounds = True
    options.recalc_average_width = True
    subsetter = subset.Subsetter(options)
    subsetter.populate(unicodes=subset.parse_unicodes(UNICODES))
    subsetter.subset(font)
    out = out_dir / f"{job['name']}.woff2"
    font.flavor = "woff2"
    font.save(out)

    m = metrics(TTFont(out))
    fb = metrics(TTFont(job["fallback"]))
    size_adjust = m["avgWidth"] / fb["avgWidth"]
    report[job["name"]] = {
        "bytes": out.stat().st_size,
        "font": m,
        "fallback": fb,
        "overrides": {
            "size-adjust": f"{size_adjust * 100:.2f}%",
            "ascent-override": f"{m['ascent'] / size_adjust * 100:.2f}%",
            "descent-override": f"{m['descent'] / size_adjust * 100:.2f}%",
            "line-gap-override": f"{m['lineGap'] / size_adjust * 100:.2f}%",
        },
    }

print(json.dumps(report, indent=2))
