<?php
declare(strict_types=1);
/**
 * C22 Breadcrumbs. The BreadcrumbList JSON-LD is produced by App\Schema from the same $page['breadcrumbs'] (P03).
 * Usage: component('breadcrumbs', ['items' => $page['breadcrumbs'], 'variant' => 'white'])
 * @var array       $items    [[label, path|null], …]; the last item (or any with a null path) is the current page
 * @var string|null $variant  'ink' (default, light backgrounds) | 'white' (over dark/photo heroes)
 * @var string|null $class
 */
$items = $items ?? [];
$variant = $variant ?? 'ink';
$classes = 'breadcrumbs' . ($variant === 'white' ? ' breadcrumbs--white' : '') . (isset($class) ? ' ' . $class : '');
$last = count($items) - 1;
if ($items === []) {
    return;
}
?>
<nav class="<?= e($classes) ?>" aria-label="Breadcrumb">
  <ol class="breadcrumbs__list">
    <?php foreach ($items as $i => [$label, $path]): $current = $i === $last || $path === null; ?>
    <li class="breadcrumbs__item">
      <?php if ($current): ?>
      <span class="breadcrumbs__current" aria-current="page"><?= e($label) ?></span>
      <?php else: ?>
      <a class="breadcrumbs__link" href="<?= e(url($path)) ?>"><?= e($label) ?></a>
      <?= icon('caret-right', 'regular', ['size' => 14, 'class' => 'breadcrumbs__sep']) ?>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ol>
</nav>
