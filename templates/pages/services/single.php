<?php
declare(strict_types=1);
/**
 * Sub-service landing page /services/<category>/<sub>/ (PROMPTS P21, docs/08 §7b). One template for all 12
 * sub-services; the copy is app/data/service-pages/<category>/<sub>.php (service_page('category/sub')), the service
 * itself (name, icon, summary, status, URLs) app/data/services.php.
 * Order: hero-inner `category` (C32: "Part of <Category>" chip, the sub-service icon in the glowing frame, the client's
 * tagline as the sub line) → the data's ordered `sections` (P20 typed blocks: intro, text, list, steps, why; a block
 * holding a [[PENDING: …]] marker is a dashed "Content pending" block off production and omitted on production) →
 * related services (`related` = 'category/sub' paths, default the siblings + the first service of the next category;
 * drafts are skipped on production) → FAQ (faqs.php by `faq_tag`; omitted when nothing is tagged) → CTA band (the
 * page's closing copy, prefilled `?service=<sub>#quote`).
 * Schema: WebPage + BreadcrumbList (Home › Services › Category › Sub-service) + Service (isRelatedTo the category).
 * @var array $params  ['category' => slug, 'sub' => slug] (app/routes.php)
 */
$catSlug = (string) $params['category'];
$subSlug = (string) $params['sub'];
$category = service_category($catSlug);
$sub = service_sub($catSlug, $subSlug);
$data = service_page($catSlug . '/' . $subSlug);
$seo = $data['seo'] ?? [];
$hero = $data['hero'] ?? [];
$quoteUrl = quote_url(['service' => $subSlug]);

// Photographic OG crop when scripts/og.mjs has written it, else the default brand card
$ogImage = !empty($seo['og_image']) && is_file(public_path(ltrim((string) $seo['og_image'], '/'))) ? $seo['og_image'] : null;
$seoDescription = (string) ($seo['description'] ?? '[[PENDING: meta description]]');
$pendingDescription = pending_note($seoDescription) !== null;
$description = $pendingDescription ? $sub['summary'] : $seoDescription;   // schema + production fallback

// Related services: 'category/sub' paths → cards (never the page itself; drafts only off production)
$all = services();
$nextCategory = $all[(array_search($catSlug, array_column($all, 'slug'), true) + 1) % count($all)];
$relatedPaths = $data['related'] ?? array_merge(
    array_map(static fn (array $s): string => $catSlug . '/' . $s['slug'], $category['subs']),
    [$nextCategory['slug'] . '/' . $nextCategory['subs'][0]['slug']]
);
$related = [];
foreach ($relatedPaths as $path) {
    [$rc, $rs] = array_pad(explode('/', (string) $path, 2), 2, '');
    $relSub = service_sub($rc, $rs);
    if ($relSub === null || ($rc === $catSlug && $rs === $subSlug) || (is_production() && ($relSub['status'] ?? 'draft') !== 'published')) {
        continue;
    }
    $related[] = ['sub' => $relSub, 'category' => service_category($rc), 'cross' => $rc !== $catSlug];
}

$page = [
    'title'          => $seo['title'] ?? $sub['name'] . ' Services UK | My Solutionz',
    // A [[PENDING]] description stays visible off production (check-content, draft ribbon), never on production
    'description'    => $pendingDescription && is_production() ? $description : $seoDescription,
    'canonical'      => $sub['url'],
    'status'         => $data['status'] ?? 'draft',
    'og_title'       => $seo['og_title'] ?? null,
    'og_description' => $seo['og_description'] ?? null,
    'og_image'       => $ogImage,
    'og_image_alt'   => $ogImage ? ($seo['og_image_alt'] ?? null) : null,
    'primary_image'  => $ogImage ?? (isset($hero['images'][0]['key']) ? App\Image::src($hero['images'][0]['key'], 1200) : null),
    'breadcrumbs'    => [['Home', '/'], ['Services', '/services/'], [$category['name'], $category['url']], [$sub['name'], null]],
    'schema'         => [App\Schema::service([
        'name'        => $sub['name'],
        'description' => $description,
        'url'         => $sub['url'],
        'serviceType' => $seo['service_type'] ?? $sub['name'],
        'category'    => ['name' => $category['name'], 'slug' => $category['slug']],
    ])],
    'body_class'     => 'page-service page-service-sub page-service--' . $category['colour'],
    'quote'          => ['service' => $subSlug],
    'header_theme'   => 'dark',
    'header_overlay' => true,
];

$cta = $data['cta'] ?? [];
?>
<?= component('hero-inner', [
    'variant'     => 'category',
    'colour'      => $category['colour'],
    'breadcrumbs' => $page['breadcrumbs'],
    'parent'      => [$category['name'], $category['url']],
    'title'       => $hero['title'] ?? $sub['name'],
    'accent'      => $hero['accent'] ?? null,
    'sub'         => $hero['sub'] ?? null,
    'lead'        => $hero['lead'] ?? $sub['summary'],
    'ctas'        => $hero['ctas'] ?? [[ui('cta_band.primary.0'), $quoteUrl], ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone']],
    'trust'       => $hero['trust'] ?? null,
    'images'      => $hero['images'] ?? [],
    'labels'      => $hero['labels'] ?? [],
    'icon'        => $sub['icon'],
    'icon_label'  => $sub['name'],
]) ?>

<?php foreach ($data['sections'] ?? [] as $block): ?>
<?php $note = pending_note($block); ?>
<?php if ($note !== null): ?>
<?= is_production() ? '' : render('sections/service/pending', ['block' => $block, 'note' => $note]) ?>
<?php else: ?>
<?= render('sections/service/' . basename((string) $block['type']), ['block' => $block, 'ctx' => $category]) ?>
<?php endif; ?>
<?php endforeach; ?>

<?= render('sections/service/related', ['items' => $related]) ?>

<?= render('sections/service/faq', ['faq' => ($data['faq'] ?? []) + ['heading' => $sub['name'], 'accent' => 'FAQs'], 'items' => faqs($data['faq_tag'] ?? $subSlug), 'id' => 'faq']) ?>

<?= component('cta-band', [
    'heading'       => $cta['heading'] ?? null,
    'accent'        => $cta['accent'] ?? null,
    'sub'           => $cta['sub'] ?? null,
    'text'          => $cta['paras'] ?? null,
    'strong'        => $cta['strong'] ?? null,
    'primary'       => [$cta['label'] ?? ui('cta_band.primary.0'), $quoteUrl],
    'show_whatsapp' => true,
]) ?>
