<?php
declare(strict_types=1);
/**
 * Insights listing /insights/ and /insights/page/<n>/ (PROMPTS P25, docs/08 §9, docs/10 §6 + §8).
 * hero-inner `simple` → sections/insights/listing (category chips, featured card, grid, pagination, empty state) →
 * CTA band. Each page is self-canonical with a unique title/description; /insights/page/1/ 301s to /insights/ and a
 * page past the end is a 404. `published` (indexable, in sitemap-insights.xml) once at least
 * insights('min_published') posts are published; a draft until then. Schema: CollectionPage + Blog.
 * Copy: app/data/insights.php (insights()).
 * @var array $params  ['n' => page number] on /insights/page/<n>/
 */
use App\Blog;
use App\Router;
use App\Schema;

$n = (int) ($params['n'] ?? 1);
if (isset($params['n']) && $n === 1) {
    Router::redirect('/insights/', 301);
}
$list = Blog::paginate(Blog::PER_PAGE, $n, Blog::all());
if ($n > $list['pages']) {
    Router::render('pages/404', [], 404);
}

$seo = insights('seo');
$hero = insights('hero');
$shown = array_values(array_filter([$list['featured'], ...$list['items']]));
$published = count(Blog::published()) >= (int) insights('min_published');
$canonical = $n > 1 ? '/insights/page/' . $n . '/' : '/insights/';
$page = [
    'title'          => $n > 1 ? strtr($seo['page_title'], ['{title}' => $seo['og_title'], '{n}' => (string) $n]) : $seo['title'],
    'description'    => $n > 1 ? strtr($seo['page_desc'], ['{n}' => (string) $n, '{description}' => $seo['description']]) : $seo['description'],
    'canonical'      => $canonical,
    'status'         => $published ? 'published' : 'draft',
    'og_title'       => $seo['og_title'],
    'web_page_type'  => 'CollectionPage',
    'main_entity'    => url('/insights/') . '#blog',
    'schema'         => [Schema::blog($shown)],
    'modified'       => $shown !== [] ? max(array_column($shown, 'updated')) : null,
    'breadcrumbs'    => $n > 1
        ? [['Home', '/'], ['Insights', '/insights/'], [str_replace('{n}', (string) $n, (string) insights('pagination.page')), null]]
        : [['Home', '/'], ['Insights', null]],
    'body_class'     => 'page-insights',
    'header_theme'   => 'light',
    'header_overlay' => true,
];
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => $page['breadcrumbs'],
    'title'       => $hero['title'],
    'accent'      => $hero['accent'],
    'lead'        => $hero['lead'],
]) ?>

<?= render('sections/insights/listing', ['list' => $list, 'current' => null, 'base' => '/insights/']) ?>

<?= component('cta-band') ?>
