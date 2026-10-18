<?php
declare(strict_types=1);
/**
 * Insights listing body (PROMPTS P25, docs/08 §9), shared by /insights/ (+ /page/<n>/) and /insights/category/<slug>/
 * (+ /page/<n>/): category chips → featured card (page 1) → grid of C10 notched cards → pagination → empty state.
 * The chips are real links, so every filter state is a crawlable URL; with JS, src/js/modules/insights-filter.js
 * fetches the chip's page, swaps `[data-insights-results]` (and the hero copy) in place with a GSAP Flip and pushes
 * the URL. Categories with no visible posts are left out of the chips (except the current one).
 * Copy: insights('filter' | 'featured' | 'more' | 'empty'); categories: content/categories.php.
 * @var array       $list     App\Blog::paginate() result: featured, items, page, pages, total
 * @var string|null $current  The active category slug (null = all insights)
 * @var string      $base     Path of page 1 of this listing (the pagination base)
 */
use App\Blog;

$f = insights('filter');
$empty = insights('empty');
$categories = array_filter(Blog::categories(), static fn (array $c): bool => $c['count'] > 0 || $c['slug'] === $current);
$plural = static fn (int $n): string => $n === 1 ? $f['one'] : $f['many'];
$summary = $current === null
    ? strtr($f['status_all'], ['{n}' => (string) $list['total'], '{insights}' => $plural($list['total'])])
    : strtr($f['status'], ['{n}' => (string) $list['total'], '{insights}' => $plural($list['total']), '{category}' => (string) (Blog::category($current)['name'] ?? '')]);
$isFirst = $list['page'] === 1;
$chip = static function (string $label, string $path, bool $active, ?int $count = null) use ($isFirst, $f, $plural): string {
    return '<li class="insights-filter__item"><a class="insights-filter__chip" href="' . e(url($path)) . '"'
        . ($active ? ' aria-current="' . ($isFirst ? 'page' : 'true') . '"' : '') . ' data-filter-link>'
        . '<span>' . e($label) . '</span>'
        . ($count !== null ? '<span class="insights-filter__count"><span class="visually-hidden">, </span>' . $count . '<span class="visually-hidden"> ' . e($plural($count)) . '</span></span>' : '')
        . '</a></li>';
};
?>
<section class="insights-list" data-header-theme="light" data-module="insights-filter">
  <div class="container">
    <?php if (count($categories) > 0): ?>
    <nav class="insights-filter" aria-label="<?= e($f['label']) ?>">
      <ul class="insights-filter__list" role="list">
        <?= $chip($f['all'], '/insights/', $current === null) ?>
        <?php foreach ($categories as $category): ?>
        <?= $chip((string) $category['name'], (string) $category['url'], $current === $category['slug'], (int) $category['count']) ?>
        <?php endforeach; ?>
      </ul>
    </nav>
    <?php endif; ?>
    <p class="visually-hidden" role="status" data-filter-status></p>

    <div class="insights-results" data-insights-results data-summary="<?= e($summary) ?>">
      <?php if ($list['featured'] !== null): ?>
      <h2 class="visually-hidden"><?= e(insights('featured')) ?></h2>
      <div class="insights-results__featured" data-anim="fade-up">
        <?= component('card-blog', ['post' => $list['featured'], 'variant' => 'featured', 'priority' => true]) ?>
      </div>
      <?php endif; ?>

      <?php if ($list['items'] !== []): ?>
      <h2 class="visually-hidden"><?= e(insights('more')) ?></h2>
      <ul class="insights-results__grid" role="list" data-anim="stagger" data-stagger="0.08">
        <?php foreach ($list['items'] as $post): ?>
        <li class="insights-results__item"><?= component('card-blog', ['post' => $post]) ?></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <?= component('pagination', ['page' => $list['page'], 'pages' => $list['pages'], 'base' => $base, 'class' => 'insights-results__pages']) ?>

      <?php if ($list['total'] === 0): ?>
      <div class="insights-empty">
        <?= component('hex-tile', ['icon' => 'linkedin-logo', 'size' => 'lg']) ?>
        <?= component('eyebrow', ['text' => $empty['eyebrow']]) ?>
        <?= $current === null
            ? heading(2, $empty['title'], $empty['accent'], 'insights-empty__title')
            : heading(2, $empty['category_title'], $empty['category_accent'], 'insights-empty__title') ?>
        <p class="insights-empty__text"><?= e($current === null ? $empty['text'] : $empty['category_text']) ?></p>
        <div class="insights-empty__actions">
          <?php if ($current !== null): ?>
          <?= component('button', ['label' => $empty['all'], 'href' => url('/insights/'), 'icon' => 'arrow-right']) ?>
          <?php endif; ?>
          <?= component('button', [
              'label'   => $empty['button'],
              'href'    => (string) config('socials.linkedin'),
              'variant' => $current === null ? 'primary' : 'secondary',
              'icon'    => 'linkedin-logo',
              'attrs'   => ['rel' => 'noopener'],
          ]) ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
