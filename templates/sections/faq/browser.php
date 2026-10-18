<?php
declare(strict_types=1);
/**
 * The /faq/ browser (PROMPTS P27): a sticky tools column (search form, result count, topic chips) beside the grouped
 * C17 accordions. Every question is always in the HTML; a server-side search (?q=) marks the others `hidden`, and
 * src/js/modules/faq-search.js (data-module="faq-search") re-filters as you type: it hides non-matching questions and
 * empty topics (chips too), updates the chip counts, highlights the matched words in the questions, announces the
 * count in the role="status" line and keeps ?q= in the URL (replaceState). Esc in the field clears it.
 * @var array<string,string> $groups  slug → label (faq_groups())
 * @var array  $items  every FAQ item shown on /faq/
 * @var array  $shown  the items matching $query (all of them when it is empty)
 * @var string $query
 */
$copy = faq_page('status');
$shownIds = array_flip(array_column($shown, 'id'));
$byGroup = [];
foreach ($groups as $slug => $label) {
    $byGroup[$slug] = array_values(array_filter($items, static fn (array $f): bool => $f['group'] === $slug));
}
$byGroup = array_filter($byGroup);
$count = static fn (string $slug): int => count(array_filter($byGroup[$slug], static fn (array $f): bool => isset($shownIds[$f['id']])));
$n = count($shown);
$status = $query === ''
    ? strtr($copy['all'], ['{n}' => (string) count($items), '{groups}' => (string) count($byGroup)])
    : ($n === 0
        ? strtr($copy['none'], ['{q}' => $query])
        : strtr($copy['match'], ['{n}' => (string) $n, '{questions}' => $n === 1 ? $copy['one'] : $copy['many'], '{q}' => $query]));
$search = faq_page('search');
$empty = faq_page('empty');
?>
<section class="faq-browser" aria-label="<?= e(faq_page('hero.title') . ' ' . faq_page('hero.accent')) ?>" data-module="faq-search"
  data-status-all="<?= e($copy['all']) ?>" data-status-match="<?= e($copy['match']) ?>" data-status-none="<?= e($copy['none']) ?>"
  data-status-one="<?= e($copy['one']) ?>" data-status-many="<?= e($copy['many']) ?>">
  <div class="container faq-browser__layout">
    <div class="faq-browser__tools">
      <form class="faq-search" role="search" action="<?= e(url('/faq/')) ?>" method="get" data-faq-form>
        <label class="faq-search__label" for="faq-q"><?= e($search['label']) ?></label>
        <div class="faq-search__field">
          <?= icon('magnifying-glass', 'regular', ['size' => 20, 'class' => 'faq-search__icon']) ?>
          <input class="faq-search__input" id="faq-q" type="search" name="q" value="<?= e($query) ?>" maxlength="80"
            autocomplete="off" spellcheck="false" placeholder="<?= e($search['placeholder']) ?>" aria-describedby="faq-status" data-faq-input>
          <button class="faq-search__submit" type="submit"><?= e($search['submit']) ?></button>
        </div>
      </form>
      <p class="faq-search__status" id="faq-status" role="status" data-faq-status><?= e($status) ?></p>

      <nav class="faq-topics" aria-label="<?= e(faq_page('topics')) ?>">
        <ul class="faq-topics__list" role="list">
          <?php foreach ($byGroup as $slug => $list): $c = $count($slug); ?>
          <li<?= $c === 0 ? ' hidden' : '' ?> data-faq-chip="<?= e($slug) ?>">
            <a class="faq-topics__chip" href="#topic-<?= e($slug) ?>">
              <span><?= e($groups[$slug]) ?></span>
              <span class="faq-topics__count" data-faq-count><?= $c ?></span>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </nav>
    </div>

    <div class="faq-browser__groups">
      <?php foreach ($byGroup as $slug => $list): ?>
      <section class="faq-group" id="topic-<?= e($slug) ?>" aria-labelledby="topic-<?= e($slug) ?>-title" data-faq-group="<?= e($slug) ?>"<?= $count($slug) === 0 ? ' hidden' : '' ?>>
        <h2 class="faq-group__title" id="topic-<?= e($slug) ?>-title"><?= e($groups[$slug]) ?></h2>
        <?= component('accordion', [
            'items'     => array_map(static fn (array $f): array => $f + ['hidden' => !isset($shownIds[$f['id']])], $list),
            'level'     => 3,
            'id_prefix' => 'faq',
            'class'     => 'faq-group__list',
        ]) ?>
      </section>
      <?php endforeach; ?>

      <div class="faq-empty" data-faq-empty<?= $n > 0 ? ' hidden' : '' ?>>
        <?= component('hex-tile', ['icon' => 'magnifying-glass', 'size' => 'lg']) ?>
        <h2 class="faq-empty__title"><?= e($empty['title']) ?></h2>
        <p class="faq-empty__text"><?= e($empty['text']) ?></p>
        <div class="faq-empty__actions">
          <?= component('button', ['label' => $empty['all'], 'href' => url('/faq/'), 'variant' => 'secondary', 'attrs' => ['data-faq-clear' => '']]) ?>
          <?= component('button', ['label' => $empty['contact'], 'href' => url('/contact-us/#quote'), 'icon' => 'arrow-right']) ?>
        </div>
      </div>
    </div>
  </div>
</section>
