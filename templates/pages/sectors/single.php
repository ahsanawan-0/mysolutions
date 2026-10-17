<?php
declare(strict_types=1);
/**
 * Sector page /sectors/<slug>/ (PROMPTS P22, docs/08 §8). One template for the seven sectors; the copy is
 * app/data/sector-pages/<slug>.php (sector_page()), the sector itself (name, icon, summary, photo, URL)
 * app/data/sectors.php.
 * Order: hero-inner `columns` (the sector photo + 2 narrow columns, labels; H1 "<Sector> Tender Writing") →
 * challenges (icon cards via the service `list` block) → how we help (text + the relevant sub-service cards) →
 * typical buyers / contract types (chips) → FAQ (faqs.php by `faq_tag`; omitted when nothing is tagged) → other sectors
 * → CTA band prefilled `?sector=<slug>#quote`. Data still holding a [[PENDING: …]] marker renders as the dashed
 * "Content pending" block off production and is omitted on production.
 * Schema: WebPage (+ `about` the relevant services) + BreadcrumbList (Home › Sectors › Sector).
 * @var array $params  ['slug' => sector slug] (app/routes.php)
 */
$slug = (string) $params['slug'];
$sector = sector($slug);
$data = sector_page($slug);
$seo = $data['seo'] ?? [];
$hero = $data['hero'] ?? [];
$ui = ui('sector_page');
$visible = static fn (array $item): bool => !is_production() || ($item['status'] ?? 'draft') === 'published';

// Relevant sub-services (slugs → ['sub', 'category']); drafts only off production
$services = [];
foreach ($data['services'] ?? [] as $subSlug) {
    $found = service_sub_find((string) $subSlug);
    if ($found !== null && $visible($found['sub'])) {
        $services[] = $found;
    }
}

$seoDescription = (string) ($seo['description'] ?? '[[PENDING: meta description]]');
$pendingDescription = pending_note($seoDescription) !== null;
$sectorPhoto = $sector['image'] ?? 'sectors/' . $slug;

$page = [
    'title'          => $seo['title'] ?? $sector['name'] . ' Tender Writing | My Solutionz',
    // A [[PENDING]] description stays visible off production (check-content, draft ribbon), never on production
    'description'    => $pendingDescription && is_production() ? $sector['summary'] : $seoDescription,
    'canonical'      => $sector['url'],
    'status'         => $data['status'] ?? 'draft',
    'og_title'       => $seo['og_title'] ?? $sector['name'] . ' Tender Writing',
    'primary_image'  => App\Image::src($sectorPhoto, 1200),
    'breadcrumbs'    => [['Home', '/'], ['Sectors', '/sectors/'], [$sector['name'], null]],
    'about'          => array_map(static fn (array $s): array => ['@id' => url($s['sub']['url']) . '#service'], $services),
    'body_class'     => 'page-sector page-sector--' . $slug,
    'quote'          => ['sector' => $slug],
    'header_theme'   => 'dark',
    'header_overlay' => true,
];

$images = [['key' => $sectorPhoto, 'alt' => $sector['image_alt'] ?? $sector['name']]];
foreach (array_slice($hero['images'] ?? [], 0, 2) as $i => $key) {
    $images[] = ['key' => $key, 'alt' => '', 'focus' => $i === 0 ? 'right' : null];
}

$challenges = $data['challenges'] ?? '[[PENDING: sector challenges]]';
$challengesBlock = [
    'type'    => 'challenges',
    'id'      => 'challenges',
    'heading' => $ui['challenges']['heading'],
    'accent'  => str_replace('{sector}', $sector['name'], $ui['challenges']['accent']),
    'items'   => $challenges,
];
$buyers = $data['buyers'] ?? '[[PENDING: typical buyers and contract types]]';
$others = array_values(array_filter(sectors(), static fn (array $s): bool => $s['slug'] !== $slug && $visible($s)));
$cta = $data['cta'] ?? [];
?>
<?= component('hero-inner', [
    'variant'     => 'columns',
    'breadcrumbs' => $page['breadcrumbs'],
    'eyebrow'     => $hero['eyebrow'] ?? 'Sectors We Serve',
    'title'       => $hero['title'] ?? $sector['name'],
    'accent'      => $hero['accent'] ?? null,
    'lead'        => $hero['lead'] ?? $sector['summary'],
    'ctas'        => $hero['ctas'] ?? [[ui('cta_band.primary.0'), quote_url($page['quote'])], ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone']],
    'images'      => $images,
    'labels'      => $hero['labels'] ?? [],
]) ?>

<?php if (($note = pending_note($challenges)) !== null): ?>
<?= is_production() ? '' : render('sections/service/pending', ['block' => $challengesBlock, 'note' => $note]) ?>
<?php else: ?>
<?= render('sections/service/list', ['block' => ['type' => 'list'] + $challengesBlock, 'ctx' => ['colour' => 'bid']]) ?>
<?php endif; ?>

<?= render('sections/sector/help', ['help' => $data['how_we_help'] ?? ['heading' => 'How We', 'accent' => 'Help'], 'items' => $services, 'sector' => $sector['name']]) ?>

<?php if (($note = pending_note($buyers)) !== null): ?>
<?= is_production() ? '' : render('sections/service/pending', ['block' => ['type' => 'buyers'] + $ui['buyers'], 'note' => $note]) ?>
<?php else: ?>
<?= render('sections/sector/buyers', ['items' => (array) $buyers]) ?>
<?php endif; ?>

<?= render('sections/service/faq', [
    'faq'   => $data['faq'] ?? ['eyebrow' => $ui['faq']['eyebrow'], 'heading' => str_replace('{sector}', $sector['name'], $ui['faq']['heading']), 'accent' => $ui['faq']['accent']],
    'items' => faqs($data['faq_tag'] ?? $slug),
    'id'    => 'faq',
]) ?>

<?= render('sections/sector/others', ['sectors' => $others]) ?>

<?= component('cta-band', [
    'heading'       => $cta['heading'] ?? null,
    'accent'        => $cta['accent'] ?? null,
    'sub'           => $cta['sub'] ?? null,
    'text'          => $cta['paras'] ?? null,
    'strong'        => $cta['strong'] ?? null,
    'primary'       => [$cta['label'] ?? ui('cta_band.primary.0'), quote_url($page['quote'])],
    'show_whatsapp' => true,
]) ?>
