<?php
declare(strict_types=1);
/**
 * Pagination (PROMPTS P25, docs/10 §8): real <a href> links that work without JS; every page is its own canonical URL.
 * Page 1 is `$base`, page n is `$base . 'page/<n>/'`. Previous / Next (hidden from assistive technology when there is
 * no such page), numbered links labelled "Page n", the current page aria-current="page", a gap (…) when there are
 * more than 7 pages. Prints nothing for a single page. Copy: insights('pagination') unless $copy is given.
 * @var int         $page
 * @var int         $pages
 * @var string      $base    e.g. '/insights/' or '/insights/category/compliance/'
 * @var array|null  $copy    ['label', 'previous', 'next', 'page' => 'Page {n}', 'previous_label', 'next_label']
 * @var string|null $class
 */
$pages = max(1, (int) $pages);
$page = min(max(1, (int) $page), $pages);
if ($pages < 2) {
    return;
}
$copy = ($copy ?? []) + (array) insights('pagination');
$href = static fn (int $n): string => url($n === 1 ? $base : rtrim($base, '/') . '/page/' . $n . '/');

// 1 … (current − 1) current (current + 1) … last, or every page when there are 7 or fewer
$numbers = [];
foreach (range(1, $pages) as $n) {
    if ($pages <= 7 || $n === 1 || $n === $pages || abs($n - $page) <= 1 || ($page <= 3 && $n <= 4) || ($page >= $pages - 2 && $n >= $pages - 3)) {
        $numbers[] = $n;
    }
}
?>
<nav class="pagination<?= isset($class) ? ' ' . e($class) : '' ?>" aria-label="<?= e($copy['label']) ?>">
  <ul class="pagination__list" role="list">
    <li class="pagination__item pagination__item--prev">
      <?php if ($page > 1): ?>
      <a class="pagination__step" href="<?= e($href($page - 1)) ?>" rel="prev" aria-label="<?= e($copy['previous_label']) ?>"><?= icon('arrow-left', 'regular', ['size' => 18]) ?><span><?= e($copy['previous']) ?></span></a>
      <?php else: ?>
      <span class="pagination__step is-disabled" aria-hidden="true"><?= icon('arrow-left', 'regular', ['size' => 18]) ?><span><?= e($copy['previous']) ?></span></span>
      <?php endif; ?>
    </li>
    <?php $last = 0; foreach ($numbers as $n): ?>
    <?php if ($n - $last > 1): ?>
    <li class="pagination__item"><span class="pagination__gap" aria-hidden="true">…</span></li>
    <?php endif; $last = $n; ?>
    <li class="pagination__item">
      <a class="pagination__num" href="<?= e($href($n)) ?>" aria-label="<?= e(str_replace('{n}', (string) $n, $copy['page'])) ?>"<?= $n === $page ? ' aria-current="page"' : '' ?>><?= $n ?></a>
    </li>
    <?php endforeach; ?>
    <li class="pagination__item pagination__item--next">
      <?php if ($page < $pages): ?>
      <a class="pagination__step" href="<?= e($href($page + 1)) ?>" rel="next" aria-label="<?= e($copy['next_label']) ?>"><span><?= e($copy['next']) ?></span><?= icon('arrow-right', 'regular', ['size' => 18]) ?></a>
      <?php else: ?>
      <span class="pagination__step is-disabled" aria-hidden="true"><span><?= e($copy['next']) ?></span><?= icon('arrow-right', 'regular', ['size' => 18]) ?></span>
      <?php endif; ?>
    </li>
  </ul>
</nav>
