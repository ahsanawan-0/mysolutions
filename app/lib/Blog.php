<?php
declare(strict_types=1);

namespace App;

use Spatie\YamlFrontMatter\YamlFrontMatter;
use Throwable;

/**
 * The Insights blog (PROMPTS P16, P25): Markdown files, no database (docs/11 §6, option A).
 * Posts are content/blog/YYYY-MM-DD-slug.md with YAML front matter:
 *   title, slug (default: the file name without its date), description, date (YYYY-MM-DD), updated, author (key in
 *   content/authors.php, default `team`), category (slug in content/categories.php), tags[], image (image-manifest
 *   key), image_alt, takeaways[], status (published | draft; anything else = draft).
 * Bodies are rendered by App\Markdown (H2/H3 ids + TOC, rel="noopener" on external links, images through img()).
 * Reading time at 220 words per minute.
 *
 * Visibility: drafts never appear in production; locally and on staging all() lists them with 'draft' => true so the
 * templates can flag them. published() is status `published` only, everywhere (feed, sitemap, the listing's own
 * publish rule).
 *
 * The parsed index (front matter + rendered HTML) is cached in storage/cache/blog.php and rebuilt whenever a post,
 * authors.php, categories.php, the image manifest, the site URL or this renderer changes.
 *
 * API: all(), published(), latest($n), find($slug), byCategory($slug), paginate($perPage, $page, $posts),
 * related($post, $n), categories(), category($slug), author($key), displayDate($ymd).
 */
final class Blog
{
    public const WORDS_PER_MINUTE = 220;
    public const PER_PAGE = 9;
    private const CACHE_VERSION = 1;

    /** @var array<int, array<string, mixed>>|null every parsed post, newest first, drafts included */
    private static ?array $index = null;
    private static ?array $authors = null;
    private static ?array $categories = null;

    /** Every post visible in this environment, newest first (drafts included outside production). */
    public static function all(): array
    {
        return is_production() ? self::published() : self::index();
    }

    /** Published posts only, newest first. */
    public static function published(): array
    {
        return array_values(array_filter(self::index(), static fn (array $p): bool => !$p['draft']));
    }

    /** The newest $n visible posts. */
    public static function latest(int $n = 3): array
    {
        return array_slice(self::all(), 0, max(0, $n));
    }

    public static function find(string $slug): ?array
    {
        foreach (self::all() as $post) {
            if ($post['slug'] === $slug) {
                return $post;
            }
        }
        return null;
    }

    /** Visible posts in a category (or in $posts), newest first. */
    public static function byCategory(string $slug, ?array $posts = null): array
    {
        return array_values(array_filter($posts ?? self::all(), static fn (array $p): bool => $p['category_slug'] === $slug));
    }

    /**
     * One page of a listing. Page 1 leads with the newest post as the featured card, then $perPage cards; later pages
     * hold $perPage cards each. Out-of-range pages return 'items' => [] (the template answers 404).
     * @return array{featured: ?array, items: array, page: int, pages: int, total: int}
     */
    public static function paginate(int $perPage = self::PER_PAGE, int $page = 1, ?array $posts = null): array
    {
        $posts = array_values($posts ?? self::all());
        $total = count($posts);
        $perPage = max(1, $perPage);
        $pages = max(1, (int) ceil(max(0, $total - 1) / $perPage));
        $page = max(1, $page);
        $featured = $page === 1 ? ($posts[0] ?? null) : null;
        $items = $page > $pages ? [] : array_slice($posts, 1 + ($page - 1) * $perPage, $perPage);
        return ['featured' => $featured, 'items' => $items, 'page' => $page, 'pages' => $pages, 'total' => $total];
    }

    /** Up to $n other posts: same category first, then most shared tags, then the newest. */
    public static function related(array $post, int $n = 3): array
    {
        $scored = [];
        foreach (self::all() as $i => $other) {
            if ($other['slug'] === $post['slug']) {
                continue;
            }
            $score = ($other['category_slug'] !== '' && $other['category_slug'] === $post['category_slug'] ? 10 : 0)
                + count(array_intersect($other['tags'], $post['tags']));
            $scored[] = [$score, $i, $other];
        }
        usort($scored, static fn (array $a, array $b): int => $b[0] <=> $a[0] ?: $a[1] <=> $b[1]);
        return array_column(array_slice($scored, 0, max(0, $n)), 2);
    }

    /**
     * Categories from content/categories.php, in display order, each with url and count (visible posts).
     * @return array<string, array<string, mixed>>
     */
    public static function categories(): array
    {
        $out = [];
        foreach (self::categoryData() as $slug => $category) {
            $out[$slug] = $category + [
                'slug'  => $slug,
                'url'   => '/insights/category/' . $slug . '/',
                'count' => count(self::byCategory($slug)),
            ];
        }
        return $out;
    }

    public static function category(string $slug): ?array
    {
        return self::categories()[$slug] ?? null;
    }

    /** An author from content/authors.php; `team` for a missing or unknown key. */
    public static function author(?string $key): array
    {
        $authors = self::authorData();
        $key = $key !== null && isset($authors[$key]) ? $key : 'team';
        return ($authors[$key] ?? ['name' => (string) config('name'), 'type' => 'Organization']) + ['key' => $key];
    }

    /** UK display date: "18 January 2026". */
    public static function displayDate(string $ymd): string
    {
        $ts = strtotime($ymd);
        return $ts === false ? $ymd : date('j F Y', $ts);
    }

    /** Minutes to read $words words (at least 1). */
    public static function readingTime(int $words): int
    {
        return max(1, (int) ceil($words / self::WORDS_PER_MINUTE));
    }

    /* ---------- Index + cache ---------- */

    /** @return array<int, array<string, mixed>> every post (drafts included), newest first */
    private static function index(): array
    {
        if (self::$index !== null) {
            return self::$index;
        }
        $files = glob(base_path('content/blog/*.md')) ?: [];
        sort($files);
        $signature = self::signature($files);
        $cacheFile = storage_path('cache/blog.php');

        if (is_file($cacheFile)) {
            try {
                $cached = include $cacheFile;
                if (is_array($cached) && ($cached['signature'] ?? null) === $signature && is_array($cached['posts'] ?? null)) {
                    return self::$index = $cached['posts'];
                }
            } catch (Throwable) {
                // a broken cache file is simply rebuilt
            }
        }

        $posts = [];
        foreach ($files as $file) {
            $post = self::parse($file);
            if ($post !== null) {
                $posts[] = $post;
            }
        }
        usort($posts, static fn (array $a, array $b): int => strcmp($b['date'], $a['date']) ?: strcmp($b['file'], $a['file']));
        self::writeCache($cacheFile, ['signature' => $signature, 'posts' => $posts]);
        return self::$index = $posts;
    }

    /** @param string[] $files */
    private static function signature(array $files): string
    {
        $parts = [self::CACHE_VERSION, (string) config('url')];
        foreach ([...$files, base_path('content/authors.php'), base_path('content/categories.php'),
            public_path('assets/img/manifest.json'), __FILE__, __DIR__ . '/Markdown.php', __DIR__ . '/MarkdownImageRenderer.php'] as $file) {
            $parts[] = $file . '@' . (is_file($file) ? (string) filemtime($file) : '-');
        }
        return md5(implode('|', $parts));
    }

    private static function writeCache(string $file, array $data): void
    {
        $dir = dirname($file);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            return;   // read-only storage: the index is rebuilt per request instead
        }
        $tmp = $file . '.' . bin2hex(random_bytes(4)) . '.tmp';
        if (@file_put_contents($tmp, "<?php\n// Generated by App\\Blog (P25). Safe to delete.\nreturn " . var_export($data, true) . ";\n", LOCK_EX) === false) {
            return;
        }
        @rename($tmp, $file);
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($file, true);
        }
    }

    /** Parse one Markdown file; null when it has no title. */
    public static function parse(string $file): ?array
    {
        $doc = YamlFrontMatter::parseFile($file);
        $m = $doc->matter();
        $title = trim((string) ($m['title'] ?? ''));
        if ($title === '') {
            return null;
        }
        $name = basename($file, '.md');
        $fromName = preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', $name) ?: $name;
        $date = self::ymd($m['date'] ?? null) ?? (preg_match('/^(\d{4}-\d{2}-\d{2})/', $name, $d) ? $d[1] : date('Y-m-d', (int) filemtime($file)));
        $updated = self::ymd($m['updated'] ?? null);
        $slug = trim((string) ($m['slug'] ?? $fromName));
        $status = ($m['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

        // Category: a slug from content/categories.php (a category's name is accepted too); unknown → shown, unlinked
        $rawCategory = trim((string) ($m['category'] ?? ''));
        $categorySlug = '';
        $categoryName = $rawCategory;
        foreach (self::categoryData() as $key => $category) {
            if ($rawCategory === $key || strcasecmp($rawCategory, (string) $category['name']) === 0) {
                $categorySlug = $key;
                $categoryName = (string) $category['name'];
                break;
            }
        }

        $author = self::author(isset($m['author']) ? (string) $m['author'] : null);
        $rendered = Markdown::render($doc->body());

        return [
            'file'          => str_replace('\\', '/', substr($file, strlen(BASE_PATH) + 1)),
            'slug'          => $slug,
            'url'           => '/insights/' . $slug . '/',
            'title'         => $title,
            'description'   => trim((string) ($m['description'] ?? '')),
            'date'          => $date,
            'updated'       => $updated !== null && $updated > $date ? $updated : $date,
            'author'        => (string) $author['name'],
            'author_key'    => (string) $author['key'],
            'category'      => $categoryName,
            'category_slug' => $categorySlug,
            'category_url'  => $categorySlug !== '' ? '/insights/category/' . $categorySlug . '/' : null,
            'tags'          => self::strings($m['tags'] ?? []),
            'image'         => trim((string) ($m['image'] ?? '')),
            'image_alt'     => trim((string) ($m['image_alt'] ?? '')),
            'takeaways'     => self::strings($m['takeaways'] ?? []),
            'status'        => $status,
            'draft'         => $status !== 'published',
            'body'          => $doc->body(),
            'html'          => $rendered['html'],
            'toc'           => $rendered['toc'],
            'words'         => $rendered['words'],
            'reading_time'  => self::readingTime($rendered['words']),
        ];
    }

    private static function categoryData(): array
    {
        return self::$categories ??= (array) require base_path('content/categories.php');
    }

    private static function authorData(): array
    {
        return self::$authors ??= (array) require base_path('content/authors.php');
    }

    /** @return string[] */
    private static function strings(mixed $value): array
    {
        if (!is_array($value)) {
            $value = $value === null || $value === '' ? [] : [$value];
        }
        return array_values(array_filter(array_map(static fn ($v): string => trim((string) $v), $value), 'strlen'));
    }

    private static function ymd(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        $ts = is_int($value) ? $value : strtotime((string) $value);
        return $ts === false ? null : date('Y-m-d', $ts);
    }
}
