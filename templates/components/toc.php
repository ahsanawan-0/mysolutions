<?php
declare(strict_types=1);
/**
 * C41 Table of contents (PROMPTS P26, docs/08 §9): the article's H2s with their H3s nested, as plain anchor links in a
 * `<details>` that is open in the HTML, so without JS it is a visible list everywhere. src/js/modules/toc.js
 * (data-module="toc"): scrollspy (the last heading above 30 % of the viewport marks its link aria-current="true": an
 * azure bar and ink text), collapsed by default below 1100px and closed again after a link is followed there.
 * Desktop (≥ 1100px): the page makes it sticky beside the body (pages/article.css); smaller: a collapsible panel.
 * Prints nothing with fewer than 2 entries.
 * @var array       $items  [['level' => 2|3, 'id' => …, 'text' => …], …] (App\Markdown `toc`)
 * @var string|null $title  Summary text (default insights('article.toc'))
 * @var string|null $label  <nav> label (default insights('article.toc_label'))
 * @var string|null $class
 */
$items = array_values(array_filter($items ?? [], static fn (array $i): bool => in_array((int) ($i['level'] ?? 0), [2, 3], true) && ($i['id'] ?? '') !== ''));
if (count($items) < 2) {
    return;
}
// H3s nest under the H2 before them (an H3 before any H2 stays top level)
$tree = [];
foreach ($items as $item) {
    if ((int) $item['level'] === 3 && $tree !== []) {
        $tree[count($tree) - 1]['children'][] = $item;
    } else {
        $tree[] = $item + ['children' => []];
    }
}
$link = static fn (array $i): string => '<a class="toc__link" href="#' . e(rawurlencode((string) $i['id'])) . '">' . e((string) $i['text']) . '</a>';
?>
<nav class="toc<?= isset($class) ? ' ' . e($class) : '' ?>" aria-label="<?= e($label ?? (string) insights('article.toc_label')) ?>" data-module="toc">
  <details class="toc__details" open>
    <summary class="toc__summary">
      <span class="toc__title"><?= icon('list-bullets', 'regular', ['size' => 20]) ?><span><?= e($title ?? (string) insights('article.toc')) ?></span></span>
      <?= icon('caret-down', 'regular', ['size' => 18, 'class' => 'toc__caret']) ?>
    </summary>
    <div class="toc__panel">
      <ol class="toc__list">
        <?php foreach ($tree as $item): ?>
        <li class="toc__item"><?= $link($item) ?>
          <?php if ($item['children'] !== []): ?>
          <ol class="toc__list toc__list--sub">
            <?php foreach ($item['children'] as $child): ?>
            <li class="toc__item toc__item--sub"><?= $link($child) ?></li>
            <?php endforeach; ?>
          </ol>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </details>
</nav>
