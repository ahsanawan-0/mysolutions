<?php
declare(strict_types=1);
/**
 * FAQ /faq/ (PROMPTS P27, docs/08 §10): hero-inner `simple` (H1 "Frequently Asked Questions") → the FAQ browser
 * (sections/faq/browser: search form, topic chips, grouped C17 accordions, empty state) → "Still have a question?"
 * CTA band → contact. Questions: app/data/faqs.php items tagged `faq`, in their group order; page copy:
 * app/data/faq-page.php. A [[PENDING]] answer is left out on production.
 * Search works without JS: the form submits ?q=… and the server filters (faq_matches()); faq-search.js filters as
 * you type and keeps the URL in step. A search URL is noindex, its canonical /faq/.
 * Schema: WebPage + BreadcrumbList; FAQPage only when config('seo.faq_schema') is on (off: no FAQ rich results since
 * 7 May 2026, docs/10 §6), built from every question, not the filtered ones.
 */
use App\Schema;

$groups = faq_groups();
$items = array_values(array_filter(faqs('faq'), static fn (array $f): bool =>
    isset($groups[$f['group']]) && (!is_production() || pending_note($f['a']) === null)));
$query = trim(mb_substr((string) ($_GET['q'] ?? ''), 0, 80));
$shown = $query === '' ? $items : array_values(array_filter($items, static fn (array $f): bool => faq_matches($f, $query)));

$seo = faq_page('seo');
$page = [
    'title'          => $seo['title'],
    'description'    => $seo['description'],
    'og_title'       => $seo['og_title'],
    'canonical'      => '/faq/',
    'status'         => faq_page('status', 'draft'),
    'robots'         => $query !== '' ? 'noindex, follow' : (string) config('seo.default_robots'),
    'breadcrumbs'    => [['Home', '/'], ['FAQ', null]],
    'schema'         => [Schema::faqPage(array_map(static fn (array $f): array => ['q' => $f['q'], 'a' => rich_text($f['a'])], $items), url('/faq/'))],
    'body_class'     => 'page-faq',
    'header_theme'   => 'light',
    'header_overlay' => true,
];
$cta = faq_page('cta');
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => $page['breadcrumbs'],
    'title'       => faq_page('hero.title'),
    'accent'      => faq_page('hero.accent'),
    'lead'        => faq_page('hero.lead'),
]) ?>

<?= render('sections/faq/browser', ['groups' => $groups, 'items' => $items, 'shown' => $shown, 'query' => $query]) ?>

<?= component('cta-band', [
    'eyebrow' => $cta['eyebrow'],
    'heading' => $cta['heading'],
    'accent'  => $cta['accent'],
    'text'    => $cta['text'],
    'primary' => $cta['primary'],
]) ?>
