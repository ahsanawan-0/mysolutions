<?php
declare(strict_types=1);
/**
 * Base layout. Expects $page (normalised by App\Seo::prepare) and $content (string) from the Router.
 * @var array<string,mixed> $page
 * @var string $content
 */
$bodyClass = trim((string) ($page['body_class'] ?? ''));
$showDraftRibbon = !is_production() && ($page['status'] ?? 'draft') !== App\Page::PUBLISHED && ($page['canonical'] ?? false) !== false;
?>
<!doctype html>
<html lang="en-GB" class="no-js">
<head>
<?= App\Seo::head($page) ?>
</head>
<body<?= $bodyClass !== '' ? ' class="' . e($bodyClass) . '"' : '' ?>>
<a class="skip-link" href="#main">Skip to main content</a>
<?php include templates_path('partials/header'); ?>
<main id="main" tabindex="-1">
<?= $content ?>
</main>
<?php include templates_path('partials/footer'); ?>
<?php if ($showDraftRibbon) include templates_path('partials/draft-ribbon'); ?>
</body>
</html>
