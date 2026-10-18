<?php
declare(strict_types=1);
/**
 * Insights category /insights/category/<slug>/ and /insights/category/<slug>/page/<n>/ (PROMPTS P25, docs/08 §9):
 * the listing layout with the category's two-voice H1 (reads as the category name), its own SEO title and unique
 * description (content/categories.php), self-canonical. /page/1/ 301s to the category; an unknown slug (the route
 * only accepts the slugs in content/categories.php) or a page past the end is a 404. `published` when the Insights
 * listing is (insights('min_published')) and the category has at least one published post; a draft otherwise.
 * @var array $params  ['slug' => category slug, 'n' => page number on /page/<n>/]
 */
use App\Blog;
use App\Router;
use App\Schema;

$slug = (string) ($params['slug'] ?? '');
$category = Blog::category($slug);
if ($category === null) {
    Router::render('pages/404', [], 404);
}
$n = (int) ($params['n'] ?? 1);
if (isset($params['n']) && $n === 1) {
    Router::redirect($category['url'], 301);
}
$list = Blog::paginate(Blog::PER_PAGE, $n, Blog::byCategory($slug));
if ($n > $list['pages']) {
    Router::render('pages/404', [], 404);
}

$seo = insights('seo');
$shown = array_values(array_filter([$list['featured'], ...$list['items']]));
$published = count(Blog::published()) >= (int) insights('min_published')
    && count(Blog::byCategory($slug, Blog::published())) > 0;
$suffix = (string) config('seo.title_suffix', ' | My Solutionz');
$page = [
    'title'          => $n > 1 ? strtr($seo['page_title'], ['{title}' => str_replace($suffix, '', $category['seo_title']), '{n}' => (string) $n]) : $category['seo_title'],
    'description'    => $n > 1 ? strtr($seo['page_desc'], ['{n}' => (string) $n, '{description}' => $category['description']]) : $category['description'],
    'canonical'      => $n > 1 ? $category['url'] . 'page/' . $n . '/' : $category['url'],
    'status'         => $published ? 'published' : 'draft',
    'og_title'       => str_replace($suffix, '', $category['seo_title']),
    'web_page_type'  => 'CollectionPage',
    'main_entity'    => url('/insights/') . '#blog',
    'schema'         => [Schema::blog($shown)],
    'modified'       => $shown !== [] ? max(array_column($shown, 'updated')) : null,
    'breadcrumbs'    => $n > 1
        ? [['Home', '/'], ['Insights', '/insights/'], [$category['name'], $category['url']], [str_replace('{n}', (string) $n, (string) insights('pagination.page')), null]]
        : [['Home', '/'], ['Insights', '/insights/'], [$category['name'], null]],
    'body_class'     => 'page-insights page-insights-category',
    'header_theme'   => 'light',
    'header_overlay' => true,
];
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => $page['breadcrumbs'],
    'title'       => $category['title'],
    'accent'      => $category['accent'],
    'lead'        => $category['description'],
]) ?>

<?= render('sections/insights/listing', ['list' => $list, 'current' => $slug, 'base' => $category['url']]) ?>

<?= component('cta-band') ?>
