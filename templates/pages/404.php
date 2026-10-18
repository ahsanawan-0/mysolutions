<?php
declare(strict_types=1);
/**
 * Not found (PROMPTS P29, docs/08 §12, docs/07 §6, docs/10 §8): real 404 status (set by the Router), `noindex`, no
 * canonical, no structured data. hero-inner `simple` with the logo mark in its slot: src/js/modules/nf-mark.js
 * assembles it after the splash, then the navy bar falls over; a click, Enter or Space puts it back (and knocks it
 * over again). Under reduced motion (or without JS) the mark is a static picture. Then the requested path, the
 * helpful links (sections/error/links: Home, Services, the 3 categories, Sectors, Contact) and the phone line.
 * Copy: ui('errors.not_found').
 */
$t = ui('errors.not_found');
$page = [
    'title'          => $t['seo_title'],
    'description'    => $t['description'],
    'canonical'      => false,
    'robots'         => 'noindex, follow',
    'status'         => 'published',
    'breadcrumbs'    => [['Home', '/'], [$t['crumb'], null]],
    'body_class'     => 'page-404 page-error',
    'header_theme'   => 'light',
    'header_overlay' => true,
];
$path = mb_strimwidth(current_path(), 0, 80, '…');
$mark = '<div class="nf-mark" data-module="nf-mark" aria-hidden="true" data-label-knock="' . e($t['knock']) . '" data-label-rebuild="' . e($t['rebuild']) . '">'
    . inline_svg('brand/logo-mark.svg', ['prefix' => 'nf', 'class' => 'nf-mark__svg', 'title' => false])
    . '</div>';
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => $page['breadcrumbs'],
    'slot'        => $mark,
    'title'       => $t['title'],
    'accent'      => $t['accent'],
    'lead'        => $t['lead'],
    'class'       => 'hero-inner--error',
]) ?>

<div class="container nf-path"><p><?= strtr(e($t['path']), ['{path}' => '<code>' . e($path) . '</code>']) ?></p></div>

<?= render('sections/error/links') ?>
