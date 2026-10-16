<?php
declare(strict_types=1);
/**
 * Sectors hub /sectors/ (PROMPTS P22, docs/08 §8): hero-inner `columns` (H1 "Sectors We" + Serve) → the 7 sector cards
 * (C36, the P12 card in a static grid) → "Why sector knowledge matters" (client-approved Our Story paragraphs) → CTA.
 * Copy: app/data/sector-pages/_hub.php; sectors (name, icon, summary, photo, URL): app/data/sectors.php.
 * Schema: WebPage + BreadcrumbList + ItemList of the sectors.
 */
$hub = sector_page('_hub');
$seo = $hub['seo'];
$hero = $hub['hero'];
$cards = $hub['cards'];
$why = $hub['why'];
$sectors = sectors();

$page = [
    'title'          => $seo['title'],
    'description'    => $seo['description'],
    'canonical'      => '/sectors/',
    'status'         => $hub['status'] ?? 'draft',
    'og_title'       => $seo['og_title'] ?? null,
    'primary_image'  => App\Image::src($hero['images'][0]['key'], 1200),
    'breadcrumbs'    => [['Home', '/'], ['Sectors', null]],
    'schema'         => [[
        '@type'           => 'ItemList',
        '@id'             => url('/sectors/') . '#sectors',
        'name'            => 'Sectors My Solutionz serves',
        'itemListElement' => array_map(static fn (array $s, int $i): array => [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $s['name'],
            'url'      => url($s['url']),
        ], $sectors, array_keys($sectors)),
    ]],
    'body_class'     => 'page-sectors-hub',
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

<section class="sectors-hub" id="sectors" aria-labelledby="sectors-hub-title" data-header-theme="light">
  <div class="container">
    <header class="sectors-hub__head">
      <?= component('eyebrow', ['text' => $cards['eyebrow']]) ?>
      <?= heading(2, $cards['heading'], $cards['accent'], 'sectors-hub__title', 'sectors-hub-title', ['data-anim' => 'two-voice']) ?>
      <p class="sectors-hub__intro" data-anim="fade-up"><?= e($cards['intro']) ?></p>
    </header>
    <ul class="sectors-hub__grid" role="list" data-anim="stagger">
      <?php foreach ($sectors as $sector): ?>
      <li class="sectors-hub__item">
        <?= component('sector-card', ['sector' => $sector, 'sizes' => '(min-width: 1024px) 30vw, (min-width: 768px) 45vw, 100vw']) ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?= render('sections/service/text', ['block' => [
    'type'    => 'text',
    'id'      => 'why-sector-knowledge',
    'heading' => $why['heading'],
    'accent'  => $why['accent'],
    'paras'   => $why['paras'],
    'button'  => $why['button'],
    'variant' => 'mist',
]]) ?>

<?= component('cta-band', ['show_whatsapp' => true]) ?>
