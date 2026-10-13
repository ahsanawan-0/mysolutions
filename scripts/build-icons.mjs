// Builds public/assets/icons/sprite.svg from @phosphor-icons/core.
// Source files: node_modules/@phosphor-icons/core/assets/<weight>/<name>-<weight>.svg (regular: <name>.svg)
// Each becomes <symbol id="<name>-<weight>" viewBox="0 0 256 256">…</symbol>.
// Usage: npm run icons   (also runs automatically before `npm run build`)

import { readFile, writeFile, mkdir } from 'node:fs/promises';
import { existsSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const listFile = path.join(root, 'src/icons.json');
const assetsDir = path.join(root, 'node_modules/@phosphor-icons/core/assets');
const outFile = path.join(root, 'public/assets/icons/sprite.svg');

const list = JSON.parse(await readFile(listFile, 'utf8'));
const weights = Object.keys(list).filter((k) => !k.startsWith('$'));

const symbols = [];
const missing = [];
let count = 0;

for (const weight of weights) {
  const names = [...new Set(list[weight])].sort();
  for (const name of names) {
    // Phosphor naming: regular = <name>.svg, every other weight = <name>-<weight>.svg
    const file = path.join(assetsDir, weight, weight === 'regular' ? `${name}.svg` : `${name}-${weight}.svg`);
    if (!existsSync(file)) {
      missing.push(`${name} (${weight})`);
      continue;
    }
    const svg = await readFile(file, 'utf8');
    const inner = svg.replace(/^[\s\S]*?<svg[^>]*>/, '').replace(/<\/svg>\s*$/, '').trim();
    // Phosphor icons are single-colour paths filled with currentColor via the <use> context.
    symbols.push(`<symbol id="${name}-${weight}" viewBox="0 0 256 256">${inner}</symbol>`);
    count++;
  }
}

if (missing.length) {
  console.error(`[icons] ${missing.length} icon(s) not found in @phosphor-icons/core:\n  - ${missing.join('\n  - ')}`);
  process.exit(1);
}

const sprite = `<svg xmlns="http://www.w3.org/2000/svg" style="display:none">${symbols.join('')}</svg>\n`;
await mkdir(path.dirname(outFile), { recursive: true });
await writeFile(outFile, sprite, 'utf8');
console.log(`[icons] wrote ${path.relative(root, outFile)}: ${count} symbols (${(sprite.length / 1024).toFixed(1)} KB)`);
