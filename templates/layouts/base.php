<?php
declare(strict_types=1);
/**
 * Base layout. Expects $page (array) and $content (string) from the Router.
 * Head meta is printed by partials/head-seo.php (full SEO engine arrives in P03).
 * @var array<string,mixed> $page
 * @var string $content
 */
$page += [
    'title'       => config('name') . ' | ' . config('tagline'),
    'description' => '',
    'robots'      => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'status'      => 'draft',
    'body_class'  => '',
];
// Draft pages are never indexable (docs/PROMPTS "structure now, content later" strategy)
if (($page['status'] ?? 'draft') !== 'published') {
    $page['robots'] = 'noindex, nofollow';
}
?>
<!doctype html>
<html lang="en-GB" class="no-js">
<head>
<?php include templates_path('partials/head-seo'); ?>
</head>
<body<?= $page['body_class'] !== '' ? ' class="' . e($page['body_class']) . '"' : '' ?>>
<a class="skip-link" href="#main">Skip to main content</a>
<?php include templates_path('partials/header'); ?>
<main id="main" tabindex="-1">
<?= $content ?>
</main>
<?php include templates_path('partials/footer'); ?>
</body>
</html>
