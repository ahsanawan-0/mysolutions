<?php
declare(strict_types=1);
/**
 * Service category landing page /services/<category>/ (PROMPTS P20, docs/08 §7a 🔒). One template for the three
 * categories; the copy is app/data/service-pages/<category>.php (service_page()), the category itself (name, colour,
 * icon, sub-services, URLs) app/data/services.php.
 * Order: hero-inner `category` (C32) → the data's ordered `sections` (typed blocks: templates/sections/service/
 * <type>.php, shared with the sub-service pages in P21) → FAQ (faqs.php by `faq_tag`) → CTA band (the client's
 * closing copy, prefilled quote link). A block whose data still holds a [[PENDING: …]] marker renders as a dashed
 * "Content pending" block off production and is omitted on production (draft categories 2–3).
 * Schema: WebPage + BreadcrumbList (Home › Services › Category) + Service with hasOfferCatalog (the sub-services).
 * @var array $params  ['category' => slug] (app/routes.php)
 */
$slug = (string) $params['category'];
$category = service_category($slug);
$data = service_page($slug);
$seo = $data['seo'];
$hero = $data['hero'];

// Photographic OG crop when scripts/og.mjs has written it, else the default brand card (config seo.og_default)
$ogImage = !empty($seo['og_image']) && is_file(public_path(ltrim((string) $seo['og_image'], '/'))) ? $seo['og_image'] : null;
$pendingDescription = pending_note($seo['description']) !== null;
$description = $pendingDescription ? $category['summary'] : $seo['description'];   // schema + production fallback
$taglines = [];
foreach ($data['sections'] as $block) {
    if (($block['type'] ?? '') === 'subs') {
        $taglines = $block['taglines'] ?? [];
    }
}

$page = [
    'title'          => $seo['title'],
    // A [[PENDING]] description stays visible off production (check-content and the draft ribbon list it) and
    // never reaches production HTML: there the category summary stands in.
    'description'    => $pendingDescription && is_production() ? $description : $seo['description'],
    'canonical'      => $category['url'],
    'status'         => $data['status'] ?? 'draft',
    'og_title'       => $seo['og_title'] ?? null,
    'og_description' => $seo['og_description'] ?? null,
    'og_image'       => $ogImage,
    'og_image_alt'   => $ogImage ? ($seo['og_image_alt'] ?? null) : null,
    'primary_image'  => $ogImage ?? App\Image::src($hero['images'][0]['key'] ?? '', 1200),
    'breadcrumbs'    => [['Home', '/'], ['Services', '/services/'], [$category['name'], null]],
    'schema'         => [App\Schema::serviceCategory([
        'name'        => $category['name'],
        'description' => $description,
        'url'         => $category['url'],
        'serviceType' => $seo['service_type'] ?? $category['name'],
        'subs'        => array_map(static fn (array $sub): array => [
            'name'        => $sub['name'],
            'url'         => $sub['url'],
            'description' => $taglines[$sub['slug']] ?? $sub['summary'],
        ], $category['subs']),
    ])],
    'body_class'     => 'page-service page-service-category page-service--' . $category['colour'],
    'header_theme'   => 'dark',
    'header_overlay' => true,
];

$cta = $data['cta'] ?? [];
?>
<?= component('hero-inner', [
    'variant'     => 'category',
    'colour'      => $category['colour'],
    'breadcrumbs' => $page['breadcrumbs'],
    'eyebrow'     => $hero['eyebrow'] ?? 'Our Services',
    'title'       => $hero['title'],
    'accent'      => $hero['accent'] ?? null,
    'sub'         => $hero['sub'] ?? null,
    'lead'        => $hero['lead'] ?? null,
    'ctas'        => $hero['ctas'] ?? [],
    'trust'       => $hero['trust'] ?? null,
    'images'      => $hero['images'] ?? [],
    'labels'      => $hero['labels'] ?? [],
    'icon'        => $hero['icon'] ?? $category['icon'],
    'icon_label'  => $category['name'],
]) ?>

<?php foreach ($data['sections'] as $block): ?>
<?php $note = pending_note($block); ?>
<?php if ($note !== null): ?>
<?= is_production() ? '' : render('sections/service/pending', ['block' => $block, 'note' => $note]) ?>
<?php else: ?>
<?= render('sections/service/' . basename((string) $block['type']), ['block' => $block, 'ctx' => $category]) ?>
<?php endif; ?>
<?php endforeach; ?>

<?= render('sections/service/faq', ['faq' => $data['faq'] ?? [], 'items' => faqs($data['faq_tag'] ?? $slug), 'id' => 'faq']) ?>

<?= component('cta-band', [
    'heading'       => $cta['heading'] ?? null,
    'accent'        => $cta['accent'] ?? null,
    'sub'           => $cta['sub'] ?? null,
    'text'          => $cta['paras'] ?? null,
    'strong'        => $cta['strong'] ?? null,
    'primary'       => [$cta['label'] ?? ui('cta_band.primary.0'), '/contact-us/?service=' . $slug . '#quote'],
    'show_whatsapp' => true,
]) ?>
