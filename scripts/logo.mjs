// Turns the client's horizontal logo JPEG (logos/) into web-ready PNGs until P07 ships the SVG lockups:
//   public/assets/img/logo/logo-horizontal.png        transparent background, original colours (light surfaces)
//   public/assets/img/logo/logo-horizontal-white.png  transparent background, wordmark + tagline in white (dark surfaces)
//   public/assets/img/logo/logo-mark-512.png          the three-shape mark on white, 512×512 (schema.org logo)
// Pixels that are (near) white become transparent; neutral grey pixels are the wordmark and are recoloured.
// Usage: npm run logo

import sharp from 'sharp';
import path from 'node:path';
import { mkdir } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const src = path.join(root, 'logos/My Solution Horizontal.jpg (1).jpeg');
const outDir = path.join(root, 'public/assets/img/logo');
await mkdir(outDir, { recursive: true });

const { data, info } = await sharp(src).ensureAlpha().raw().toBuffer({ resolveWithObject: true });
const { width, height } = info;

function convert(wordmarkRGB) {
  const out = Buffer.alloc(data.length);
  for (let i = 0; i < data.length; i += 4) {
    const r = data[i], g = data[i + 1], b = data[i + 2];
    const max = Math.max(r, g, b), min = Math.min(r, g, b);
    if (max - min < 28) {
      // neutral: wordmark/tagline (dark) → white background (light). Alpha from darkness.
      const lum = (r + g + b) / 3;
      const a = lum >= 238 ? 0 : Math.min(255, Math.round(((238 - lum) / 190) * 255));
      out[i] = wordmarkRGB[0]; out[i + 1] = wordmarkRGB[1]; out[i + 2] = wordmarkRGB[2]; out[i + 3] = a;
    } else {
      // coloured: the mark. Fade out only where it blends into the white background.
      const a = min >= 240 ? 0 : min > 185 ? Math.round(((240 - min) / 55) * 255) : 255;
      out[i] = r; out[i + 1] = g; out[i + 2] = b; out[i + 3] = a;
    }
  }
  return sharp(out, { raw: { width, height, channels: 4 } });
}

const trimmed = (img) => img.png().trim({ threshold: 1 });

const colour = await trimmed(convert([11, 18, 32])).resize({ height: 96 }).toFile(path.join(outDir, 'logo-horizontal.png'));
const white = await trimmed(convert([255, 255, 255])).resize({ height: 96 }).toFile(path.join(outDir, 'logo-horizontal-white.png'));

// Mark only: left ~38% of the lockup, trimmed, centred on a 512px white square with 12% padding
const fullBuf = await convert([11, 18, 32]).png().toBuffer();
const markBuf = await sharp(fullBuf).extract({ left: 0, top: 0, width: Math.round(width * 0.39), height }).png().toBuffer();
const markTrimmed = await sharp(markBuf).trim({ threshold: 1 }).png().toBuffer();
const markMeta = await sharp(markTrimmed).metadata();
const inner = 512 - 2 * 62;
const scale = Math.min(inner / markMeta.width, inner / markMeta.height);
const markResized = await sharp(markTrimmed).resize({ width: Math.round(markMeta.width * scale), height: Math.round(markMeta.height * scale) }).toBuffer();
await sharp({ create: { width: 512, height: 512, channels: 4, background: { r: 255, g: 255, b: 255, alpha: 1 } } })
  .composite([{ input: markResized, gravity: 'centre' }])
  .png()
  .toFile(path.join(outDir, 'logo-mark-512.png'));

console.log(`[logo] logo-horizontal.png ${colour.width}×${colour.height}, logo-horizontal-white.png ${white.width}×${white.height}, logo-mark-512.png 512×512`);
