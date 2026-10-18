// Image pipeline: assets-src/images/**/*.{jpg,jpeg,png,webp} → public/assets/img/<same path>/
// Outputs AVIF + WebP + JPEG at widths [400, 800, 1200, 1600, 1920] (never upscaling) and
// writes public/assets/img/manifest.json (intrinsic size + generated widths) for app/lib/Image.php.
// Images used by blog posts also get the article crops (16:9, 4:3, 1:1 + a 1200×630 OG crop; see the end, P26).
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

// Article crops (P26, docs/10 §5–§6): every image a blog post uses (`image:` in content/blog/*.md front matter) also
// gets the BlogPosting schema crops 16:9, 4:3 and 1:1 (at most 1200px wide, never upscaled) and a 1200×630 Open Graph
// crop, as <key>-16x9.jpg / -4x3.jpg / -1x1.jpg / -og.jpg next to its renditions. Recorded as `crops` in the manifest
// entry ({ name: [width, height] }) for App\Image::crop().
const CROPS = { '16x9': [16, 9], '4x3': [4, 3], '1x1': [1, 1] };
const OG = [1200, 630];
const blogDir = path.join(root, 'content/blog');
const postImages = new Set();
for (const name of await readdir(blogDir).catch(() => [])) {
  if (!name.endsWith('.md')) continue;
  const front = (await readFile(path.join(blogDir, name), 'utf8')).match(/^---\r?\n([\s\S]*?)\r?\n---/);
  const key = front?.[1].match(/^image:\s*["']?([^"'\r\n]+?)["']?\s*$/m)?.[1];
  if (key) postImages.add(key);
}
let cropped = 0;
for (const key of postImages) {
  const file = sources.find((f) => path.relative(srcDir, f).split(path.sep).join('/').replace(/\.[^.]+$/, '') === key);
  const entry = manifest[key];
  if (!file || !entry) {
    console.log(`[images] crops: no source image for "${key}" (used by a blog post)`);
    continue;
  }
  const target = path.join(outDir, path.dirname(key));
  const base = path.basename(key);
  const names = [...Object.keys(CROPS), 'og'];
  const exists = await Promise.all(names.map((n) => stat(path.join(target, `${base}-${n}.jpg`)).then(() => true, () => false)));
  if (entry.crops && entry.crops.mtime === entry.mtime && exists.every(Boolean)) continue;

  const crops = { mtime: entry.mtime };
  const image = sharp(file, { failOn: 'none' }).rotate();
  for (const [name, [rw, rh]] of Object.entries(CROPS)) {
    const w = Math.min(1200, entry.width, Math.floor((entry.height * rw) / rh));
    const h = Math.round((w * rh) / rw);
    await image.clone().resize(w, h, { fit: 'cover', position: 'attention' }).jpeg(FORMATS.jpeg.opts).toFile(path.join(target, `${base}-${name}.jpg`));
    crops[name] = [w, h];
    cropped++;
  }
  // Open Graph: always exactly 1200×630 (the <head> declares that size), upscaled from a smaller source if need be
  await image.clone().resize(OG[0], OG[1], { fit: 'cover', position: 'attention' }).jpeg({ quality: 84, mozjpeg: true, progressive: true }).toFile(path.join(target, `${base}-og.jpg`));
  crops.og = OG;
  cropped++;
  entry.crops = crops;
  console.log(`[images] ${key} → crops ${Object.keys(CROPS).map((n) => `${n} ${crops[n].join('×')}`).join(', ')}, og 1200×630`);
}
if (cropped) generated += cropped;

await mkdir(outDir, { recursive: true });
await writeFile(manifestFile, JSON.stringify(manifest, null, 2) + '\n', 'utf8');
console.log(`[images] ${sources.length} source image(s), ${generated} file(s) written, manifest: ${Object.keys(manifest).length} entr${Object.keys(manifest).length === 1 ? 'y' : 'ies'}`);
