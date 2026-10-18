<?php
declare(strict_types=1);

/**
 * Global helper functions (autoloaded by Composer "files").
 * Templates use these; nothing else is global.
 */

/** Absolute path inside the project root. */
function base_path(string $path = ''): string
{
    return BASE_PATH . ($path === '' ? '' : '/' . ltrim($path, '/'));
}

/** Absolute path inside public/ (the web root). */
function public_path(string $path = ''): string
{
    return base_path('public' . ($path === '' ? '' : '/' . ltrim($path, '/')));
}

/**
 * Absolute path inside the writable storage folder: storage/ by default, or STORAGE_PATH where the project
 * root is read-only (Vercel: /tmp/storage).
 */
function storage_path(string $path = ''): string
{
    $root = rtrim((string) env('STORAGE_PATH', base_path('storage')), '/');
    return $root . ($path === '' ? '' : '/' . ltrim($path, '/'));
}

/** Absolute path to a template file: templates_path('pages/home') → …/templates/pages/home.php */
function templates_path(string $template): string
{
    return base_path('templates/' . ltrim($template, '/') . '.php');
}

/** Escape for HTML output. Every echo of dynamic data goes through this. */
function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

/** Read an environment variable (from .env or the real environment). */
function env(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return (string) $value;
}

/** Environment name: local | staging | production. */
function app_env(): string
{
    $env = env('APP_ENV', 'production');
    return in_array($env, ['local', 'staging', 'production'], true) ? $env : 'production';
}

function is_local(): bool
{
    return app_env() === 'local';
}

function is_production(): bool
{
    return app_env() === 'production';
}

/**
 * Site configuration from app/config/site.php, with dot access:
 * config('name'), config('address.postcode'), config('socials.linkedin').
 */
function config(string $key, mixed $default = null): mixed
{
    static $config = null;
    if ($config === null) {
        $config = require base_path('app/config/site.php');
    }
    $value = $config;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

/**
 * Absolute URL for a site path. Page paths get a trailing slash; file paths
 * (anything with an extension, e.g. /sitemap.xml) and hash/query parts are kept as given.
 * url('/about-us') → https://mysolutionz.co.uk/about-us/
 */
function url(string $path = '/'): string
{
    $base = rtrim((string) config('url'), '/');
    $path = '/' . ltrim($path, '/');

    [$pathOnly, $suffix] = preg_split('/(?=[?#])/', $path, 2) + [1 => ''];
    if ($pathOnly !== '/' && !str_ends_with($pathOnly, '/') && pathinfo($pathOnly, PATHINFO_EXTENSION) === '') {
        $pathOnly .= '/';
    }
    return $base . $pathOnly . $suffix;
}

/**
 * Root-relative URL for a static file in public/assets/, cache-busted with the file's mtime.
 * asset('og/home.jpg') → /assets/og/home.jpg?v=1760000000
 */
function asset(string $path): string
{
    $path = ltrim($path, '/');
    if (str_starts_with($path, 'assets/')) {
        $path = substr($path, 7);
    }
    $file = public_path('assets/' . $path);
    $version = is_file($file) ? (string) filemtime($file) : '0';
    return '/assets/' . $path . '?v=' . $version;
}

/**
 * Print the <link>/<script> tags for a Vite entry.
 * local + VITE_DEV=true → the Vite dev server (CSS is injected by @vite/client);
 * otherwise → hashed files from public/assets/build/manifest.json.
 */
function vite(string $entry = 'src/js/main.js'): string
{
    $devEnabled = is_local() && filter_var(env('VITE_DEV', 'false'), FILTER_VALIDATE_BOOLEAN);

    if ($devEnabled) {
        // The dev server serves under vite.config.js `base` ('/assets/build/'), same as the build output
        $server = 'http://localhost:5173/assets/build';
        return "<script type=\"module\" src=\"{$server}/@vite/client\"></script>\n"
            . "<script type=\"module\" src=\"{$server}/" . e($entry) . "\"></script>\n";
    }

    static $manifest = null;
    if ($manifest === null) {
        $file = public_path('assets/build/manifest.json');
        $manifest = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }

    if (!isset($manifest[$entry])) {
        return "<!-- vite: no build found for {$entry}. Run `npm run build` (or set VITE_DEV=true locally). -->\n";
    }

    $html = '';
    $chunk = $manifest[$entry];
    foreach ($chunk['css'] ?? [] as $css) {
        $html .= '<link rel="stylesheet" href="/assets/build/' . e($css) . "\">\n";
    }
    $html .= '<script type="module" src="/assets/build/' . e($chunk['file']) . "\"></script>\n";
    foreach ($chunk['imports'] ?? [] as $import) {
        if (isset($manifest[$import]['file'])) {
            $html .= '<link rel="modulepreload" href="/assets/build/' . e($manifest[$import]['file']) . "\">\n";
        }
    }
    return $html;
}

/** Current request path (decoded, no query string). */
function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return rawurldecode($path);
}

/**
 * True when $path is the current page (exact) or, for section roots like /services/,
 * an ancestor of it: used to mark the active nav item.
 */
function is_active(string $path, bool $exact = false): bool
{
    $current = current_path();
    $path = '/' . trim($path, '/') . '/';
    if ($path === '//') {
        $path = '/';
    }
    if ($exact || $path === '/') {
        return $current === $path;
    }
    return $current === $path || str_starts_with($current, $path);
}

/** Render a template (templates/<name>.php) to a string with $vars extracted into its scope. */
function render(string $template, array $vars = []): string
{
    $__file = templates_path($template);
    if (!is_file($__file)) {
        throw new RuntimeException("Template not found: {$template}");
    }
    extract($vars, EXTR_SKIP);
    ob_start();
    try {
        include $__file;
    } finally {
        $__html = ob_get_clean();
    }
    return (string) $__html;
}

/**
 * Inline SVG icon from the Phosphor sprite (public/assets/icons/sprite.svg, built by `npm run icons`).
 * icon('handshake')                     → light weight, decorative (aria-hidden)
 * icon('x', 'regular', ['label' => 'Close']) → role="img" + <title>
 * Other $attrs: class, size (px, default 24). Unknown icon on local → throws.
 */
function icon(string $name, string $weight = 'light', array $attrs = []): string
{
    static $known = null;
    if ($known === null) {
        $file = base_path('src/icons.json');
        $known = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    if (is_local() && $known !== [] && !in_array($name, $known[$weight] ?? [], true)) {
        throw new RuntimeException("icon('{$name}', '{$weight}'): not in src/icons.json. Add it and run `npm run icons`.");
    }

    static $spriteUrl = null;
    if ($spriteUrl === null) {
        $spriteUrl = asset('icons/sprite.svg');
    }

    $size = (int) ($attrs['size'] ?? 24);
    $class = 'icon icon--' . $name . (isset($attrs['class']) ? ' ' . $attrs['class'] : '');
    $label = $attrs['label'] ?? null;

    $svg = '<svg class="' . e($class) . '" width="' . $size . '" height="' . $size . '"';
    $svg .= $label === null
        ? ' aria-hidden="true" focusable="false"'
        : ' role="img" focusable="false"';
    $svg .= '>';
    if ($label !== null) {
        $svg .= '<title>' . e($label) . '</title>';
    }
    $svg .= '<use href="' . e($spriteUrl) . '#' . e($name . '-' . $weight) . '"></use></svg>';
    return $svg;
}

/** All service categories with their sub-services (app/data/services.php). */
function services(): array
{
    static $services = null;
    return $services ??= require base_path('app/data/services.php');
}

function service_category(string $slug): ?array
{
    foreach (services() as $category) {
        if ($category['slug'] === $slug) {
            return $category;
        }
    }
    return null;
}

function service_sub(string $categorySlug, string $subSlug): ?array
{
    $category = service_category($categorySlug);
    foreach ($category['subs'] ?? [] as $sub) {
        if ($sub['slug'] === $subSlug) {
            return $sub;
        }
    }
    return null;
}

/** All sectors (app/data/sectors.php). */
function sectors(): array
{
    static $sectors = null;
    return $sectors ??= require base_path('app/data/sectors.php');
}

/** One sector from app/data/sectors.php by slug, or null. */
function sector(string $slug): ?array
{
    foreach (sectors() as $sector) {
        if ($sector['slug'] === $slug) {
            return $sector;
        }
    }
    return null;
}

/**
 * Sector page copy (app/data/sector-pages/<slug>.php, PROMPTS P22): sector_page('transport'), sector_page('_hub').
 * Returns [] when the file does not exist or the slug is not a plain slug.
 */
function sector_page(string $slug): array
{
    static $pages = [];
    if (!array_key_exists($slug, $pages)) {
        $file = base_path('app/data/sector-pages/' . $slug . '.php');
        $pages[$slug] = preg_match('#^_?[a-z0-9-]+$#', $slug) === 1 && is_file($file) ? require $file : [];
    }
    return $pages[$slug];
}

/** A sub-service by its slug in any category (slugs are unique across categories): ['sub' => …, 'category' => …] or null. */
function service_sub_find(string $subSlug): ?array
{
    foreach (services() as $category) {
        foreach ($category['subs'] as $sub) {
            if ($sub['slug'] === $subSlug) {
                return ['sub' => $sub, 'category' => $category];
            }
        }
    }
    return null;
}

/**
 * FAQ items (app/data/faqs.php): faqs() → all, faqs('home') → items tagged with that page, faqs(null, 'basics') → one
 * group, faqs('bid-proposal-management', 'services') → both. Each item: id, q, a, group, pages.
 */
function faqs(?string $page = null, ?string $group = null): array
{
    static $data = null;
    $data ??= require base_path('app/data/faqs.php');
    return array_values(array_filter($data['items'], static fn (array $f): bool =>
        ($page === null || in_array($page, $f['pages'], true)) && ($group === null || $f['group'] === $group)));
}

/** FAQ group labels in display order (app/data/faqs.php → groups): slug → label. */
function faq_groups(): array
{
    static $data = null;
    $data ??= require base_path('app/data/faqs.php');
    return $data['groups'];
}

/** Permissioned client testimonials (app/data/testimonials.php). Empty until the client supplies them. */
function testimonials(): array
{
    static $items = null;
    return $items ??= require base_path('app/data/testimonials.php');
}

/** Primary navigation items (app/data/navigation.php). */
function navigation(): array
{
    static $nav = null;
    return $nav ??= require base_path('app/data/navigation.php');
}

/**
 * Inline an SVG from public/assets/ so its parts can be styled and animated (logo lockups, the splash mark).
 * inline_svg('brand/logo-horizontal.svg', ['prefix' => 'hl', 'class' => 'site-header__logo-svg', 'attrs' => ['data-anim' => 'logo-assembly']])
 *  - prefix: namespaces every id (id="x" → id="hl-x", plus href="#x" and url(#x) references) so one file can be
 *    inlined more than once per page. Without a prefix the ids are kept (one copy per page, e.g. the splash).
 *  - class: appended to the root element's class. attrs: extra root attributes.
 *  - title: false strips the <title>; a string replaces it and the root gets role="img". By default the SVG is
 *    decorative (aria-hidden="true" focusable="false"): the wrapping link or heading carries the accessible name.
 */
function inline_svg(string $file, array $o = []): string
{
    static $cache = [];
    $path = public_path('assets/' . ltrim($file, '/'));
    if (!isset($cache[$path])) {
        if (!is_file($path)) {
            if (is_local()) {
                throw new RuntimeException("inline_svg('{$file}'): file not found in public/assets/.");
            }
            return '';
        }
        $raw = (string) file_get_contents($path);
        $raw = (string) preg_replace('/<\?xml[^>]*\?>|<!--.*?-->/s', '', $raw);
        $cache[$path] = trim($raw);
    }
    $svg = $cache[$path];

    $prefix = isset($o['prefix']) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $o['prefix']) : '';
    if ($prefix !== '') {
        $svg = (string) preg_replace('/\bid="([^"]+)"/', 'id="' . $prefix . '-$1"', $svg);
        $svg = (string) preg_replace('/\bhref="#([^"]+)"/', 'href="#' . $prefix . '-$1"', $svg);
        $svg = (string) preg_replace('/url\(#([^)]+)\)/', 'url(#' . $prefix . '-$1)', $svg);
    }

    $title = $o['title'] ?? null;
    if ($title === false) {
        $svg = (string) preg_replace('/\s*<title>.*?<\/title>/s', '', $svg, 1);
    } elseif (is_string($title) && $title !== '') {
        $svg = preg_match('/<title>/', $svg)
            ? (string) preg_replace('/<title>.*?<\/title>/s', '<title>' . e($title) . '</title>', $svg, 1)
            : (string) preg_replace('/^(<svg[^>]*>)/', '$1<title>' . e($title) . '</title>', $svg, 1);
    }

    $attrs = $o['attrs'] ?? [];
    if (is_string($title) && $title !== '') {
        $attrs += ['role' => 'img', 'focusable' => 'false'];
    } else {
        $attrs += ['aria-hidden' => 'true', 'focusable' => 'false'];
    }
    if (!empty($o['class'])) {
        if (preg_match('/^<svg[^>]*\sclass="([^"]*)"/', $svg, $m)) {
            $svg = (string) preg_replace('/^(<svg[^>]*\sclass=")([^"]*)"/', '${1}' . e($m[1] . ' ' . $o['class']) . '"', $svg, 1);
        } else {
            $attrs['class'] = (string) $o['class'];
        }
    }
    $extra = '';
    foreach ($attrs as $name => $value) {
        if ($value === false || $value === null) {
            continue;
        }
        $extra .= ' ' . e((string) $name) . ($value === true ? '' : '="' . e((string) $value) . '"');
    }
    return (string) preg_replace('/^<svg\b/', '<svg' . $extra, $svg, 1);
}

/**
 * Logo lockup from public/assets/brand/ (SVGs built by `npm run logo` from the client's JPEGs, P07).
 * logo('colour' | 'white', ['height' => 40, 'class' => '…', 'inline' => true, 'prefix' => 'hl', 'lockup' => 'horizontal' | 'vertical' | 'mark'])
 * Default: a decorative <img> (alt=""; the wrapping link carries the label) with real width/height from the viewBox.
 * inline => true embeds the SVG (ids namespaced by prefix) so CSS can theme its parts (header: dark theme turns the
 * wordmark white and lightens the navy bar) and the splash can fly onto it.
 */
function logo(string $variant = 'colour', array $attrs = []): string
{
    static $boxes = [];
    $lockup = $attrs['lockup'] ?? 'horizontal';
    $file = 'brand/logo-' . $lockup . ($variant === 'white' && $lockup !== 'vertical' ? '-white' : '') . '.svg';
    $path = public_path('assets/' . $file);
    if (!is_file($path)) {
        return '<span class="logo-text' . ($variant === 'white' ? ' logo-text--light' : '') . '" aria-hidden="true"><span class="logo-text__mark"></span>' . e(config('name')) . '</span>';
    }
    if (!isset($boxes[$path])) {
        preg_match('/viewBox="0 0 ([\d.]+) ([\d.]+)"/', (string) file_get_contents($path), $m);
        $boxes[$path] = [(float) ($m[1] ?? 1), (float) ($m[2] ?? 1)];
    }
    [$vw, $vh] = $boxes[$path];
    $height = (int) ($attrs['height'] ?? 36);
    $width = (int) round($vw * $height / $vh);
    $class = 'logo-img' . (isset($attrs['class']) ? ' ' . $attrs['class'] : '');

    if (!empty($attrs['inline'])) {
        return inline_svg($file, [
            'prefix' => $attrs['prefix'] ?? 'logo',
            'class' => $class,
            'title' => false,
            'attrs' => ['width' => $width, 'height' => $height],
        ]);
    }
    return '<img class="' . e($class) . '" src="' . e(asset($file)) . '" alt="" width="' . $width . '" height="' . $height . '" decoding="async">';
}

/** Site-wide UI copy for reusable components (app/data/ui.php): ui('cta_band'), ui('footer.description'). */
function ui(string $key, mixed $default = null): mixed
{
    static $ui = null;
    $ui ??= require base_path('app/data/ui.php');
    return data_get($ui, $key, $default);
}

/** Home page copy (app/data/home.php): home('hero'), home('hero.links'). */
function home(string $key, mixed $default = null): mixed
{
    static $home = null;
    $home ??= require base_path('app/data/home.php');
    return data_get($home, $key, $default);
}

/** Team members (app/data/team.php): [] until real people with permission are supplied (docs/15 Q08). */
function team(): array
{
    static $team = null;
    $team ??= require base_path('app/data/team.php');
    return $team;
}

/** About page copy (app/data/about.php): about('story.paras'). */
function about(string $key, mixed $default = null): mixed
{
    static $about = null;
    $about ??= require base_path('app/data/about.php');
    return data_get($about, $key, $default);
}

/**
 * Service page copy (app/data/service-pages/, PROMPTS P20/P21): service_page('bid-proposal-management') (category),
 * service_page('_hub'), service_page('bid-proposal-management/expert-review') (sub-service, P21).
 * Returns [] when the file does not exist or the slug is not a plain `slug` / `category/sub` path.
 */
function service_page(string $slug): array
{
    static $pages = [];
    if (!array_key_exists($slug, $pages)) {
        $file = base_path('app/data/service-pages/' . $slug . '.php');
        $pages[$slug] = preg_match('#^_?[a-z0-9-]+(/[a-z0-9-]+)?$#', $slug) === 1 && is_file($file) ? require $file : [];
    }
    return $pages[$slug];
}

/**
 * The note of the first `[[PENDING: …]]` marker anywhere in $data (a block, a string), or null when there is none.
 * Service blocks with a marker render as a "Content pending" placeholder off production and are omitted on it.
 */
function pending_note(mixed $data): ?string
{
    if (is_string($data)) {
        return preg_match('/\[\[PENDING:?\s*(.*?)\]\]/s', $data, $m) === 1 ? trim($m[1]) : null;
    }
    if (is_array($data)) {
        foreach ($data as $value) {
            $note = pending_note($value);
            if ($note !== null) {
                return $note;
            }
        }
    }
    return null;
}

/**
 * The client copy conventions (docs/website_content/services/*.md) as safe HTML: the text is escaped first, then
 * `**bold**` → <strong> and `[label](/path/)` → <a href="url(path)"> (root-relative paths only). Nothing else
 * becomes HTML. Service page paragraphs are printed with rich().
 */
function rich(string $text): string
{
    $html = e($text);
    $html = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $html) ?? $html;
    return preg_replace_callback('#\[([^\]]+)\]\((/[^)\s]*)\)#', static fn (array $m): string =>
        '<a href="' . e(url(html_entity_decode($m[2], ENT_QUOTES | ENT_HTML5))) . '">' . $m[1] . '</a>', $html) ?? $html;
}

/** Contact page copy (app/data/contact.php, P23): contact('form.labels.name'). */
function contact(string $key, mixed $default = null): mixed
{
    static $contact = null;
    $contact ??= require base_path('app/data/contact.php');
    return data_get($contact, $key, $default);
}

/** Insights listing copy (app/data/insights.php, P25): insights('hero.title'). */
function insights(string $key, mixed $default = null): mixed
{
    static $insights = null;
    $insights ??= require base_path('app/data/insights.php');
    return data_get($insights, $key, $default);
}

/**
 * Path of the quote form with its pre-fill context (P24): quote_url(['service' => 'expert-review']) →
 * '/contact-us/?service=expert-review#quote'. Keys: `sector` (a sector slug) and `service` (a sub-service or category
 * slug); the contact page accepts whitelisted values only (ContactController::prefill()) and its canonical ignores
 * the query. Never put personal data in this URL (docs/05 §2).
 */
function quote_url(array $context = []): string
{
    $query = array_filter([
        'sector'  => (string) ($context['sector'] ?? ''),
        'service' => (string) ($context['service'] ?? ''),
    ], 'strlen');
    return '/contact-us/' . ($query !== [] ? '?' . http_build_query($query) : '') . '#quote';
}

/**
 * A colour token from src/css/tokens.css (`--navy-900: #…`) for places CSS cannot reach, e.g. the inline styles of
 * the HTML emails (P23), so no hex value is typed outside tokens.css. Returns '' for an unknown token.
 */
function token(string $name): string
{
    static $tokens = null;
    if ($tokens === null) {
        $tokens = [];
        $css = (string) @file_get_contents(base_path('src/css/tokens.css'));
        if (preg_match_all('/--([a-z0-9-]+):\s*(#[0-9A-Fa-f]{3,8})(?![0-9A-Fa-f])/', $css, $m, PREG_SET_ORDER)) {
            foreach ($m as [, $key, $hex]) {
                $tokens[$key] ??= $hex;
            }
        }
    }
    return $tokens[$name] ?? '';
}

/** Dot-path lookup in a nested array: data_get($arr, 'a.b.c'). */
function data_get(array $data, string $key, mixed $default = null): mixed
{
    $value = $data;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

/** Responsive <picture> (or branded placeholder) from the image manifest. See App\Image::render(). */
function img(string $key, array $o = []): string
{
    return App\Image::render($key, $o);
}

/** Render a foundation component partial: component('button', ['label' => 'Get a Free Quote', 'href' => '/contact-us/']). */
function component(string $name, array $vars = []): string
{
    return render('components/' . $name, $vars);
}

/**
 * Two-voice heading (C02): heading(2, 'Our Approach to', 'Business Growth')
 * → <h2>Our Approach to <em>Business Growth</em></h2>. The accent is max 2–3 words.
 * $attrs adds attributes, e.g. ['data-anim' => 'two-voice'] (P06 motion hooks).
 */
function heading(int $level, string $text, ?string $accent = null, string $class = '', ?string $id = null, array $attrs = []): string
{
    $level = max(1, min(6, $level));
    $open = '<h' . $level . ($class !== '' ? ' class="' . e($class) . '"' : '') . ($id !== null ? ' id="' . e($id) . '"' : '');
    foreach ($attrs as $name => $value) {
        if (preg_match('/^[a-zA-Z][\w-]*$/', (string) $name) === 1) {
            $open .= ' ' . $name . ($value === true ? '' : '="' . e((string) $value) . '"');
        }
    }
    $open .= '>';
    $inner = e($text);
    if ($accent !== null && $accent !== '') {
        $inner .= ' <em>' . e($accent) . '</em>';
    }
    return $open . $inner . '</h' . $level . '>';
}
