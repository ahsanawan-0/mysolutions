<?php
declare(strict_types=1);
/**
 * Services hub /services/ (PROMPTS P20, docs/08 §6): hero-inner `columns` (C32) → the three category panels with the
 * two-voice intro (colour band, hex icon, summary, the sub-services as hex-tile links, "Explore" split button) →
 * "How we work" (the five approach steps from home('process.steps') as a compact strip) → sectors chip marquee →
 * FAQ (faqs.php items tagged `services`) → CTA band. Copy: app/data/service-pages/_hub.php; categories,
 * sub-services and URLs: app/data/services.php. Schema: WebPage + BreadcrumbList + ItemList of the categories.
 */
$hub = service_page('_hub');
$seo = $hub['seo'];
$hero = $hub['hero'];
$c = $hub['categories'];
$how = $hub['how'];
$categories = services();
$steps = home('process.steps', []);
$countLabel = static fn (int $n): string => str_replace('{n}', (string) $n, $n === 1 ? $c['count']['one'] : $c['count']['other']);

$page = [
    'title'          => $seo['title'],
    'description'    => $seo['description'],
    'canonical'      => '/services/',
    'status'         => $hub['status'] ?? 'draft',
    'og_title'       => $seo['og_title'] ?? null,
    'primary_image'  => App\Image::src($hero['images'][0]['key'], 1200),
    'breadcrumbs'    => [['Home', '/'], ['Services', null]],
    'schema'         => [[
        '@type'           => 'ItemList',
        '@id'             => url('/services/') . '#categories',
        'name'            => 'My Solutionz services',
        'itemListElement' => array_map(static fn (array $cat, int $i): array => [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $cat['name'],
            'url'      => url($cat['url']),
        ], $categories, array_keys($categories)),
    ]],
    'body_class'     => 'page-services-hub',
    'header_theme'   => 'dark',
    'header_overlay' => true,
];
?>
<?= component('hero-inner', [
    'variant'     => 'columns',
    'breadcrumbs' => $page['breadcrumbs'],
    'eyebrow'     => $hero['eyebrow'],
    'title'       => $hero['title'],
    'accent'      => $hero['accent'],
    'lead'        => $hero['lead'],
    'ctas'        => $hero['ctas'],
    'images'      => $hero['images'],
    'labels'      => $hero['labels'],
]) ?>

<section class="hub-cats" id="categories" aria-labelledby="categories-title" data-header-theme="light">
  <div class="container">
    <header class="hub-cats__head">
      <?= component('eyebrow', ['text' => $c['eyebrow']]) ?>
      <?= heading(2, $c['heading'], $c['accent'], 'hub-cats__title', 'categories-title', ['data-anim' => 'two-voice']) ?>
      <p class="hub-cats__intro" data-anim="fade-up"><?= e($c['intro']) ?></p>
    </header>

    <ul class="hub-cats__grid" role="list" data-anim="stagger">
      <?php foreach ($categories as $i => $cat): ?>
      <?php $titleId = 'hub-' . $cat['slug']; ?>
      <li class="hub-cats__item">
        <article class="hub-cat hub-cat--<?= e($cat['colour']) ?>" aria-labelledby="<?= e($titleId) ?>">
          <div class="hub-cat__band">
            <?= component('hex-tile', ['icon' => $cat['icon'], 'category' => $cat['colour'], 'size' => 'lg', 'class' => 'hub-cat__tile']) ?>
            <span class="hub-cat__num" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
            <?= component('chip', ['label' => $countLabel(count($cat['subs'])), 'variant' => 'outline', 'icon' => 'hexagon', 'class' => 'hub-cat__count']) ?>
          </div>
          <div class="hub-cat__body">
            <h3 class="hub-cat__title" id="<?= e($titleId) ?>"><a class="hub-cat__title-link" href="<?= e(url($cat['url'])) ?>"><?= e($cat['name']) ?></a></h3>
            <p class="hub-cat__summary"><?= e($cat['summary']) ?></p>
            <ul class="hub-cat__subs" role="list" aria-label="<?= e($cat['name']) ?> services">
              <?php foreach ($cat['subs'] as $sub): ?>
              <li>
                <a class="hub-cat__sub" href="<?= e(url($sub['url'])) ?>">
                  <?= component('hex-tile', ['icon' => $sub['icon'], 'category' => $cat['colour'], 'size' => 'sm']) ?>
                  <span class="hub-cat__sub-name"><?= e($sub['name']) ?></span>
                  <?= icon('arrow-right', 'regular', ['size' => 18, 'class' => 'hub-cat__sub-arrow']) ?>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
            <?= component('button', [
                'label'   => str_replace('{name}', $cat['name'], $c['explore']),
                'href'    => url($cat['url']),
                'variant' => 'split',
                'class'   => 'hub-cat__cta',
            ]) ?>
          </div>
        </article>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php if ($steps !== []): ?>
<section class="hub-how on-dark" id="how-we-work" aria-labelledby="how-we-work-title" data-header-theme="dark">
  <div class="container">
    <header class="hub-how__head">
      <div class="hub-how__heading">
        <?= component('eyebrow', ['text' => $how['eyebrow']]) ?>
        <?= heading(2, $how['heading'], $how['accent'], 'hub-how__title', 'how-we-work-title', ['data-anim' => 'two-voice']) ?>
      </div>
      <div class="hub-how__aside" data-anim="fade-up">
        <p class="hub-how__intro"><?= e($how['intro']) ?></p>
        <?= component('button', ['label' => $how['link']['label'], 'href' => url($how['link']['url']), 'variant' => 'link', 'class' => 'hub-how__link']) ?>
      </div>
    </header>
    <ol class="hub-how__steps" data-anim="stagger">
      <?php foreach ($steps as $i => $step): ?>
      <li class="hub-how__step">
        <span class="hub-how__n" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
        <span class="hub-how__icon" aria-hidden="true"><?= icon($step['icon'], 'light', ['size' => 24]) ?></span>
        <h3 class="hub-how__step-title"><?= e($step['title']) ?></h3>
        <p class="hub-how__text"><?= e($step['text']) ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
<?php endif; ?>

<section class="hub-sectors" aria-labelledby="hub-sectors-title" data-header-theme="light">
  <div class="container hub-sectors__head">
    <h2 class="hub-sectors__title" id="hub-sectors-title"><?= e($hub['sectors']['label']) ?></h2>
    <?= component('button', ['label' => $hub['sectors']['link']['label'], 'href' => url($hub['sectors']['link']['url']), 'variant' => 'link']) ?>
  </div>
  <div class="hub-sectors__marquee" id="hub-sectors" data-anim="marquee" data-speed="40" role="group" aria-labelledby="hub-sectors-title">
    <ul class="hub-sectors__track" role="list" data-marquee-track>
      <?php foreach (sectors() as $sector): ?>
      <li class="hub-sectors__item">
        <a class="chip chip--outline hub-sectors__chip" href="<?= e(url($sector['url'])) ?>"><?= icon($sector['icon'], 'light', ['size' => 20]) ?><span><?= e($sector['name']) ?></span></a>
      </li>
      <?php endforeach; ?>
    </ul>
    <button class="marquee__pause hub-sectors__pause" type="button" data-marquee-pause aria-pressed="false" hidden>
      <?= icon('pause', 'regular', ['size' => 18]) ?><?= icon('play', 'regular', ['size' => 18]) ?>
      <span class="visually-hidden" data-marquee-label>Pause the moving sectors</span>
    </button>
  </div>
</section>

<?= render('sections/service/faq', ['faq' => $hub['faq'], 'items' => faqs('services'), 'id' => 'faq']) ?>

<?= component('cta-band', ['show_whatsapp' => true]) ?>
