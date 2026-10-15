// Generates the brand-coloured background art in public/assets/img/art/ (docs/04 §6: the inspiration JPEGs are
// re-created as line art, never shipped). Colours are NOT in these files: each line group reads a custom property
// inherited from the <use> / inline element, with currentColor as the fallback, so the tokens stay in tokens.css.
//   grid-wave.svg   the azure "perspective grid-wave" (WhatsApp … 9.34.03 PM.jpeg): <g id="grid-wave"> for <use>
//   skyscraper.svg  the glass skyscraper, upward perspective, thin frame lines (some_section_bg.jpeg): <g id="skyscraper">
//   swirl.svg       the thin speed-line swirl (WhatsApp … 9.34.00 PM (1).jpeg): a standalone <svg> that templates
//                   inline (inline_svg) so its paths can be drawn on scroll with data-anim="draw"
// Usage: npm run art   (also runs automatically before `npm run build`)

import { writeFile, mkdir } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const outDir = path.join(root, 'public/assets/img/art');
const f = (n) => String(Math.round(n * 10) / 10);
const write = async (name, svg, note) => {
  const out = path.join(outDir, name);
  await writeFile(out, svg, 'utf8');
  console.log(`[art] wrote ${path.relative(root, out)}: ${note} (${(svg.length / 1024).toFixed(1)} KB)`);
};
await mkdir(outDir, { recursive: true });

/* ---------- 1. Grid-wave ---------- */
{
  // The drawing is 4 wave periods wide so a 1600-unit viewport can translate by one PERIOD and loop seamlessly.
  const WIDTH = 2400;
  const PERIOD = 600;
  const ROWS = 11;       // "horizontal" wave lines, dense at the top (far) and spaced out at the bottom (near)
  const COL_STEP = 60;   // spacing of the "vertical" lines that follow the sheet
  const SAMPLE = 24;     // sampling step for the wave lines

  const rows = Array.from({ length: ROWS }, (_, i) => {
    const t = i / (ROWS - 1);                 // 0 = far, 1 = near
    return {
      y: 36 + 350 * Math.pow(t, 1.45),        // perspective: rows get further apart as they come nearer
      amp: 8 + 30 * t,                        // near waves are bigger
      phase: i * 0.55,                        // rolling offset from row to row
      opacity: 0.25 + 0.75 * t,               // far rows fade
    };
  });
  const waveY = (row, x) => row.y + row.amp * Math.sin((2 * Math.PI * x) / PERIOD + row.phase);

  const horizontals = rows.map((row) => {
    let d = '';
    for (let x = 0; x <= WIDTH; x += SAMPLE) d += (x === 0 ? 'M' : 'L') + f(x) + ' ' + f(waveY(row, x));
    return `<path d="${d}" stroke-opacity="${f(row.opacity)}"/>`;
  });
  const verticals = [];
  for (let x = 0; x <= WIDTH; x += COL_STEP) {
    let d = '';
    rows.forEach((row, i) => { d += (i === 0 ? 'M' : 'L') + f(x) + ' ' + f(waveY(row, x)); });
    verticals.push(`<path d="${d}"/>`);
  }
  const svg =
    `<svg xmlns="http://www.w3.org/2000/svg">` +
    `<g id="grid-wave" fill="none" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">` +
    `<g style="stroke:var(--gw-v,currentColor)" opacity=".5">${verticals.join('')}</g>` +
    `<g style="stroke:var(--gw-h,currentColor)">${horizontals.join('')}</g>` +
    `</g></svg>\n`;
  await write('grid-wave.svg', svg, `${rows.length} wave rows, ${verticals.length} columns`);
}

/* ---------- 2. Glass skyscraper (upward perspective) ---------- */
{
  // Two glass facets meeting at a central edge, seen from the pavement: the mullions converge on a vanishing point
  // high above the top edge, the floor lines bunch up towards the top. Viewbox 1200 × 800; <use> it with
  // preserveAspectRatio="xMidYMax slice" so the base always touches the bottom of its container.
  const W = 1200, H = 800;
  const VP = { x: 600, y: -520 };                     // vanishing point above the frame
  const EDGE_X = 560;                                 // the building's corner edge (slightly off-centre)
  const toward = (x0, y0, t) => ({ x: x0 + (VP.x - x0) * t, y: y0 + (VP.y - y0) * t });
  const atY = (x0, y0, y) => x0 + (VP.x - x0) * ((y - y0) / (VP.y - y0));   // x of the line from (x0,y0) to VP at height y

  const mullions = [];
  const bases = [];
  for (let x = -520; x <= 1720; x += 88) bases.push(x);
  bases.forEach((x0, i) => {
    const top = toward(x0, H, 1);                     // towards the VP; clip at y = 0
    const xTop = atY(x0, H, 0);
    const far = Math.abs(x0 - EDGE_X) / 1200;
    mullions.push(`<path d="M${f(x0)} ${f(H)} L${f(xTop)} 0" stroke-opacity="${f(0.9 - 0.55 * far)}"${Math.abs(x0 - EDGE_X) < 44 ? ' stroke-width="2.5"' : ''}/>`);
    void top; void i;
  });

  const floors = [];
  const FLOORS = 22;
  for (let i = 0; i < FLOORS; i++) {
    const t = i / (FLOORS - 1);
    const y = H - 40 - (H - 60) * Math.pow(t, 1.9);   // dense towards the top
    const xl = atY(bases[0], H, y);
    const xr = atY(bases[bases.length - 1], H, y);
    const xe = atY(EDGE_X, H, y);
    // the two facets read as separate planes: each floor line bends slightly at the corner edge
    floors.push(`<path d="M${f(xl)} ${f(y)} L${f(xe)} ${f(y + 14 * (1 - t))} L${f(xr)} ${f(y)}" stroke-opacity="${f(0.35 + 0.5 * (1 - t))}"/>`);
  }
  const svg =
    `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}">` +
    `<g id="skyscraper" fill="none" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">` +
    `<g style="stroke:var(--sk-v,currentColor)">${mullions.join('')}</g>` +
    `<g style="stroke:var(--sk-h,currentColor)" opacity=".8">${floors.join('')}</g>` +
    `</g></svg>\n`;
  await write('skyscraper.svg', svg, `${mullions.length} mullions, ${floors.length} floors`);
}

/* ---------- 3. Speed-line swirl ---------- */
{
  // A bundle of long S-curves sweeping from the bottom-left up to the right, like the inspiration's thin
  // azure/navy speed lines. Standalone <svg> (viewBox 1600 × 700) so templates can inline it and draw the paths.
  const W = 1600, H = 700;
  const LINES = 9;
  const paths = [];
  for (let i = 0; i < LINES; i++) {
    const t = i / (LINES - 1);
    const o = (t - 0.5) * 2;                           // −1 … 1 spread
    const y0 = H - 40 - 120 * t;
    const d =
      `M${f(-60)} ${f(y0)} ` +
      `C${f(320 + 40 * o)} ${f(y0 - 20)}, ${f(520 + 60 * o)} ${f(H * 0.78 - 160 * t)}, ${f(820 + 30 * o)} ${f(H * 0.52 - 90 * t)} ` +
      `S${f(1240 - 40 * o)} ${f(120 + 60 * t)}, ${f(W + 60)} ${f(40 + 110 * t)}`;
    paths.push(`<path d="${d}" stroke-opacity="${f(0.35 + 0.65 * (1 - Math.abs(o)))}"${i === 4 ? ' stroke-width="2"' : ''}/>`);
  }
  const svg =
    `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" preserveAspectRatio="xMidYMid slice">` +
    `<g fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round">${paths.join('')}</g>` +
    `</svg>\n`;
  await write('swirl.svg', svg, `${paths.length} curves`);
}
