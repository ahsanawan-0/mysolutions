<?php
declare(strict_types=1);
/**
 * HTML sitemap /sitemap/ (PROMPTS P29, docs/08 §12): every page grouped as Main pages, Services (by category),
 * Sectors, Insights (listing, categories with posts, posts) and Legal, generated from app/routes.php (the routes with
 * a `sitemap` group, the same set as sitemap.xml) and App\Blog. Labels are each page's own breadcrumb label.
 * On production only indexable pages are listed (published, not noindex), exactly like sitemap.xml; elsewhere drafts
 * are listed too with a "Draft" chip. Never listed: /thank-you/ and /styleguide/* (no `sitemap` group), this page.
 * Copy: ui('sitemap').
 */
use App\Blog;
use App\Page;

$t = ui('sitemap');
$production = is_production();
$routes = require base_path('app/routes.php');

// One entry per route: label (last breadcrumb, "Home" for /), url, draft; null when it must not be listed
$entry = static function (string $path, array $route) use ($production): ?array {
    if ($path === '/sitemap/' || isset($route['controller']) || str_contains($path, '{') || empty($route['sitemap'])) {
        return null;
    }
    $probe = Page::probe($route['template'], $route['params'] ?? [], $path);
    if ($probe === null || $probe['canonical'] === false) {
        return null;
    }
    $indexable = Page::isIndexable($probe);
    if ($production && !$indexable) {
        return null;
    }
    $crumbs = $probe['breadcrumbs'];
    $label = $path === '/' ? 'Home' : (string) (end($crumbs)[0] ?? $probe['og_title']);
    return ['label' => $label, 'url' => $path, 'draft' => !$indexable];
};
$get = static fn (string $path): ?array => isset($routes[$path]) ? $entry($path, $routes[$path]) : null;

// Main pages: the `pages` routes that are not legal pages
$legalPaths = ['/privacy-policy/', '/cookie-policy/', '/terms-and-conditions/', '/accessibility/'];
$main = [];
foreach ($routes as $path => $route) {
    if (($route['sitemap'] ?? null) === 'pages' && !in_array($path, $legalPaths, true) && ($e = $entry($path, $route)) !== null) {
        $main[] = $e;
    }
}
$legal = array_values(array_filter(array_map($get, $legalPaths)));

// Services: the hub, then each category with its sub-services
$servicesHub = $get('/services/');
$categories = [];
foreach (services() as $category) {
    $subs = array_values(array_filter(array_map(static fn (array $s): ?array => $get($s['url']), $category['subs'])));
    $self = $get($category['url']);
    if ($self !== null || $subs !== []) {
        $categories[] = ['self' => $self ?? ['label' => $category['name'], 'url' => null, 'draft' => false], 'subs' => $subs];
    }
}

// Sectors: the hub, then each sector
$sectorsHub = $get('/sectors/');
$sectorPages = array_values(array_filter(array_map(static fn (array $s): ?array => $get($s['url']), sectors())));

// Insights: the listing, the categories with visible posts, the posts (drafts only off production)
$insightsHub = $get('/insights/');
$posts = $production ? Blog::published() : Blog::all();
$publishedPosts = Blog::published();
$listingPublished = count($publishedPosts) >= (int) insights('min_published');
$insightCategories = [];
foreach (Blog::categories() as $slug => $category) {
    if (Blog::byCategory($slug, $posts) === []) {
        continue;
    }
    $catPublished = $listingPublished && Blog::byCategory($slug, $publishedPosts) !== [];
    if ($production && !$catPublished) {
        continue;
    }
    $insightCategories[] = ['label' => $category['name'], 'url' => $category['url'], 'draft' => !$catPublished];
}
$insightPosts = array_map(static fn (array $p): array => ['label' => $p['title'], 'url' => $p['url'], 'draft' => $p['draft']], $posts);

$hasDrafts = !$production;
$page = [
    'title'          => $t['seo_title'],
    'description'    => $t['description'],
    'canonical'      => '/sitemap/',
    'status'         => 'published',
    'breadcrumbs'    => [['Home', '/'], ['Sitemap', null]],
    'body_class'     => 'page-sitemap',
    'header_theme'   => 'light',
    'header_overlay' => true,
];

$link = static function (array $e) use ($t): string {
    $draft = $e['draft'] ? ' <span class="sitemap__draft">' . e($t['draft']) . '</span>' : '';
    return $e['url'] === null
        ? '<span class="sitemap__label">' . e($e['label']) . '</span>' . $draft
        : '<a href="' . e(url($e['url'])) . '">' . e($e['label']) . '</a>' . $draft;
};
$list = static function (array $entries, string $class = 'sitemap__list') use ($link): string {
    if ($entries === []) {
        return '';
    }
    return '<ul class="' . e($class) . '">' . implode('', array_map(static fn (array $e): string => '<li>' . $link($e) . '</li>', $entries)) . '</ul>';
};
$groups = $t['groups'];
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => $page['breadcrumbs'],
    'title'       => $t['title'],
    'accent'      => $t['accent'],
    'lead'        => $t['lead'],
]) ?>

<div class="sitemap" data-header-theme="light">
  <div class="container">
    <?php if ($hasDrafts): ?>
    <p class="sitemap__note"><?= icon('info', 'regular', ['size' => 18]) ?><span><?= e($t['draft_note']) ?></span></p>
    <?php endif; ?>

    <div class="sitemap__grid">
      <?php if ($main !== []): ?>
      <section class="sitemap__group" aria-labelledby="sm-main">
        <h2 class="sitemap__title" id="sm-main"><?= icon('house', 'light', ['size' => 24]) ?><span><?= e($groups['main']) ?></span></h2>
        <?= $list($main) ?>
      </section>
      <?php endif; ?>

      <?php if ($servicesHub !== null || $categories !== []): ?>
      <section class="sitemap__group sitemap__group--wide" aria-labelledby="sm-services">
        <h2 class="sitemap__title" id="sm-services"><?= icon('squares-four', 'light', ['size' => 24]) ?><span><?= e($groups['services']) ?></span></h2>
        <?php if ($servicesHub !== null): ?><?= $list([$servicesHub]) ?><?php endif; ?>
        <div class="sitemap__cats">
          <?php foreach ($categories as $cat): ?>
          <div class="sitemap__cat">
            <h3 class="sitemap__subtitle"><?= $link($cat['self']) ?></h3>
            <?= $list($cat['subs']) ?>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <?php if ($sectorsHub !== null || $sectorPages !== []): ?>
      <section class="sitemap__group" aria-labelledby="sm-sectors">
        <h2 class="sitemap__title" id="sm-sectors"><?= icon('buildings', 'light', ['size' => 24]) ?><span><?= e($groups['sectors']) ?></span></h2>
        <?= $list(array_values(array_filter([$sectorsHub, ...$sectorPages]))) ?>
      </section>
      <?php endif; ?>

      <?php if ($insightsHub !== null || $insightPosts !== []): ?>
      <section class="sitemap__group" aria-labelledby="sm-insights">
        <h2 class="sitemap__title" id="sm-insights"><?= icon('article', 'light', ['size' => 24]) ?><span><?= e($groups['insights']) ?></span></h2>
        <?= $list(array_values(array_filter([$insightsHub, ...$insightCategories]))) ?>
        <?php if ($insightPosts !== []): ?>
        <?= $list($insightPosts, 'sitemap__list sitemap__list--posts') ?>
        <?php endif; ?>
      </section>
      <?php endif; ?>

      <?php if ($legal !== []): ?>
      <section class="sitemap__group" aria-labelledby="sm-legal">
        <h2 class="sitemap__title" id="sm-legal"><?= icon('scales', 'light', ['size' => 24]) ?><span><?= e($groups['legal']) ?></span></h2>
        <?= $list($legal) ?>
      </section>
      <?php endif; ?>
    </div>
  </div>
</div>
