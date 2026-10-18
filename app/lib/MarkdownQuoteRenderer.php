<?php
declare(strict_types=1);

namespace App;

use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

/**
 * Block quotes in Insights articles (PROMPTS P26). App\Markdown::onParsed() reads a GitHub-style marker on the first
 * line of a quote and stores it as the node's `callout` data; this renderer turns it into:
 *   > [!NOTE] Optional title        → <aside class="callout callout--note"> (also TIP, IMPORTANT, WARNING, UPDATE)
 *   > [!PULL]                       → <aside class="pull-quote"> (a line set large, lit up by `read-along`)
 *   > plain quote                   → <blockquote> (a real quotation)
 * The callout title defaults to the type's label (Note, Tip, …); the icon is decorative.
 */
final class MarkdownQuoteRenderer implements NodeRendererInterface
{
    /** type => [default title, Phosphor icon (regular)] */
    public const TYPES = [
        'note'      => ['Note', 'info'],
        'tip'       => ['Tip', 'lightbulb'],
        'important' => ['Important', 'seal-check'],
        'warning'   => ['Warning', 'warning-circle'],
        'update'    => ['Update', 'clock-counter-clockwise'],
        'pull'      => ['', ''],
    ];

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement|string
    {
        BlockQuote::assertInstanceOf($node);
        $inner = $childRenderer->renderNodes($node->children());
        $type = $node->data->get('callout', null);

        if ($type === null) {
            return new HtmlElement('blockquote', $node->data->get('attributes', []), "\n" . $inner . "\n");
        }
        if ($type === 'pull') {
            return '<aside class="pull-quote">' . icon('quotes', 'light', ['size' => 40, 'class' => 'pull-quote__mark'])
                . '<div class="pull-quote__text" data-anim="read-along">' . "\n" . $inner . "\n" . '</div></aside>';
        }
        [$label, $icon] = self::TYPES[$type];
        $title = (string) $node->data->get('callout_title', '');
        return '<aside class="callout callout--' . e($type) . '">' . "\n"
            . '<p class="callout__title">' . icon($icon, 'regular', ['size' => 20, 'class' => 'callout__icon'])
            . '<strong>' . e($title !== '' ? $title : $label) . '</strong></p>' . "\n"
            . '<div class="callout__body">' . "\n" . $inner . "\n" . '</div>' . "\n"
            . '</aside>';
    }
}
