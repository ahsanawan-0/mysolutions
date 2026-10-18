<?php
declare(strict_types=1);

namespace App;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\StringContainerHelper;

/**
 * Markdown → HTML for the Insights articles (PROMPTS P25): league/commonmark with CommonMark + GFM (tables, wrapped in
 * `.prose-table` so they scroll on small screens; strikethrough; autolinks; task lists). Raw HTML in a post is escaped
 * (it shows up visibly, so nothing slips through unnoticed) and unsafe link schemes are dropped. On top of that:
 *  - H2/H3 get ids from their text (unique per article) and are returned as the table of contents (P26);
 *  - external links get rel="noopener" (no forced new tab);
 *  - `![alt](image-key)` renders through img() (App\Image: responsive <picture>, lazy, width/height; the branded
 *    placeholder while the key is not in the manifest). See App\MarkdownImageRenderer.
 */
final class Markdown
{
    private static ?MarkdownConverter $converter = null;

    /** @var array<int, array{level:int,id:string,text:string}> */
    private static array $toc = [];

    /**
     * @return array{html:string, toc: array<int, array{level:int,id:string,text:string}>, words:int}
     */
    public static function render(string $markdown): array
    {
        self::$toc = [];
        $html = (string) self::converter()->convert($markdown);
        $text = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $words = $text === '' ? 0 : (int) preg_match_all("/[\\p{L}\\p{N}][\\p{L}\\p{N}'’-]*/u", $text);
        return ['html' => $html, 'toc' => self::$toc, 'words' => $words];
    }

    private static function converter(): MarkdownConverter
    {
        if (self::$converter !== null) {
            return self::$converter;
        }
        $environment = new Environment([
            'html_input'         => 'escape',
            'allow_unsafe_links' => false,
            'max_nesting_level'  => 50,
            'table'              => ['wrap' => ['enabled' => true, 'tag' => 'div', 'attributes' => ['class' => 'prose-table']]],
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addRenderer(Image::class, new MarkdownImageRenderer(), 10);
        $environment->addEventListener(DocumentParsedEvent::class, [self::class, 'onParsed']);
        return self::$converter = new MarkdownConverter($environment);
    }

    /** Heading ids + TOC, and rel="noopener" on external links. */
    public static function onParsed(DocumentParsedEvent $event): void
    {
        $used = [];
        $host = strtolower((string) parse_url((string) config('url'), PHP_URL_HOST));
        $production = strtolower((string) parse_url((string) config('production_url'), PHP_URL_HOST));

        foreach ($event->getDocument()->iterator() as $node) {
            if ($node instanceof Heading && in_array($node->getLevel(), [2, 3], true)) {
                $text = trim(StringContainerHelper::getChildText($node));
                $base = self::slug($text) ?: 'section';
                $id = $base;
                for ($i = 2; isset($used[$id]); $i++) {
                    $id = $base . '-' . $i;
                }
                $used[$id] = true;
                $node->data->set('attributes/id', $id);
                self::$toc[] = ['level' => $node->getLevel(), 'id' => $id, 'text' => $text];
            } elseif ($node instanceof Link) {
                $linkHost = strtolower((string) parse_url($node->getUrl(), PHP_URL_HOST));
                if ($linkHost !== '' && $linkHost !== $host && $linkHost !== $production) {
                    $node->data->set('attributes/rel', 'noopener');
                }
            }
        }
    }

    /** "What's in a tender?" → "whats-in-a-tender" */
    public static function slug(string $text): string
    {
        $text = strtolower(str_replace(['’', "'"], '', $text));
        return trim((string) preg_replace('/[^a-z0-9]+/', '-', $text), '-');
    }
}
