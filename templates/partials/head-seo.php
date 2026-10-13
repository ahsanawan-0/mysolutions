<?php
declare(strict_types=1);
/**
 * <head> contents. Stub: the full template from docs/10 §7 (canonical, OG/Twitter, icons, JSON-LD)
 * is built by P03 via App\Seo. Order: charset → viewport → title → description → robots → js class → assets.
 * @var array<string,mixed> $page
 */
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page['title']) ?></title>
<?php if ($page['description'] !== ''): ?>
<meta name="description" content="<?= e($page['description']) ?>">
<?php endif; ?>
<?php if (!empty($page['canonical'])): ?>
<link rel="canonical" href="<?= e($page['canonical']) ?>">
<?php endif; ?>
<meta name="robots" content="<?= e($page['robots']) ?>">
<script>document.documentElement.classList.replace('no-js','js')</script>
<?= vite('src/js/main.js') ?>
