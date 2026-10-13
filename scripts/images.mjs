// Image pipeline: assets-src/images/**/*.{jpg,jpeg,png,webp} → public/assets/img/<same path>/
// Outputs AVIF + WebP + JPEG at widths [400, 800, 1200, 1600, 1920] (never upscaling) and
// writes public/assets/img/manifest.json (intrinsic size + generated widths) for app/lib/Image.php.
// Usage: npm run images

import { readdir, readFile, writeFile, mkdir, stat } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import sharp from 'sharp';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const srcDir = path.join(root, 'assets-src/images');
const outDir = path.join(root, 'public/assets/img');
const manifestFile = path.join(outDir, 'manifest.json');

const WIDTHS = [400, 800, 1200, 1600, 1920];
const INPUT_EXT = new Set(['.jpg', '.jpeg', '.png', '.webp']);
const FORMATS = {
  avif: { ext: 'avif', opts: { quality: 55, effort: 4 } },
  webp: { ext: 'webp', opts: { quality: 78 } },
  jpeg: { ext: 'jpg', opts: { quality: 80, mozjpeg: true, progressive: true } },
};

async function walk(dir) {
  let entries;
  try {
    entries = await readdir(dir, { withFileTypes: true });
  } catch {
    return [];
  }
  const files = [];
  for (const entry of entries) {
    const full = path.join(dir, entry.name);
    if (entry.isDirectory()) files.push(...(await walk(full)));
    else if (INPUT_EXT.has(path.extname(entry.name).toLowerCase())) files.push(full);
  }
  return files;
}

async function readManifest() {
  try {
    return JSON.parse(await readFile(manifestFile, 'utf8'));
  } catch {
    return {};
  }
}

const sources = await walk(srcDir);
const manifest = await readManifest();
let generated = 0;

for (const file of sources) {
  const rel = path.relative(srcDir, file).split(path.sep).join('/');
  const key = rel.replace(/\.[^.]+$/, '');
  const image = sharp(file, { failOn: 'none' }).rotate();
  const meta = await image.metadata();
  const srcStat = await stat(file);

  if (manifest[key]?.mtime === srcStat.mtimeMs && manifest[key]?.width === meta.width) {
    continue; // unchanged since last run
  }

  const widths = WIDTHS.filter((w) => w <= meta.width);
  if (!widths.includes(meta.width) && meta.width < Math.max(...WIDTHS)) widths.push(meta.width); // keep the intrinsic size
  widths.sort((a, b) => a - b);

  const target = path.join(outDir, path.dirname(rel));
  await mkdir(target, { recursive: true });
  const base = path.basename(key);

  for (const w of widths) {
    for (const [fmt, { ext, opts }] of Object.entries(FORMATS)) {
      const out = path.join(target, `${base}-${w}.${ext}`);
      await image.clone().resize({ width: w, withoutEnlargement: true })[fmt](opts).toFile(out);
      generated++;
    }
  }

  manifest[key] = {
    width: meta.width,
    height: meta.height,
    widths,
    formats: ['avif', 'webp', 'jpg'],
    mtime: srcStat.mtimeMs,
  };
  console.log(`[images] ${rel} → ${widths.join('/')}w × avif/webp/jpg`);
}

await mkdir(outDir, { recursive: true });
await writeFile(manifestFile, JSON.stringify(manifest, null, 2) + '\n', 'utf8');
console.log(`[images] ${sources.length} source image(s), ${generated} file(s) written, manifest: ${Object.keys(manifest).length} entr${Object.keys(manifest).length === 1 ? 'y' : 'ies'}`);
