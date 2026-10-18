<?php
declare(strict_types=1);
/**
 * Gone (PROMPTS P29, docs/10 §2 + §8): served with status 410 for old WordPress URLs removed on purpose. Styled like
 * the 404: hero-inner `simple` with the logo mark, its navy bar already lying down (static), then the helpful links.
 * `noindex`, no canonical. Copy: ui('errors.gone').
 */
$t = ui('errors.gone');
$page = [
    'title'          => $t['seo_title'],
    'description'    => $t['description'],
    'canonical'      => false,
    'robots'         => 'noindex, follow',
    'status'         => 'published',
    'breadcrumbs'    => [['Home', '/'], [$t['crumb'], null]],
    'body_class'     => 'page-410 page-error',
    'header_theme'   => 'light',
    'header_overlay' => true,
];
$mark = '<div class="nf-mark nf-mark--fallen" aria-hidden="true">'
    . inline_svg('brand/logo-mark.svg', ['prefix' => 'gone', 'class' => 'nf-mark__svg', 'title' => false])
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

<?= render('sections/error/links') ?>
