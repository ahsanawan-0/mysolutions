<?php
declare(strict_types=1);
/**
 * Insights article /insights/<slug>/ (PROMPTS P26, docs/08 §9, docs/10 §5–§6 + §10 E-E-A-T): one template for every
 * post in content/blog/*.md (App\Blog). An unknown slug, or a draft on production, is a 404.
 * Order: hero-inner `article` (breadcrumbs Home › Insights › Category › Post, category chip, H1, lead, meta row:
 * author, published date, "Updated" date when it differs, reading time; the featured image = LCP) → the article body
 * (sections/insights/article: progress bar, Key takeaways, sticky / collapsible contents, prose, share links, author
 * box) → related insights (3 C10 cards) → CTA band (pre-filled with the category's `quote` context).
 * SEO: <title> = front matter `seo_title`, else the title + suffix when that fits in 65 characters, else the title;
 * og:type article + article:* tags; the photographic 1200×630 OG crop; WebPage → mainEntity #article; BlogPosting
 * with the 16:9, 4:3 and 1:1 crops (scripts/images.mjs), ISO 8601 Europe/London dates, the author (Person, or the
 * #organization for the team), publisher #organization.
 * @var array $params  ['slug' => post slug]
 */
use App\Blog;
use App\Image;
use App\Page;
use App\Router;
use App\Schema;

$post = Blog::find((string) ($params['slug'] ?? ''));
if ($post === null) {
    Router::render('pages/404', [], 404);
}

$copy = insights('article');
$author = Blog::author($post['author_key']);
$category = $post['category_slug'] !== '' ? Blog::category($post['category_slug']) : null;
$suffix = (string) config('seo.title_suffix', ' | My Solutionz');
$title = $post['seo_title'] !== ''
    ? $post['seo_title']
    : (mb_strlen($post['title'] . $suffix) <= 65 ? $post['title'] . $suffix : $post['title']);

// Images: the schema crops (16:9, 4:3, 1:1) and the OG crop from the image pipeline; the largest rendition otherwise
$key = $post['image'];
$crops = $key !== '' ? array_values(array_filter([Image::crop($key, '16x9'), Image::crop($key, '4x3'), Image::crop($key, '1x1')])) : [];
if ($crops === [] && $key !== '' && ($largest = Image::src($key, 1200)) !== null) {
    $crops = [$largest];
}
$ogImage = $key !== '' ? Image::crop($key, 'og') : null;

$breadcrumbs = [['Home', '/'], ['Insights', '/insights/']];
if ($category !== null) {
    $breadcrumbs[] = [$category['name'], $category['url']];
}
$breadcrumbs[] = [$post['title'], null];

$page = [
    'title'           => $title,
    'description'     => $post['description'],
    'canonical'       => $post['url'],
    'status'          => $post['status'],
    'og_type'         => 'article',
    'og_image'        => $ogImage,
    'og_image_alt'    => $ogImage !== null && $post['image_alt'] !== '' ? $post['image_alt'] : null,
    'primary_image'   => $crops[0] ?? null,
    'published'       => $post['date'],
    'modified'        => $post['updated'],
    'article_author'  => Page::absolute((string) ($author['url'] ?? '/about-us/')),
    'article_section' => $post['category'],
    'article_tags'    => $post['tags'],
    'main_entity'     => url($post['url']) . '#article',
    'schema'          => [Schema::blogPosting([
        'title'       => $post['title'],
        'description' => $post['description'],
        'url'         => $post['url'],
        'published'   => $post['date'],
        'modified'    => $post['updated'],
        'author'      => $author,
        'images'      => array_map([Page::class, 'absolute'], $crops),
        'section'     => $post['category'],
        'keywords'    => $post['tags'],
        'wordCount'   => $post['words'],
    ])],
    'breadcrumbs'     => $breadcrumbs,
    'body_class'      => 'page-article',
    'quote'           => $category['quote'] ?? [],
    'header_theme'    => 'light',
    'header_overlay'  => true,
];

$meta = [
    ['icon' => 'user', 'text' => strtr($copy['by'], ['{name}' => $author['name']])],
    ['icon' => 'calendar-blank', 'label' => trim(str_replace('{date}', '', $copy['published'])), 'text' => Blog::displayDate($post['date']), 'datetime' => $post['date']],
];
if ($post['updated'] !== $post['date']) {
    $meta[] = ['icon' => 'clock-counter-clockwise', 'label' => trim(str_replace('{date}', '', $copy['updated'])), 'text' => Blog::displayDate($post['updated']), 'datetime' => $post['updated']];
}
$meta[] = ['icon' => 'clock', 'text' => strtr((string) insights('read'), ['{n}' => (string) $post['reading_time']])];
$cta = ui('cta_band');
?>
<?= component('hero-inner', [
    'variant'     => 'article',
    'breadcrumbs' => $page['breadcrumbs'],
    'chip'        => $post['category'] !== '' ? [$post['category'], $post['category_url'] !== null ? url($post['category_url']) : null] : null,
    'title'       => $post['title'],
    'lead'        => $post['description'],
    'meta'        => $meta,
    'image'       => $key !== '' ? ['key' => $key, 'alt' => $post['image_alt']] : null,
]) ?>

<?= render('sections/insights/article', ['post' => $post, 'author' => $author, 'url' => url($post['url'])]) ?>

<?= render('sections/insights/related', ['posts' => Blog::related($post, 3)]) ?>

<?= component('cta-band', ['primary' => [$cta['primary'][0], quote_url($page['quote'])]]) ?>
