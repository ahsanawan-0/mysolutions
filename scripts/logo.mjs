// Brand assets from the client's logo JPEGs (logos/), rebuilt as exact vectors. Usage: npm run logo
//
// The mark is three polygons measured from the 1563px vertical lockup (docs/07 §2, P07). The wordmark and tagline
// are set in Khand (OFL, Indian Type Foundry; build-time only, never loaded on the site): the font was identified by
// fitting candidates to the JPEG pixels (Khand Regular IoU 0.98 / ink-weight 1.00 for "My Solutionz", Khand Medium
// IoU 0.98 for the tagline; Saira Condensed, the docs' guess, scored 0.32). Each lockup is measured from its own JPEG:
// cap height sets the font size, baseline and left edge come from the glyph boxes, letter-spacing is a least-squares
// fit to the glyph columns, then every glyph is outlined to a path (one <path class="letter"> per letter).
//
// Outputs
//   public/assets/brand/logo-mark.svg, logo-mark-white.svg, logo-horizontal.svg, logo-horizontal-white.svg,
//   logo-vertical.svg, logo-splash.svg (vertical + the staging light streak, for the splash choreography), logo-512.png
//   public/favicon.ico, icon-192.png, icon-512.png, apple-touch-icon.png, icon.svg, site.webmanifest
//   design/logo-motion/logo.svg (= logo-splash.svg, the pixel2motion input) and outputs/fit_work/geometry.json
//   (fit metrics + the bar wipe clip-path polygons used by design/logo-motion/motion.css and src/css/components/logo.css)
// Colours are read from src/css/tokens.css so no hex lives here. Ids: #mark › #hex-sky #bar-azure #bar-navy,
// #wordmark › .letter (#letter-1 …), #tagline. inline_svg() prefixes the ids when a lockup is inlined more than once.

import { readFile, writeFile, mkdir, rm } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import sharp from 'sharp';
import opentype from 'opentype.js';
import pngToIco from 'png-to-ico';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const SOURCES = {
  vertical: path.join(root, 'logos/Vertical Lockup on White Background.jpg (1).jpeg'),
  horizontal: path.join(root, 'logos/My Solution Horizontal.jpg (1).jpeg'),
};
const FONTS = {
  wordmark: path.join(root, 'node_modules/@fontsource/khand/files/khand-latin-400-normal.woff'),
  tagline: path.join(root, 'node_modules/@fontsource/khand/files/khand-latin-500-normal.woff'),
};
const WORDMARK = 'My Solutionz';
const TAGLINE = 'Solutions Crafted for Success';
const TITLE = 'My Solutionz';

// The mark, measured from the vertical lockup at 1:1 (viewBox 0 0 640 366). Paint order: sky, azure, navy.
const MARK = {
  w: 640,
  h: 366,
  pieces: [
    { id: 'hex-sky', cls: 'logo__hex', token: 'ms-sky', points: [[0, 274], [53, 182], [160, 182], [213, 274], [160, 366], [53, 366]] },
    { id: 'bar-azure', cls: 'logo__bar logo__bar--azure', token: 'ms-azure', points: [[106, 89], [158, 0], [268, 0], [426, 275], [374, 366], [265, 366]] },
    { id: 'bar-navy', cls: 'logo__bar logo__bar--navy', token: 'ms-navy', points: [[320, 89], [371, 0], [481, 0], [640, 275], [587, 366], [478, 366]] },
  ],
};

/* ---------- tokens ---------- */
const tokensCss = await readFile(path.join(root, 'src/css/tokens.css'), 'utf8');
const token = (name) => {
  const m = tokensCss.match(new RegExp(`--${name}:\\s*(#[0-9A-Fa-f]{6})`));
  if (!m) throw new Error(`Token --${name} not found in tokens.css`);
  return m[1];
};
const c = {
  sky: token('ms-sky'), azure: token('ms-azure'), navy: token('ms-navy'), navyLight: token('navy-400'),
  ink: token('ms-ink'), white: token('ms-white'), canvas: token('ms-canvas'), theme: token('navy-900'),
};

/* ---------- measurement ---------- */
function runs(values, min = 1) {
  const out = []; let start = -1;
  for (let i = 0; i <= values.length; i++) {
    const on = i < values.length && values[i] >= min;
    if (on && start < 0) start = i;
    if (!on && start >= 0) { out.push([start, i - 1]); start = -1; }
  }
  return out;
}

/** Mark bbox + text bands (wordmark, tagline) with per-glyph column boxes, from one JPEG. */
async function measure(file) {
  const { data, info } = await sharp(file).raw().toBuffer({ resolveWithObject: true });
  const { width: w, height: h, channels: ch } = info;
  const isMark = new Uint8Array(w * h), isText = new Uint8Array(w * h);
  for (let i = 0; i < w * h; i++) {
    const r = data[i * ch], g = data[i * ch + 1], b = data[i * ch + 2];
    const max = Math.max(r, g, b), min = Math.min(r, g, b);
    if (max - min >= 40 && b > r) isMark[i] = 1;
    else if (max - min < 40 && max < 140) isText[i] = 1;
  }
  const bbox = (mask, xa = 0, xb = w - 1, ya = 0, yb = h - 1) => {
    let x0 = w, y0 = h, x1 = -1, y1 = -1;
    for (let y = ya; y <= yb; y++) for (let x = xa; x <= xb; x++) if (mask[y * w + x]) { x0 = Math.min(x0, x); x1 = Math.max(x1, x); y0 = Math.min(y0, y); y1 = Math.max(y1, y); }
    return { x0, y0, x1, y1, w: x1 - x0 + 1, h: y1 - y0 + 1 };
  };
  const rows = new Array(h).fill(0);
  for (let y = 0; y < h; y++) for (let x = 0; x < w; x++) rows[y] += isText[y * w + x];
  const bands = runs(rows).filter(([a, b]) => b - a > 5).map(([a, b]) => {
    const cols = new Array(w).fill(0);
    for (let y = a; y <= b; y++) for (let x = 0; x < w; x++) cols[x] += isText[y * w + x];
    const glyphs = runs(cols).map(([x0, x1]) => bbox(isText, x0, x1, a, b));
    return { y0: a, y1: b, glyphs };
  });
  if (bands.length !== 2) throw new Error(`${path.basename(file)}: expected 2 text bands (wordmark, tagline), found ${bands.length}`);
  return { w, h, mark: bbox(isMark), wordmark: bands[0], tagline: bands[1], isText };
}

/* ---------- type fitting ---------- */
async function loadFont(file) {
  const buf = await readFile(file);
  return opentype.parse(buf.buffer.slice(buf.byteOffset, buf.byteOffset + buf.byteLength));
}
const glyphBox = (font, glyph) => glyph.getPath(0, 0, font.unitsPerEm).getBoundingBox(); // y1 = top (negative)

/** Natural glyph positions (kerning + letter-spacing), x from 0 */
function layout(font, text, fontSize, ls) {
  const glyphs = font.stringToGlyphs(text);
  const k = fontSize / font.unitsPerEm;
  let x = 0;
  return glyphs.map((g, i) => {
    const bb = glyphBox(font, g);
    const item = { ch: text[i], glyph: g, x, left: x + bb.x1 * k, right: x + bb.x2 * k };
    x += g.advanceWidth * k + ls;
    if (i + 1 < glyphs.length) x += font.getKerningValue(g, glyphs[i + 1]) * k;
    return item;
  });
}

/**
 * Fit one text line to a measured band: font size from the first glyph's cap height, baseline from its bottom,
 * letter-spacing by least squares against the measured glyph columns (or from the total span when the columns
 * do not segment one-to-one), left edge from the first column. Returns per-glyph paths in source pixel coordinates.
 */
function fitLine(font, text, band, { decimals }) {
  const first = band.glyphs[0];
  const baseline = first.y1 + 1;
  const capPx = baseline - first.y0;
  const fontSize = capPx / (-glyphBox(font, font.charToGlyph(text[0])).y1 / font.unitsPerEm);
  const natural = layout(font, text, fontSize, 0).filter((g) => g.ch !== ' ');
  const gapIndex = []; let gi = 0; for (const ch of text) { if (ch !== ' ') gapIndex.push(gi); gi++; }
  let ls, x0;
  if (band.glyphs.length === natural.length) {
    const n = natural.length; let sx = 0, sy = 0, sxx = 0, sxy = 0;
    for (let i = 0; i < n; i++) { const yv = band.glyphs[i].x0 - natural[i].left; sx += gapIndex[i]; sy += yv; sxx += gapIndex[i] ** 2; sxy += gapIndex[i] * yv; }
    ls = (n * sxy - sx * sy) / (n * sxx - sx * sx);
    x0 = (sy - ls * sx) / n;
  } else {
    const measuredSpan = band.glyphs.at(-1).x1 + 1 - first.x0;
    const naturalSpan = natural.at(-1).right - natural[0].left;
    ls = (measuredSpan - naturalSpan) / gapIndex.at(-1);
    x0 = first.x0 - natural[0].left;
  }
  const placed = layout(font, text, fontSize, ls).filter((g) => g.ch !== ' ');
  const resid = band.glyphs.length === placed.length
    ? placed.reduce((s, g, i) => s + Math.abs(g.left + x0 - band.glyphs[i].x0), 0) / placed.length
    : null;
  return {
    fontSize, ls, baseline, x0, resid,
    left: placed[0].left + x0,
    right: placed.at(-1).right + x0,
    glyphs: placed.map((g) => ({ ch: g.ch, path: (ox, oy) => g.glyph.getPath(x0 + g.x - ox, baseline - oy, fontSize).toPathData(decimals) })),
  };
}

/** IoU + ink-weight ratio of outlined text against the source text mask, inside a region. */
async function scoreText(paths, src, region) {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${src.w}" height="${src.h}">${paths.map((d) => `<path d="${d}"/>`).join('')}</svg>`;
  const png = await sharp(Buffer.from(svg)).ensureAlpha().raw().toBuffer();
  let inter = 0, union = 0, s = 0, r = 0;
  for (let y = region.y0; y <= region.y1; y++) for (let x = region.x0; x <= region.x1; x++) {
    const i = y * src.w + x; const a = src.isText[i] === 1; const b = png[i * 4 + 3] > 127;
    if (a) s++; if (b) r++; if (a && b) inter++; if (a || b) union++;
  }
  return { iou: +(inter / union).toFixed(4), ink_weight: +(r / s).toFixed(3) };
}

/* ---------- SVG assembly ---------- */
const fmt = (n) => (Math.round(n * 10) / 10).toString();

function markSvgParts(scale, ox, oy, fills, { pieceAttr = true } = {}) {
  return MARK.pieces.map((p) => {
    const pts = p.points.map(([x, y]) => `${fmt(x * scale + ox)},${fmt(y * scale + oy)}`).join(' ');
    return `<polygon id="${p.id}" class="${p.cls}"${pieceAttr ? ` data-piece="${p.id}"` : ''} fill="${fills[p.id]}" points="${pts}"/>`;
  }).join('\n    ');
}

/** One lockup (vertical or horizontal) from its measured JPEG, normalised so the content box starts at 0,0. */
async function lockup(name, src, fonts, { white = false, streak = false } = {}) {
  const word = fitLine(fonts.wordmark, WORDMARK, src.wordmark, { decimals: 1 });
  const tag = fitLine(fonts.tagline, TAGLINE, src.tagline, { decimals: 1 });
  const scale = src.mark.w / MARK.w;
  const x0 = Math.floor(Math.min(src.mark.x0, word.left, tag.left));
  const y0 = Math.floor(Math.min(src.mark.y0, src.wordmark.y0, src.tagline.y0));
  const x1 = Math.ceil(Math.max(src.mark.x1 + 1, word.right, tag.right));
  const y1 = Math.ceil(Math.max(src.mark.y1 + 1, src.wordmark.y1 + 1, src.tagline.y1 + 1));
  const W = x1 - x0, H = y1 - y0;
  const fills = white
    ? { 'hex-sky': c.sky, 'bar-azure': c.azure, 'bar-navy': c.navyLight }
    : { 'hex-sky': c.sky, 'bar-azure': c.azure, 'bar-navy': c.navy };
  const text = white ? c.white : c.ink;
  const letters = word.glyphs.map((g, i) => `<path id="letter-${i + 1}" class="letter" data-letter="${g.ch}" d="${g.path(x0, y0)}"/>`).join('\n    ');
  const tagPath = tag.glyphs.map((g) => g.path(x0, y0)).join(' ');
  const markOx = src.mark.x0 - x0, markOy = src.mark.y0 - y0;
  // Staging light streak (splash only): a soft azure band parallel to the bars (60° "\"), base opacity 0 so the
  // static logo, ?static=1, reduced motion and the final frame are untouched. The <g> is what motion.css animates.
  const streakSvg = streak ? (() => {
    const cx = markOx + (MARK.w * scale) / 2, cy = markOy + (MARK.h * scale) / 2;
    const len = Math.hypot(MARK.w, MARK.h) * scale * 1.3, wid = 70 * scale;
    return `
  <defs>
    <linearGradient id="streak-grad" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0" stop-color="${c.azure}" stop-opacity="0"/>
      <stop offset="0.5" stop-color="${c.azure}" stop-opacity="1"/>
      <stop offset="1" stop-color="${c.azure}" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <g id="streak" class="logo__streak" opacity="0">
    <rect x="${fmt(cx - wid / 2)}" y="${fmt(cy - len / 2)}" width="${fmt(wid)}" height="${fmt(len)}" fill="url(#streak-grad)" transform="rotate(30 ${fmt(cx)} ${fmt(cy)})"/>
  </g>`;
  })() : '';
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" class="logo logo--${name}">
  <title>${TITLE}</title>${streakSvg}
  <g id="mark" class="logo__mark">
    ${markSvgParts(scale, markOx, markOy, fills)}
  </g>
  <g id="wordmark" class="logo__wordmark" fill="${text}">
    ${letters}
  </g>
  <g id="tagline" class="logo__tagline" fill="${text}">
    <path d="${tagPath}"/>
  </g>
</svg>
`;
  // The same lockup placed in the source JPEG's pixel frame, for the pixel2motion overlay check (render_overlay.py
  // renders an SVG at the source size, so the viewBox has to be the source canvas).
  const inSourceFrame = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${src.w} ${src.h}"><rect width="${src.w}" height="${src.h}" fill="${c.white}"/><svg x="${x0}" y="${y0}" width="${W}" height="${H}" viewBox="0 0 ${W} ${H}">${svg.replace(/^<svg[^>]*>/, '').replace(/<\/svg>\s*$/, '')}</svg></svg>\n`;
  return { svg, inSourceFrame, W, H, x0, y0, word, tag, scale, markOx, markOy };
}

function markSvg({ white = false } = {}) {
  const fills = white
    ? { 'hex-sky': c.white, 'bar-azure': c.white, 'bar-navy': c.white }
    : { 'hex-sky': c.sky, 'bar-azure': c.azure, 'bar-navy': c.navy };
  const parts = markSvgParts(1, 0, 0, fills);
  const body = white
    ? parts.replace('id="hex-sky"', 'id="hex-sky" fill-opacity="0.55"').replace('id="bar-azure"', 'id="bar-azure" fill-opacity="0.8"')
    : parts;
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${MARK.w} ${MARK.h}" class="logo logo--mark${white ? ' logo--white' : ''}">
  <title>${TITLE}</title>
  <g id="mark" class="logo__mark">
    ${body}
  </g>
</svg>
`;
}

/* ---------- bar wipe geometry (clip-path polygons, fill-box percentages) ---------- */
// The bars reveal "up their own 60° diagonal": a straight cut perpendicular to the bar axis sweeps from the
// bottom-right tip to the top-left tip. Four polygon vertices: two on the cut line, two far past the revealed end.
// Keyframes interpolate the vertices linearly, so the band slides along the axis.
function wipePolygons(points) {
  const xs = points.map((p) => p[0]), ys = points.map((p) => p[1]);
  const bx = Math.min(...xs), by = Math.min(...ys), bw = Math.max(...xs) - bx, bh = Math.max(...ys) - by;
  const u = [Math.cos(Math.PI / 3), Math.sin(Math.PI / 3)];   // bar axis, 60° below horizontal, pointing down-right
  const v = [u[1], -u[0]];                                      // perpendicular
  const proj = points.map(([x, y]) => x * u[0] + y * u[1]);
  const pMin = Math.min(...proj) - 3, pMax = Math.max(...proj) + 3;
  const centre = [bx + bw / 2, by + bh / 2];
  const cProj = centre[0] * u[0] + centre[1] * u[1];
  const D = Math.hypot(bw, bh) * 1.5;
  const pct = ([x, y]) => `${fmt(((x - bx) / bw) * 100)}% ${fmt(((y - by) / bh) * 100)}%`;
  const polygon = (T) => {
    const cen = [centre[0] + (T - cProj) * u[0], centre[1] + (T - cProj) * u[1]];
    const A = [cen[0] + v[0] * D, cen[1] + v[1] * D];
    const B = [cen[0] - v[0] * D, cen[1] - v[1] * D];
    const C = [B[0] + u[0] * D, B[1] + u[1] * D];
    const E = [A[0] + u[0] * D, A[1] + u[1] * D];
    return `polygon(${[A, B, C, E].map(pct).join(', ')})`;
  };
  return { hidden: polygon(pMax), visible: polygon(pMin), axis_px: pMax - pMin };
}

/* ---------- raster outputs ---------- */
async function markPng(size, { background, padding }) {
  const inner = size - padding * 2;
  const k = Math.min(inner / MARK.w, inner / MARK.h);
  const w = Math.round(MARK.w * k), h = Math.round(MARK.h * k);
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}" viewBox="0 0 ${MARK.w} ${MARK.h}">${markSvgParts(1, 0, 0, { 'hex-sky': c.sky, 'bar-azure': c.azure, 'bar-navy': c.navy }, { pieceAttr: false })}</svg>`;
  const mark = await sharp(Buffer.from(svg)).png().toBuffer();
  return sharp({ create: { width: size, height: size, channels: 4, background } })
    .composite([{ input: mark, gravity: 'centre' }])
    .png();
}

/* ---------- main ---------- */
const fonts = { wordmark: await loadFont(FONTS.wordmark), tagline: await loadFont(FONTS.tagline) };
const brandDir = path.join(root, 'public/assets/brand');
const designDir = path.join(root, 'design/logo-motion');
await mkdir(brandDir, { recursive: true });
await mkdir(path.join(designDir, 'outputs/fit_work'), { recursive: true });

const vertical = await measure(SOURCES.vertical);
const horizontal = await measure(SOURCES.horizontal);
const V = await lockup('vertical', vertical, fonts);
const VS = await lockup('vertical', vertical, fonts, { streak: true });
const Hc = await lockup('horizontal', horizontal, fonts);
const Hw = await lockup('horizontal', horizontal, fonts, { white: true });

await writeFile(path.join(brandDir, 'logo-vertical.svg'), V.svg);
await writeFile(path.join(brandDir, 'logo-splash.svg'), VS.svg);
await writeFile(path.join(brandDir, 'logo-horizontal.svg'), Hc.svg);
await writeFile(path.join(brandDir, 'logo-horizontal-white.svg'), Hw.svg);
await writeFile(path.join(brandDir, 'logo-mark.svg'), markSvg());
await writeFile(path.join(brandDir, 'logo-mark-white.svg'), markSvg({ white: true }));
await writeFile(path.join(designDir, 'logo.svg'), VS.svg);
await writeFile(path.join(designDir, 'outputs/fit_work/vertical_in_source_frame.svg'), V.inSourceFrame);
await writeFile(path.join(designDir, 'outputs/fit_work/horizontal_in_source_frame.svg'), Hc.inSourceFrame);

// logo-512.png: the vertical lockup on white (schema.org Organization logo)
{
  const inner = 512 - 2 * 48;
  const k = Math.min(inner / V.W, inner / V.H);
  const lock = await sharp(Buffer.from(V.svg)).resize({ width: Math.round(V.W * k), height: Math.round(V.H * k) }).png().toBuffer();
  await sharp({ create: { width: 512, height: 512, channels: 4, background: { r: 255, g: 255, b: 255, alpha: 1 } } })
    .composite([{ input: lock, gravity: 'centre' }]).png().toFile(path.join(brandDir, 'logo-512.png'));
}

// Favicons (docs/10 §7): ICO 16/32/48 (transparent), PNG 192/512 + apple-touch-icon 180 (mark on white), icon.svg
const transparent = { r: 0, g: 0, b: 0, alpha: 0 };
const white = { r: 255, g: 255, b: 255, alpha: 1 };
const ico = await Promise.all([16, 32, 48].map((s) => markPng(s, { background: transparent, padding: Math.round(s * 0.04) }).then((i) => i.toBuffer())));
await writeFile(path.join(root, 'public/favicon.ico'), await pngToIco(ico));
await (await markPng(192, { background: white, padding: 20 })).toFile(path.join(root, 'public/icon-192.png'));
await (await markPng(512, { background: white, padding: 52 })).toFile(path.join(root, 'public/icon-512.png'));
await (await markPng(180, { background: white, padding: 22 })).toFile(path.join(root, 'public/apple-touch-icon.png'));
await writeFile(path.join(root, 'public/icon.svg'), `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${MARK.w} ${MARK.w}">
  <title>${TITLE}</title>
  <style>@media (prefers-color-scheme: dark) { #bar-navy { fill: ${c.navyLight}; } }</style>
  <g transform="translate(0 ${(MARK.w - MARK.h) / 2})">
    ${markSvgParts(1, 0, 0, { 'hex-sky': c.sky, 'bar-azure': c.azure, 'bar-navy': c.navy }, { pieceAttr: false })}
  </g>
</svg>
`);
await writeFile(path.join(root, 'public/site.webmanifest'), JSON.stringify({
  name: 'My Solutionz',
  short_name: 'My Solutionz',
  description: 'Bid and proposal management, compliance and virtual assistance, and digital presence services for UK businesses.',
  start_url: '/',
  display: 'browser',
  background_color: c.canvas,
  theme_color: c.theme,
  icons: [
    { src: '/icon-192.png', sizes: '192x192', type: 'image/png' },
    { src: '/icon-512.png', sizes: '512x512', type: 'image/png' },
  ],
}, null, 2) + '\n');

// The P05 PNG stand-ins are superseded by the SVGs
await rm(path.join(root, 'public/assets/img/logo'), { recursive: true, force: true });

// Fit evidence + motion geometry
const score = async (src, fit, band) => scoreText(fit.glyphs.map((g) => g.path(0, 0)), src, { x0: Math.max(0, Math.floor(fit.left) - 20), x1: Math.min(src.w - 1, Math.ceil(fit.right) + 20), y0: Math.max(0, band.y0 - 12), y1: Math.min(src.h - 1, band.y1 + 12) });
const geometry = {
  font: { family: 'Khand', wordmark_weight: 400, tagline_weight: 500, licence: 'OFL-1.1 (Indian Type Foundry)', note: 'Saira Condensed (docs guess) scored IoU 0.32; Khand chosen by pixel fit' },
  lockups: {},
  bar_wipes: Object.fromEntries(MARK.pieces.slice(1).map((p) => [p.id, wipePolygons(p.points)])),
  mark_bbox: MARK.pieces.map((p) => ({ id: p.id, x: Math.min(...p.points.map((q) => q[0])), y: Math.min(...p.points.map((q) => q[1])), w: Math.max(...p.points.map((q) => q[0])) - Math.min(...p.points.map((q) => q[0])), h: Math.max(...p.points.map((q) => q[1])) - Math.min(...p.points.map((q) => q[1])) })),
};
for (const [name, src, L] of [['vertical', vertical, V], ['horizontal', horizontal, Hc]]) {
  geometry.lockups[name] = {
    source_px: [src.w, src.h], viewBox: [L.W, L.H], mark_scale: +L.scale.toFixed(4), mark_offset: [L.markOx, L.markOy],
    wordmark: { font_size: +L.word.fontSize.toFixed(2), letter_spacing: +L.word.ls.toFixed(2), baseline: L.word.baseline, left: +L.word.left.toFixed(1), column_residual_px: L.word.resid === null ? null : +L.word.resid.toFixed(2), ...(await score(src, L.word, src.wordmark)) },
    tagline: { font_size: +L.tag.fontSize.toFixed(2), letter_spacing: +L.tag.ls.toFixed(2), baseline: L.tag.baseline, left: +L.tag.left.toFixed(1), column_residual_px: L.tag.resid === null ? null : +L.tag.resid.toFixed(2), ...(await score(src, L.tag, src.tagline)) },
  };
}
await writeFile(path.join(designDir, 'outputs/fit_work/geometry.json'), JSON.stringify(geometry, null, 2) + '\n');

const kb = (s) => `${(Buffer.byteLength(s) / 1024).toFixed(1)} KB`;
console.log(`[logo] vertical ${V.W}Ã—${V.H} (${kb(V.svg)}), horizontal ${Hc.W}Ã—${Hc.H} (${kb(Hc.svg)}), splash ${kb(VS.svg)}`);
for (const [name, l] of Object.entries(geometry.lockups)) {
  console.log(`[logo] ${name}: wordmark Khand 400 @ ${l.wordmark.font_size}px ls ${l.wordmark.letter_spacing} â†’ IoU ${l.wordmark.iou} ink ${l.wordmark.ink_weight}; tagline Khand 500 @ ${l.tagline.font_size}px ls ${l.tagline.letter_spacing} â†’ IoU ${l.tagline.iou} ink ${l.tagline.ink_weight}`);
}
console.log('[logo] bar wipe clip-paths â†’ design/logo-motion/outputs/fit_work/geometry.json');
console.log('[logo] favicon.ico, icon-192/512.png, apple-touch-icon.png, icon.svg, site.webmanifest, brand/logo-512.png written');
