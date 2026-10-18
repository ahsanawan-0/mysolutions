<?php
declare(strict_types=1);

namespace App;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

/**
 * `![alt](image-key)` in an Insights article (PROMPTS P25) → img(): a responsive, lazy <picture> with width/height from
 * the image manifest (`npm run images`), or the branded placeholder while the key is missing. The optional title
 * (`![alt](key "Caption")`) is ignored; captions belong in the text. Only manifest keys are supported: an absolute
 * URL or a path would have no dimensions (docs/10 §5), so it renders as a visible `[[PENDING IMAGE: …]]` placeholder.
 */
final class MarkdownImageRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        Image::assertInstanceOf($node);
        $alt = trim(html_entity_decode(strip_tags($childRenderer->renderNodes($node->children())), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $key = trim($node->getUrl());
        if ($key === '' || preg_match('#^([a-z][a-z0-9+.-]*:|//|/)#i', $key) === 1) {
            $key = 'unsupported-image-url';
        }
        return img($key, [
            'alt'   => $alt,
            'sizes' => '(min-width: 800px) 720px, 100vw',
            'class' => 'prose-img',
        ]);
    }
}
