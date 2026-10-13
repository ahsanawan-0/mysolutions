// Default Open Graph image (1200×630): brand gradient + hex pattern + wordmark + tagline.
// Colours are read from src/css/tokens.css so no hex lives here. Output: public/assets/og/default.jpg
// Temporary text-only mark until P07 delivers the logo SVG; generalised per page in P30.
// Usage: npm run og

import { readFile, writeFile, mkdir } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import sharp from 'sharp';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const tokens = await readFile(path.join(root, 'src/css/tokens.css'), 'utf8');
const token = (name) => {
  const m = tokens.match(new RegExp(`--${name}:\\s*(#[0-9A-Fa-f]{6})`));
  if (!m) throw new Error(`Token --${name} not found in tokens.css`);
  return m[1];
};

const W = 1200;
const H = 630;
const c = { navy900: token('navy-900'), navy700: token('navy-700'), azure: token('azure-500'), sky: token('sky-300'), white: token('ms-white') };

const svg = `
<svg xmlns="http://www.w3.org/2000/svg" width="${W}" height="${H}" viewBox="0 0 ${W} ${H}">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="${c.navy900}"/>
      <stop offset="0.6" stop-color="${c.navy700}"/>
      <stop offset="1" stop-color="${c.azure}"/>
    </linearGradient>
    <pattern id="hex" width="56" height="64" patternUnits="userSpaceOnUse">
      <path d="M28 3 L52 17 L52 47 L28 61 L4 47 L4 17 Z" fill="none" stroke="${c.sky}" stroke-opacity="0.16" stroke-width="1.5"/>
    </pattern>
    <linearGradient id="mark" x1="0" y1="1" x2="1" y2="0">
      <stop offset="0" stop-color="${c.sky}"/>
      <stop offset="0.5" stop-color="${c.azure}"/>
      <stop offset="1" stop-color="${c.white}"/>
    </linearGradient>
  </defs>
  <rect width="${W}" height="${H}" fill="url(#bg)"/>
  <rect width="${W}" height="${H}" fill="url(#hex)"/>
  <!-- Abstract "M" from three hexagon-derived shapes (logo anatomy, docs/02 §1) -->
  <g transform="translate(96 150) scale(1.0)">
    <polygon points="0,150 40,127 80,150 80,196 40,219 0,196" fill="${c.sky}"/>
    <polygon points="70,40 118,12 170,150 122,178" fill="${c.azure}"/>
    <polygon points="150,40 198,12 250,150 202,178" fill="${c.navy700}" stroke="${c.sky}" stroke-opacity="0.5" stroke-width="2"/>
  </g>
  <text x="400" y="300" font-family="Mona Sans, Arial, Helvetica, sans-serif" font-size="92" font-weight="600" fill="${c.white}" letter-spacing="-3">My Solutionz</text>
  <text x="404" y="362" font-family="Google Sans, Arial, Helvetica, sans-serif" font-size="36" fill="${c.sky}">Solutions Crafted for Success</text>
  <text x="404" y="440" font-family="Google Sans, Arial, Helvetica, sans-serif" font-size="26" fill="${c.white}" fill-opacity="0.85">Bid &amp; Proposal Management · Compliance &amp; Virtual Assistance · Digital Presence</text>
  <text x="404" y="560" font-family="Google Sans, Arial, Helvetica, sans-serif" font-size="28" fill="${c.white}" fill-opacity="0.7">mysolutionz.co.uk</text>
</svg>`;

const outDir = path.join(root, 'public/assets/og');
await mkdir(outDir, { recursive: true });
const out = path.join(outDir, 'default.jpg');
await sharp(Buffer.from(svg)).jpeg({ quality: 86, mozjpeg: true, progressive: true }).toFile(out);
console.log(`[og] wrote ${path.relative(root, out)} (${W}×${H})`);
