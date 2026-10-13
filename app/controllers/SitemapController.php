<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Page;

/**
 * XML sitemaps (docs/10 §8): index → pages / services / sectors / insights, plus the temporary
 * legacy sitemap of old URLs. Only published, indexable, 200 URLs; lastmod from the page's
 * `modified` date or the template file's mtime (never "now").
 */
final class SitemapController
{
    private const GROUPS = ['pages', 'services', 'sectors', 'insights'];

    public function index(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach (self::GROUPS as $group) {
            $entries = $this->entries($group);
            if ($entries === []) {
                continue;
            }
            $latest = max(array_column($entries, 'lastmod'));
            $xml .= "  <sitemap>\n    <loc>" . $this->x(url("/sitemap-{$group}.xml")) . "</loc>\n"
                . "    <lastmod>" . $this->x(date('c', $latest)) . "</lastmod>\n  </sitemap>\n";
        }
        $xml .= "</sitemapindex>\n";
        $this->send($xml);
    }

    public function pages(): void    { $this->urlset($this->entries('pages')); }
    public function services(): void { $this->urlset($this->entries('services')); }
    public function sectors(): void  { $this->urlset($this->entries('sectors')); }
    public function insights(): void { $this->urlset($this->entries('insights')); }

    /** Old URLs (exact 301 keys from app/redirects.php) so Google recrawls the redirects after launch. */
    public function legacy(): void
    {
        if (!config('seo.legacy_sitemap', false)) {
            http_response_code(404);
            header('Content-Type: text/plain; charset=UTF-8');
            echo "Not found\n";
            return;
        }
        $redirects = require base_path('app/redirects.php');
        $host = rtrim((string) config('production_url'), '/');
        $entries = [];
        foreach (array_keys($redirects['exact']) as $old) {
            $entries[] = ['loc' => $host . $old, 'lastmod' => null, 'image' => null];
        }
        $this->urlset($entries);
    }

    /**
     * @return array<int, array{loc:string,lastmod:?int,image:?string}>
     */
    private function entries(string $group): array
    {
        $routes = require base_path('app/routes.php');
        $entries = [];
        foreach ($routes as $path => $route) {
            if (($route['sitemap'] ?? null) !== $group || isset($route['controller']) || str_contains($path, '{')) {
                continue;
            }
            $page = Page::probe($route['template'], $route['params'] ?? [], $path);
            if ($page === null || !Page::isIndexable($page)) {
                continue;
            }
            $lastmod = $page['modified'] !== null ? strtotime($page['modified']) : $page['template_mtime'];
            $entries[] = [
                'loc'     => (string) $page['canonical'],
                'lastmod' => $lastmod ?: $page['template_mtime'],
                'image'   => $page['primary_image'],
            ];
        }
        // P25 adds blog posts and categories from content/blog to the "insights" group via App\Blog.
        return $entries;
    }

    /** @param array<int, array{loc:string,lastmod:?int,image:?string}> $entries */
    private function urlset(array $entries): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
            . ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
        foreach ($entries as $e) {
            $xml .= "  <url>\n    <loc>" . $this->x($e['loc']) . "</loc>\n";
            if ($e['lastmod'] !== null) {
                $xml .= "    <lastmod>" . $this->x(date('c', $e['lastmod'])) . "</lastmod>\n";
            }
            if ($e['image'] !== null) {
                $xml .= "    <image:image><image:loc>" . $this->x($e['image']) . "</image:loc></image:image>\n";
            }
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>\n";
        $this->send($xml);
    }

    private function send(string $xml): void
    {
        header('Content-Type: application/xml; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');
        echo $xml;
    }

    private function x(string $s): string
    {
        return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
