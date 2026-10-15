<?php
declare(strict_types=1);
/**
 * C34 In-page sub-nav (PROMPTS P18; About page, reusable on any long page): a glass pill bar of same-page anchor
 * links that sticks under the header (slides up with it when the header hides), horizontal-scroll chips on small
 * screens. src/js/modules/subnav.js adds the scrollspy: aria-current="true" on the section being read and a navy
 * pill that slides under it. Clicks use the P06 anchor rules (Lenis + header offset; html:has(.subnav) adds the
 * bar's height to scroll-padding-top). Without JS it is a plain list of anchor links.
 * @var array<string,string> $items  section id => label, in page order
 * @var string|null          $label  aria-label (default "On this page")
 * @var string|null          $class
 */
$items = $items ?? [];
if ($items === []) {
    return;
}
?>
<nav class="subnav<?= isset($class) ? ' ' . e($class) : '' ?>" aria-label="<?= e($label ?? 'On this page') ?>" data-module="subnav">
  <div class="container subnav__bar">
    <div class="subnav__track glass-regular-light">
      <ul class="subnav__list" role="list">
        <?php foreach ($items as $id => $text): ?>
        <li class="subnav__item"><a class="subnav__link" href="#<?= e($id) ?>"><?= e($text) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <span class="subnav__pill" aria-hidden="true"></span>
    </div>
  </div>
</nav>
