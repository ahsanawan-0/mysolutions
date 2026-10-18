<?php
declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Responsive <picture> output from the image manifest written by scripts/images.mjs,
 * with a branded inline-SVG placeholder for images that don't exist yet.
 *
 * Usage via the helper: img('hero/london-office', ['alt' => '…', 'sizes' => '(min-width: 1024px) 50vw, 100vw']);
 */
final class Image
{
    private const BASE = '/assets/img/';

    /** @var array<string, array{width:int,height:int,widths:int[],formats:string[]}>|null */
    private static ?array $manifest = null;

    /**
     * @param array{
     *   alt?: string, decorative?: bool, sizes?: string, priority?: bool, class?: string,
     *   ratio?: string, width?: int, height?: int, loading?: string, attrs?: array<string,string>, cover?: bool,
     *   pattern?: bool   (placeholder only: false drops the hexagon pattern, e.g. where it would read as a design)
     * } $o
     */
    public static function render(string $key, array $o = []): string
    {
        $decorative = (bool) ($o['decorative'] ?? false);
        $alt = $o['alt'] ?? null;

        if ($decorative) {
            $alt = '';
        } elseif ($alt === null || trim($alt) === '') {
            if (is_local()) {
                throw new RuntimeException("img('{$key}'): alt text is required (or pass decorative => true).");
            }
            $alt = '';
        }

        $entry = self::manifest()[$key] ?? null;
        if ($entry === null) {
            return self::placeholder($key, $o, $alt);
        }

        $priority = (bool) ($o['priority'] ?? false);
        $sizes = $o['sizes'] ?? '100vw';
        $class = isset($o['class']) ? ' class="' . e($o['class']) . '"' : '';
        $dir = str_contains($key, '/') ? substr($key, 0, strrpos($key, '/') + 1) : '';
        $base = basename($key);
        $widths = $entry['widths'];
        $largest = max($widths);

        $srcset = static function (string $ext) use ($dir, $base, $widths): string {
            return implode(', ', array_map(
                static fn (int $w): string => self::BASE . $dir . $base . '-' . $w . '.' . $ext . ' ' . $w . 'w',
                $widths
            ));
        };

        $imgAttrs = [
            'src'      => self::BASE . $dir . $base . '-' . $largest . '.jpg',
            'srcset'   => $srcset('jpg'),
            'sizes'    => $sizes,
            'width'    => (string) $entry['width'],
            'height'   => (string) $entry['height'],
            'alt'      => $alt,
            'decoding' => 'async',
        ];
        if ($priority) {
            $imgAttrs['fetchpriority'] = 'high';
        } else {
            $imgAttrs['loading'] = $o['loading'] ?? 'lazy';
        }
        foreach ($o['attrs'] ?? [] as $name => $value) {
            $imgAttrs[$name] = $value;
        }

        $attrString = '';
        foreach ($imgAttrs as $name => $value) {
            $attrString .= ' ' . e($name) . '="' . e($value) . '"';
        }

        return '<picture' . $class . '>'
            . '<source type="image/avif" srcset="' . e($srcset('avif')) . '" sizes="' . e($sizes) . '">'
            . '<source type="image/webp" srcset="' . e($srcset('webp')) . '" sizes="' . e($sizes) . '">'
            . '<img' . $attrString . '>'
            . '</picture>';
    }

    /**
     * URL of one rendition for CSS use (background-image fills such as the image-filled "10+" numerals):
     * the smallest JPEG at least $minWidth wide, or the largest one. Null when the image is not in the manifest,
     * so the caller can fall back to a gradient instead of a broken URL.
     */
    public static function src(string $key, int $minWidth = 800): ?string
    {
        $entry = self::manifest()[$key] ?? null;
        if ($entry === null) {
            return null;
        }
        $widths = $entry['widths'];
        sort($widths);
        $pick = max($widths);
        foreach ($widths as $w) {
            if ($w >= $minWidth) {
                $pick = $w;
                break;
            }
        }
        $dir = str_contains($key, '/') ? substr($key, 0, strrpos($key, '/') + 1) : '';
        return self::BASE . $dir . basename($key) . '-' . $pick . '.jpg';
    }

    /**
     * Path of an article crop written by scripts/images.mjs (P26): '16x9', '4x3', '1x1' (BlogPosting `image`) or 'og'
     * (1200×630 Open Graph). Null when the image has no such crop (not used by a post, or not generated yet).
     */
    public static function crop(string $key, string $name): ?string
    {
        $crops = self::manifest()[$key]['crops'] ?? null;
        if (!is_array($crops) || !isset($crops[$name])) {
            return null;
        }
        $dir = str_contains($key, '/') ? substr($key, 0, strrpos($key, '/') + 1) : '';
        $path = self::BASE . $dir . basename($key) . '-' . $name . '.jpg';
        return is_file(public_path(ltrim($path, '/'))) ? $path : null;
    }

    /**
     * Branded placeholder: navy→azure gradient, faint hexagon pattern and a `[[PENDING IMAGE: key]]`
     * label, at the requested aspect ratio (`ratio => '16/9'`, default 16/9). Colours via CSS variables only.
     */
    public static function placeholder(string $key, array $o, string $alt): string
    {
        [$rw, $rh] = self::ratio($o['ratio'] ?? null, $o['width'] ?? null, $o['height'] ?? null);
        $w = 1600;
        $h = (int) round($w * $rh / $rw);
        $id = 'ph' . substr(md5($key), 0, 8);
        $label = '[[PENDING IMAGE: ' . $key . ']]';
        $class = 'img-placeholder' . (isset($o['class']) ? ' ' . $o['class'] : '');
        $fontSize = max(28, (int) round($w / 45));

        $a11y = $alt === ''
            ? 'aria-hidden="true"'
            : 'role="img" aria-label="' . e($alt) . '"';
        // cover => true: the placeholder fills its box like object-fit: cover (real images use CSS for that)
        if (!empty($o['cover'])) {
            $a11y .= ' preserveAspectRatio="xMidYMid slice"';
        }

        // Hexagon tile for the pattern (pointy-top, 48×56 cell)
        $hex = 'M24 2 L46 15 L46 41 L24 54 L2 41 L2 15 Z';

        return '<svg class="' . e($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $w . ' ' . $h . '" '
            . 'width="' . $w . '" height="' . $h . '" ' . $a11y . ' data-pending-image="' . e($key) . '">'
            . '<defs>'
            . '<linearGradient id="' . $id . '-g" x1="0" y1="0" x2="1" y2="1">'
            . '<stop offset="0" style="stop-color:var(--navy-900)"/>'
            . '<stop offset="0.55" style="stop-color:var(--navy-700)"/>'
            . '<stop offset="1" style="stop-color:var(--azure-500)"/>'
            . '</linearGradient>'
            . '<pattern id="' . $id . '-p" width="48" height="56" patternUnits="userSpaceOnUse">'
            . '<path d="' . $hex . '" fill="none" style="stroke:var(--sky-300)" stroke-opacity="0.18" stroke-width="1.5"/>'
            . '</pattern>'
            . '</defs>'
            . '<rect width="100%" height="100%" fill="url(#' . $id . '-g)"/>'
            . (($o['pattern'] ?? true) ? '<rect width="100%" height="100%" fill="url(#' . $id . '-p)"/>' : '')
            . '<text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" '
            . 'font-family="ui-monospace, SFMono-Regular, Menlo, Consolas, monospace" font-size="' . $fontSize . '" '
            . 'style="fill:var(--sky-300)" fill-opacity="0.9">' . e($label) . '</text>'
            . '</svg>';
    }

    /** @return array{0:int,1:int} */
    private static function ratio(?string $ratio, ?int $width, ?int $height): array
    {
        if ($width && $height) {
            return [$width, $height];
        }
        if ($ratio !== null && preg_match('/^\s*(\d+)\s*[\/:]\s*(\d+)\s*$/', $ratio, $m) === 1 && (int) $m[2] > 0) {
            return [(int) $m[1], (int) $m[2]];
        }
        return [16, 9];
    }

    /** @return array<string, array{width:int,height:int,widths:int[],formats:string[]}> */
    private static function manifest(): array
    {
        if (self::$manifest === null) {
            $file = public_path('assets/img/manifest.json');
            self::$manifest = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
        }
        return self::$manifest;
    }
}
