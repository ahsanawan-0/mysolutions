<?php
declare(strict_types=1);
/**
 * Base layout. Expects $page (normalised by App\Seo::prepare) and $content (string) from the Router.
 * Page keys used here: body_class, header ('default' | 'hero' | 'none'; 'hero' means the hero template renders the
 * header itself via render('partials/header', ['variant' => 'hero'])), header_theme ('light' | 'dark': what sits
 * under the header at the top of the page), header_overlay (true when a hero sits under the fixed header).
 * @var array<string,mixed> $page
 * @var string $content
 */
$headerVariant = $page['header'] ?? 'default';
$headerTheme = $page['header_theme'] ?? 'light';
$overlay = (bool) ($page['header_overlay'] ?? false);
$bodyClass = trim(($page['body_class'] ?? '') . ($overlay ? ' has-overlay-header' : ''));
// DRAFT_RIBBON=false hides it on a client-facing preview (Vercel); the pages stay noindex there either way
$showDraftRibbon = !is_production() && filter_var(env('DRAFT_RIBBON', 'true'), FILTER_VALIDATE_BOOLEAN)
    && ($page['status'] ?? 'draft') !== App\Page::PUBLISHED && ($page['canonical'] ?? false) !== false;
?>
<!doctype html>
<html lang="en-GB" class="no-js">
<head>
<?= App\Seo::head($page) ?>
</head>
<body<?= $bodyClass !== '' ? ' class="' . e($bodyClass) . '"' : '' ?>>
<a class="skip-link" href="#main">Skip to main content</a>
<?php include templates_path('partials/splash'); ?>
<?php if ($headerVariant === 'default'): ?>
<?= render('partials/header', ['variant' => 'default', 'theme' => $headerTheme]) ?>
<?php elseif ($headerVariant === 'hero'): ?>
<?= render('partials/header', ['variant' => 'default', 'theme' => $headerTheme, 'follow' => true]) ?>
<?php endif; ?>
<main id="main" tabindex="-1">
<?= $content ?>
</main>
<?php include templates_path('partials/footer'); ?>
<?= component('back-to-top') ?>
<?php if ($showDraftRibbon) include templates_path('partials/draft-ribbon'); ?>
</body>
</html>
