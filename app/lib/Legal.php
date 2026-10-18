<?php
declare(strict_types=1);

namespace App;

use Spatie\YamlFrontMatter\YamlFrontMatter;

/**
 * The legal pages (PROMPTS P28, docs/13 §3–§6): content/legal/<slug>.md rendered by App\Markdown (headings → the
 * sticky contents, links, callouts, tables). Front matter: title + accent (the two-voice H1), seo_title, description,
 * lead, updated (Y-m-d), status (published | draft).
 * Company facts are tokens, so they always match app/config/site.php: {{legal_name}}, {{name}}, {{company_number}},
 * {{registered_in}}, {{registered_office}}, {{email}}, {{phone}}, {{site}}, {{updated}}.
 * Slots are filled after rendering (a paragraph holding only the slot): {{cookie-table}} = the technologies table
 * from app/config/cookies.php (sections/legal/cookie-table), {{cookie-settings}} = the "change settings" control
 * (sections/legal/cookie-settings).
 */
final class Legal
{
    public static function load(string $slug): array
    {
        $file = base_path('content/legal/' . $slug . '.md');
        $doc = YamlFrontMatter::parseFile($file);
        $m = $doc->matter();
        $updated = isset($m['updated']) ? date('Y-m-d', is_int($m['updated']) ? $m['updated'] : (int) strtotime((string) $m['updated'])) : date('Y-m-d', (int) filemtime($file));
        $rendered = Markdown::render(strtr($doc->body(), self::tokens($updated)));
        $html = preg_replace_callback('#<p>\{\{([a-z-]+)\}\}</p>#', static fn (array $s): string =>
            in_array($s[1], ['cookie-table', 'cookie-settings'], true) ? render('sections/legal/' . $s[1]) : $s[0], $rendered['html']) ?? $rendered['html'];

        return [
            'slug'        => $slug,
            'url'         => '/' . $slug . '/',
            'title'       => (string) ($m['title'] ?? ''),
            'accent'      => isset($m['accent']) ? (string) $m['accent'] : null,
            'seo_title'   => (string) ($m['seo_title'] ?? ''),
            'description' => (string) ($m['description'] ?? ''),
            'lead'        => (string) ($m['lead'] ?? ''),
            'updated'     => $updated,
            'status'      => ($m['status'] ?? 'draft') === 'published' ? 'published' : 'draft',
            'html'        => $html,
            'toc'         => $rendered['toc'],
        ];
    }

    /** The page contract for a legal document (docs/11 §4). */
    public static function page(array $doc): array
    {
        $name = trim($doc['title'] . ' ' . ($doc['accent'] ?? ''));
        return [
            'title'          => $doc['seo_title'],
            'description'    => $doc['description'],
            'canonical'      => $doc['url'],
            'status'         => $doc['status'],
            'modified'       => $doc['updated'],
            'breadcrumbs'    => [['Home', '/'], [$name, null]],
            'body_class'     => 'page-legal page-legal--' . $doc['slug'],
            'header_theme'   => 'light',
            'header_overlay' => true,
        ];
    }

    /** @return array<string, string> */
    private static function tokens(string $updated): array
    {
        $a = (array) config('address');
        return [
            '{{legal_name}}'        => (string) config('legal_name'),
            '{{name}}'              => (string) config('name'),
            '{{company_number}}'    => (string) config('company_number'),
            '{{registered_in}}'     => (string) config('registered_in'),
            '{{registered_office}}' => trim(($a['street'] ?? '') . ', ' . ($a['locality'] ?? '') . ' ' . ($a['postcode'] ?? '')),
            '{{email}}'             => (string) config('email'),
            '{{phone}}'             => (string) config('phone_display'),
            '{{site}}'              => (string) config('production_url', 'https://mysolutionz.co.uk'),
            '{{updated}}'           => Blog::displayDate($updated),
        ];
    }
}
