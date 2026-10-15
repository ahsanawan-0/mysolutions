<?php
declare(strict_types=1);
/**
 * <head> contents in the docs/10 §7 order:
 * charset → viewport → title → description → canonical → robots → js class → consent-mode default →
 * preloads → CSS/JS → Open Graph → Twitter → icons → manifest → theme-color → RSS → JSON-LD.
 * Rendered by App\Seo::head(). Icon/manifest links print only when the files exist (created in P07).
 * @var array<string,mixed> $page   normalised page contract
 * @var string $jsonLd              JSON-LD graph (may be empty for error pages)
 */
$icons = [
    ['<link rel="icon" href="/favicon.ico" sizes="48x48">', 'favicon.ico'],
    ['<link rel="icon" type="image/png" sizes="192x192" href="/icon-192.png">', 'icon-192.png'],
    ['<link rel="icon" type="image/svg+xml" href="/icon.svg">', 'icon.svg'],
    ['<link rel="apple-touch-icon" href="/apple-touch-icon.png">', 'apple-touch-icon.png'],
    ['<link rel="manifest" href="/site.webmanifest">', 'site.webmanifest'],
];
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page['title']) ?></title>
<?php if ($page['description'] !== ''): ?>
<meta name="description" content="<?= e($page['description']) ?>">
<?php endif; ?>
<?php if ($page['canonical'] !== false): ?>
<link rel="canonical" href="<?= e($page['canonical']) ?>">
<?php endif; ?>
<meta name="robots" content="<?= e($page['robots']) ?>">
<script>document.documentElement.classList.replace('no-js','js')</script>
<!-- Consent Mode v2 default (denied) is injected here by P28 only if Google tags are used -->
<link rel="preload" href="/assets/fonts/figtree-var.woff2" as="font" type="font/woff2" crossorigin>
<?= vite('src/js/main.js') ?>
<?php if ($page['canonical'] !== false): ?>
<meta property="og:locale" content="en_GB">
<meta property="og:type" content="<?= e($page['og_type']) ?>">
<meta property="og:site_name" content="<?= e(config('name')) ?>">
<meta property="og:title" content="<?= e($page['og_title']) ?>">
<meta property="og:description" content="<?= e($page['og_description']) ?>">
<meta property="og:url" content="<?= e($page['canonical']) ?>">
<meta property="og:image" content="<?= e($page['og_image']) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= e($page['og_image_alt']) ?>">
<meta property="og:image:type" content="image/jpeg">
<?php if ($page['og_type'] === 'article'): ?>
<?php if ($page['published']): ?><meta property="article:published_time" content="<?= e($page['published']) ?>"><?php echo "\n"; endif; ?>
<?php if ($page['modified']): ?><meta property="article:modified_time" content="<?= e($page['modified']) ?>"><?php echo "\n"; endif; ?>
<?php if (!empty($page['article_author'])): ?><meta property="article:author" content="<?= e($page['article_author']) ?>"><?php echo "\n"; endif; ?>
<?php if (!empty($page['article_section'])): ?><meta property="article:section" content="<?= e($page['article_section']) ?>"><?php echo "\n"; endif; ?>
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image:alt" content="<?= e($page['og_image_alt']) ?>">
<?php endif; ?>
<?php foreach ($icons as [$tag, $file]): if (is_file(public_path($file))): ?>
<?= $tag ?>

<?php endif; endforeach; ?>
<meta name="theme-color" content="<?= e(config('seo.theme_color')) ?>">
<link rel="alternate" type="application/rss+xml" title="<?= e(config('name')) ?> Insights" href="<?= e(url('/insights/feed.xml')) ?>">
<?php if ($jsonLd !== ''): ?>
<script type="application/ld+json">
<?= $jsonLd ?>

</script>
<?php endif; ?>
