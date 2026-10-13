// Default Open Graph image (1200×630): brand gradient + hex pattern + the white horizontal logo lockup
// (public/assets/brand/logo-horizontal-white.svg, built by `npm run logo`) + the three service lines.
// Colours are read from src/css/tokens.css so no hex lives here. Output: public/assets/og/default.jpg
// Generalised per page in P30. Usage: npm run og

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

// The white lockup, inlined (its outer <svg> stripped) and scaled to 1000px wide
const lockup = await readFile(path.join(root, 'public/assets/brand/logo-horizontal-white.svg'), 'utf8');
const [, vbW, vbH] = lockup.match(/viewBox="0 0 ([\d.]+) ([\d.]+)"/) ?? [0, 1366, 291];
const lockupInner = lockup.replace(/^[\s\S]*?<svg[^>]*>/, '').replace(/<\/svg>\s*$/, '').replace(/<title>.*?<\/title>/s, '');
const lockupScale = 1000 / Number(vbW);
const lockupH = Number(vbH) * lockupScale;

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
  <g transform="translate(100 ${((H - 120) - lockupH) / 2 + 20}) scale(${lockupScale})">${lockupInner}</g>
  <text x="100" y="${H - 96}" font-family="Google Sans, Arial, Helvetica, sans-serif" font-size="26" fill="${c.white}" fill-opacity="0.85">Bid &amp; Proposal Management · Compliance &amp; Virtual Assistance · Digital Presence</text>
  <text x="100" y="${H - 50}" font-family="Google Sans, Arial, Helvetica, sans-serif" font-size="26" fill="${c.sky}">mysolutionz.co.uk</text>
</svg>`;

const outDir = path.join(root, 'public/assets/og');
await mkdir(outDir, { recursive: true });
const out = path.join(outDir, 'default.jpg');
await sharp(Buffer.from(svg)).jpeg({ quality: 86, mozjpeg: true, progressive: true }).toFile(out);
console.log(`[og] wrote ${path.relative(root, out)} (${W}×${H})`);
