<?php
declare(strict_types=1);

namespace App;

use Spatie\YamlFrontMatter\YamlFrontMatter;

/**
 * Minimal blog reader (PROMPTS P16; P25/P26 add rendering, categories, pagination, reading time and the feed).
 * Posts are content/blog/YYYY-MM-DD-slug.md with YAML front matter: title, slug (optional, default = the file name
 * without its date), description, date (YYYY-MM-DD), updated, author, category, image (image-manifest key),
 * image_alt, status (published|draft). Drafts never appear in production; locally and on staging they are listed with
 * 'draft' => true so the templates can show a "Draft" chip.
 */
final class Blog
{
    /** @var array<int, array<string, mixed>>|null */
    private static ?array $posts = null;

    /** Every post, newest first (drafts included outside production). */
    public static function all(): array
    {
        if (self::$posts !== null) {
            return self::$posts;
        }
        $posts = [];
        foreach (glob(base_path('content/blog/*.md')) ?: [] as $file) {
            $post = self::parse($file);
            if ($post === null || ($post['draft'] && is_production())) {
                continue;
            }
            $posts[] = $post;
        }
        usort($posts, static fn (array $a, array $b): int => strcmp($b['date'], $a['date']) ?: strcmp($b['file'], $a['file']));
        return self::$posts = $posts;
    }

    /** The newest $n posts. */
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

    /** Parse one markdown file; null when it has no title. */
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
        $date = self::ymd($m['date'] ?? null) ?? (preg_match('/^(\d{4}-\d{2}-\d{2})/', $name, $d) ? $d[1] : date('Y-m-d'));
        $slug = trim((string) ($m['slug'] ?? $fromName));
        $category = trim((string) ($m['category'] ?? ''));
        $status = ($m['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

        return [
            'file'          => str_replace('\\', '/', substr($file, strlen(BASE_PATH) + 1)),
            'slug'          => $slug,
            'url'           => '/insights/' . $slug . '/',
            'title'         => $title,
            'description'   => trim((string) ($m['description'] ?? '')),
            'date'          => $date,
            'updated'       => self::ymd($m['updated'] ?? null) ?? $date,
            'author'        => trim((string) ($m['author'] ?? config('name'))),
            'category'      => $category,
            'category_slug' => $category !== '' ? trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($category)), '-') : '',
            'image'         => trim((string) ($m['image'] ?? '')),
            'image_alt'     => trim((string) ($m['image_alt'] ?? '')),
            'status'        => $status,
            'draft'         => $status !== 'published',
            'body'          => $doc->body(),
        ];
    }

    /** UK display date: "18 January 2026". */
    public static function displayDate(string $ymd): string
    {
        $ts = strtotime($ymd);
        return $ts === false ? $ymd : date('j F Y', $ts);
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
