<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Blog;
use App\Page;

/**
 * /insights/feed.xml (PROMPTS P25, docs/10 §8): RSS 2.0 with the last 20 published posts (never drafts, in any
 * environment), each with its full description, category and author, plus <atom:link rel="self">. An empty channel
 * while nothing is published. Linked from every page's <head> (templates/partials/head-seo.php).
 */
final class FeedController
{
    private const LIMIT = 20;

    public function rss(): void
    {
        $posts = array_slice(Blog::published(), 0, self::LIMIT);
        $copy = (array) insights('feed');
        $self = url('/insights/feed.xml');
        $built = $posts !== [] ? max(array_map(static fn (array $p): int => (int) strtotime($p['updated']), $posts)) : null;

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">' . "\n"
            . "<channel>\n"
            . '  <title>' . $this->x((string) $copy['title']) . "</title>\n"
            . '  <link>' . $this->x(url('/insights/')) . "</link>\n"
            . '  <description>' . $this->x((string) $copy['description']) . "</description>\n"
            . "  <language>en-gb</language>\n"
            . ($built !== null ? '  <lastBuildDate>' . $this->x($this->rfc((string) date('Y-m-d', $built))) . "</lastBuildDate>\n" : '')
            . '  <atom:link href="' . $this->x($self) . '" rel="self" type="application/rss+xml"/>' . "\n";

        foreach ($posts as $post) {
            $link = Page::absolute($post['url']);
            $xml .= "  <item>\n"
                . '    <title>' . $this->x($post['title']) . "</title>\n"
                . '    <link>' . $this->x($link) . "</link>\n"
                . '    <guid isPermaLink="true">' . $this->x($link) . "</guid>\n"
                . '    <pubDate>' . $this->x($this->rfc($post['date'])) . "</pubDate>\n"
                . '    <description>' . $this->x($post['description']) . "</description>\n"
                . ($post['category'] !== '' ? '    <category>' . $this->x($post['category']) . "</category>\n" : '')
                . '    <dc:creator>' . $this->x($post['author']) . "</dc:creator>\n"
                . "  </item>\n";
        }
        $xml .= "</channel>\n</rss>\n";

        header('Content-Type: application/rss+xml; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');
        echo $xml;
    }

    /** A Y-m-d date as RFC 822 at midnight UK time ("Sun, 18 Jan 2026 00:00:00 +0000"). */
    private function rfc(string $ymd): string
    {
        $date = Page::date($ymd);
        $ts = $date !== null ? strtotime($date) : false;
        return date(DATE_RSS, $ts === false ? 0 : $ts);
    }

    private function x(string $s): string
    {
        return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
