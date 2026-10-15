<?php
declare(strict_types=1);
/**
 * C32 Inner-page hero (PROMPTS P17; docs/04 §3 "Sun Over" pill columns + floating labels, §6 glass-skyscraper wash;
 * docs/08 §5 A0, §7a–§7b; docs/05 §5 glowing frame reused). One flexible hero for every page except home.
 * The page sets header_overlay => true and header_theme => 'dark' (columns, category) or 'light' (simple, article);
 * the default glass header sits over the hero. Behaviour: src/js/modules/hero-inner.js (data-module="hero-inner":
 * entrance after `splash:done`, column/label parallax ≥ 1024); styles: src/css/components/hero-inner.css.
 *
 * Variants:
 *  - columns   Navy 900 panel with an 80px bottom-left radius; copy left; 3 pill image columns right with up to 3
 *              floating labels (About, hubs).
 *  - category  Same panel tinted by the category colour; a large hex icon tile inside the animated glowing frame,
 *              optional image columns; "Part of …" chip for sub-services (service categories and sub-services).
 *  - simple    Compact light hero on the canvas: breadcrumbs, H1, lead (legal, FAQ, utility pages).
 *  - article   Light: breadcrumbs, category chip, H1, lead, meta row, featured image (blog articles, P26).
 *
 * @var string      $variant      'columns' (default) | 'category' | 'simple' | 'article'
 * @var string      $title        H1, first voice
 * @var string|null $accent       H1 accent words (brand blue)
 * @var string|null $eyebrow
 * @var string|null $sub          Tagline under the H1 (the client's sub-headings, P20/P21)
 * @var string|null $lead
 * @var array|null  $ctas         [[label, url, variant?], …]; the first is the primary button
 * @var string|null $trust        Small trust line under the CTAs
 * @var array|null  $images       up to 3 × ['key' => …, 'alt' => …, 'focus' => 'left'|'right'?]; first = wide column
 *                                (LCP, priority); focus picks the side of the photo a narrow pill shows
 * @var array|null  $labels       up to 3 × ['text' => …, 'icon' => …] floating labels
 * @var string|null $icon         Phosphor icon in the framed hex tile (category)
 * @var string|null $icon_label   Caption under the framed hex tile (category)
 * @var string|null $colour       'bid' | 'compliance' | 'digital' (category colour: chip, glow, hex tile)
 * @var array|null  $parent       [label, url] → "Part of …" chip linking up (sub-services)
 * @var array|null  $breadcrumbs  [[label, path|null], …] (normally $page['breadcrumbs'])
 * @var array|null  $chip         [label, url|null] article category chip
 * @var array|null  $meta         [['icon' => …, 'text' => …, 'datetime' => …?], …] article meta row
 * @var array|null  $image        ['key' => …, 'alt' => …] article featured image (priority, never clipped)
 * @var string|null $id           H1 id (default 'page-title')
 * @var int|null    $level        Heading level of the title (default 1; only the styleguide demo lowers it)
 * @var string|null $class
 */
$variant = in_array($variant ?? 'columns', ['columns', 'category', 'simple', 'article'], true) ? ($variant ?? 'columns') : 'columns';
$dark = in_array($variant, ['columns', 'category'], true);
$id = $id ?? 'page-title';
$colour = in_array($colour ?? '', ['bid', 'compliance', 'digital'], true) ? $colour : null;
$images = array_slice($images ?? [], 0, 3);
$labels = array_slice($labels ?? [], 0, 3);
$ctas = $ctas ?? [];
$breadcrumbs = $breadcrumbs ?? [];
$speeds = ['0.9', '1', '1.1'];   // parallax per column / label (docs/08 §5 A0)

$classes = 'hero-inner hero-inner--' . $variant . ($dark ? ' on-dark' : '') . ($colour ? ' hero-inner--cat-' . $colour : '')
    . ($variant === 'category' && $images !== [] ? ' hero-inner--with-cols' : '') . (isset($class) ? ' ' . $class : '');
$href = static fn (string $u): string => preg_match('#^(https?:|tel:|mailto:|\#)#', $u) === 1 ? $u : url($u);

$columns = static function () use ($images, $speeds): string {
    if ($images === []) return '';
    $sizes = ['(min-width: 1024px) 24vw, (min-width: 768px) 46vw, 100vw', '(min-width: 1024px) 10vw, (min-width: 768px) 18vw, 1px', '(min-width: 1024px) 8vw, (min-width: 768px) 14vw, 1px'];
    $out = '<div class="hero-inner__cols">';
    foreach ($images as $i => $image) {
        $focus = in_array($image['focus'] ?? '', ['left', 'right'], true) ? ' hero-inner__col--focus-' . $image['focus'] : '';
        $out .= '<div class="hero-inner__col hero-inner__col--' . ($i + 1) . $focus . '" data-speed="' . $speeds[$i] . '">'
            . '<div class="hero-inner__col-media">'
            . img((string) $image['key'], [
                'alt' => (string) ($image['alt'] ?? ''),
                'decorative' => ($image['alt'] ?? '') === '',
                'sizes' => $sizes[$i],
                'priority' => $i === 0,
                'loading' => 'eager',
                'ratio' => $i === 0 ? '3/4' : '1/3',
            ])
            . '</div></div>';
    }
    return $out . '</div>';
};
?>
<section class="<?= e($classes) ?>" aria-labelledby="<?= e($id) ?>" data-header-theme="<?= $dark ? 'dark' : 'light' ?>" data-module="hero-inner">
  <div class="hero-inner__panel">
    <?php if ($dark): ?>
    <div class="hero-inner__bg" aria-hidden="true">
      <svg class="hero-inner__art" focusable="false" preserveAspectRatio="xMidYMax slice"><use href="<?= e(asset('img/art/skyscraper.svg')) ?>#skyscraper"></use></svg>
      <span class="hero-inner__glow"></span>
    </div>
    <?php endif; ?>

    <div class="container hero-inner__grid">
      <div class="hero-inner__copy">
        <?= component('breadcrumbs', ['items' => $breadcrumbs, 'variant' => $dark ? 'white' : 'ink', 'class' => 'hero-inner__crumbs']) ?>

        <?php if (!empty($parent)): ?>
        <a class="chip chip--<?= e($colour ?? 'bid') ?> hero-inner__parent" href="<?= e(url((string) $parent[1])) ?>">
          <span class="chip__dot" aria-hidden="true"></span>
          <span>Part of <?= e((string) $parent[0]) ?></span>
          <?= icon('arrow-up-right', 'regular', ['size' => 14]) ?>
        </a>
        <?php elseif ($variant === 'article' && !empty($chip)): ?>
        <?= component('chip', ['label' => (string) $chip[0], 'href' => $chip[1] ?? null, 'variant' => 'bid', 'dot' => true, 'class' => 'hero-inner__chip']) ?>
        <?php elseif (!empty($eyebrow)): ?>
        <?= $variant === 'category'
            ? component('chip', ['label' => $eyebrow, 'variant' => $colour ?? 'bid', 'dot' => true, 'class' => 'hero-inner__eyebrow'])
            : component('eyebrow', ['text' => $eyebrow, 'class' => 'hero-inner__eyebrow']) ?>
        <?php endif; ?>

        <?= heading((int) ($level ?? 1), $title, $accent ?? null, 'hero-inner__title', $id) ?>

        <?php if (!empty($sub)): ?>
        <p class="hero-inner__sub"><?= e($sub) ?></p>
        <?php endif; ?>
        <?php if (!empty($lead)): ?>
        <p class="hero-inner__lead lead"><?= e($lead) ?></p>
        <?php endif; ?>

        <?php if ($variant === 'article' && !empty($meta)): ?>
        <ul class="hero-inner__meta" role="list">
          <?php foreach ($meta as $item): ?>
          <li class="hero-inner__meta-item">
            <?php if (!empty($item['icon'])): ?><?= icon((string) $item['icon'], 'regular', ['size' => 16]) ?><?php endif; ?>
            <?php if (!empty($item['datetime'])): ?>
            <time datetime="<?= e((string) $item['datetime']) ?>"><?= e((string) $item['text']) ?></time>
            <?php else: ?>
            <span><?= e((string) $item['text']) ?></span>
            <?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if ($ctas !== [] && in_array($variant, ['columns', 'category'], true)): ?>
        <div class="hero-inner__ctas">
          <?php foreach ($ctas as $i => $cta): ?>
          <?= component('button', [
              'label'   => (string) $cta[0],
              'href'    => $href((string) $cta[1]),
              'variant' => $cta[2] ?? ($i === 0 ? 'primary' : 'glass'),
              'icon'    => $cta[3] ?? null,
          ]) ?>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($trust) && $dark): ?>
        <p class="hero-inner__trust"><?= icon('seal-check', 'light', ['size' => 18]) ?><span><?= e($trust) ?></span></p>
        <?php endif; ?>
      </div>

      <?php if ($variant === 'columns' || $variant === 'category'): ?>
      <div class="hero-inner__media"<?= $images !== [] ? ' data-parallax-scope' : '' ?>>
        <?= $columns() ?>

        <?php if ($variant === 'category' && !empty($icon)): ?>
        <div class="hero-inner__badge" data-speed="<?= $images !== [] ? '1.1' : '1' ?>">
          <span class="special-frame special-frame--hug" aria-hidden="true"></span>
          <div class="hero-inner__badge-card glass-thin-dark">
            <?= component('hex-tile', ['icon' => $icon, 'category' => $colour, 'class' => 'hero-inner__hex']) ?>
            <?php if (!empty($icon_label)): ?>
            <p class="hero-inner__badge-label"><?= e($icon_label) ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($labels !== []): ?>
        <ul class="hero-inner__labels" role="list">
          <?php foreach ($labels as $i => $label): ?>
          <li class="hero-inner__label-wrap hero-inner__label-wrap--<?= $i + 1 ?>" data-speed="<?= e($speeds[2 - $i]) ?>">
            <span class="hero-inner__label glass-thin-dark">
              <span class="hero-inner__label-icon" aria-hidden="true"><?= icon((string) ($label['icon'] ?? 'hexagon'), 'light', ['size' => 18]) ?></span>
              <span><?= e((string) $label['text']) ?></span>
            </span>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <?php if ($variant === 'article' && !empty($image)): ?>
      <figure class="hero-inner__figure">
        <?= img((string) $image['key'], [
            'alt' => (string) ($image['alt'] ?? ''),
            'sizes' => '(min-width: 1360px) 1280px, 94vw',
            'priority' => true,
            'ratio' => '16/9',
        ]) ?>
      </figure>
      <?php endif; ?>
    </div>
  </div>
</section>
