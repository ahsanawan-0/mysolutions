// Generates public/assets/img/art/grid-wave.svg: the azure "perspective grid-wave" background art
// (docs/04 §6, inspirations/WhatsApp … 9.34.03 PM.jpeg) rebuilt as brand-coloured line art.
// The file holds one <g id="grid-wave"> that templates/components/grid-wave.php references with
// <use href="/assets/img/art/grid-wave.svg#grid-wave">. Colours are NOT in this file: each line group reads
// the --gw-h (horizontal) / --gw-v (vertical) custom properties inherited from the <use> element, with
// currentColor as the fallback, so the tokens stay in src/css/tokens.css.
// Usage: npm run art   (also runs automatically before `npm run build`)

import { writeFile, mkdir } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const outFile = path.join(root, 'public/assets/img/art/grid-wave.svg');

// Geometry. The drawing is 4 wave periods wide so a 1600-unit viewport can translate by one PERIOD and loop seamlessly.
const WIDTH = 2400;
const PERIOD = 600;
const ROWS = 11;       // "horizontal" wave lines, dense at the top (far) and spaced out at the bottom (near)
const COL_STEP = 60;   // spacing of the "vertical" lines that follow the sheet
const SAMPLE = 24;     // sampling step for the wave lines

const f = (n) => String(Math.round(n * 10) / 10);
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
  for (let x = 0; x <= WIDTH; x += SAMPLE) {
    d += (x === 0 ? 'M' : 'L') + f(x) + ' ' + f(waveY(row, x));
  }
  return `<path d="${d}" stroke-opacity="${f(row.opacity)}"/>`;
});

const verticals = [];
for (let x = 0; x <= WIDTH; x += COL_STEP) {
  let d = '';
  rows.forEach((row, i) => {
    d += (i === 0 ? 'M' : 'L') + f(x) + ' ' + f(waveY(row, x));
  });
  verticals.push(`<path d="${d}"/>`);
}

const svg =
  `<svg xmlns="http://www.w3.org/2000/svg">` +
  `<g id="grid-wave" fill="none" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">` +
  `<g style="stroke:var(--gw-v,currentColor)" opacity=".5">${verticals.join('')}</g>` +
  `<g style="stroke:var(--gw-h,currentColor)">${horizontals.join('')}</g>` +
  `</g></svg>\n`;

await mkdir(path.dirname(outFile), { recursive: true });
await writeFile(outFile, svg, 'utf8');
console.log(`[art] wrote ${path.relative(root, outFile)}: ${rows.length} wave rows, ${verticals.length} columns (${(svg.length / 1024).toFixed(1)} KB)`);
