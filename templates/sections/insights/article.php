<?php
declare(strict_types=1);
/**
 * The article body (PROMPTS P26, docs/08 §9), under the `article` hero: reading-progress bar (fixed under the
 * header), Key takeaways (front matter `takeaways`), the table of contents (C41: sticky beside the body from 1100px,
 * a collapsible panel above it on smaller screens), the Markdown body (`.prose`, max 720px: lists, tables, quotes,
 * pull quotes, callouts), then the share links (C43) and the author box (C42).
 * Grid order = reading order: takeaways → contents → body → footer; on desktop the contents column spans every row.
 * @var array $post    App\Blog post
 * @var array $author  App\Blog::author()
 * @var string $url    Absolute canonical URL (share links)
 */
$copy = insights('article');
$hasToc = count($post['toc']) >= 2;
?>
<div class="reading-progress" data-module="reading-progress" data-target="#article-body" aria-hidden="true"><span class="reading-progress__bar"></span></div>

<div class="article<?= $hasToc ? ' article--toc' : '' ?><?= $post['takeaways'] !== [] ? ' article--takeaways' : '' ?>">
  <div class="container article__grid">
    <?php if ($post['takeaways'] !== []): ?>
    <aside class="takeaways article__takeaways" aria-labelledby="takeaways-title">
      <h2 class="takeaways__title" id="takeaways-title"><?= icon('lightbulb', 'regular', ['size' => 22]) ?><span><?= e($copy['takeaways']) ?></span></h2>
      <ul class="takeaways__list">
        <?php foreach ($post['takeaways'] as $point): ?>
        <li><?= icon('check', 'regular', ['size' => 18, 'class' => 'takeaways__check']) ?><span><?= rich((string) $point) ?></span></li>
        <?php endforeach; ?>
      </ul>
    </aside>
    <?php endif; ?>

    <?php if ($hasToc): ?>
    <div class="article__toc">
      <?= component('toc', ['items' => $post['toc']]) ?>
    </div>
    <?php endif; ?>

    <div class="article__body prose" id="article-body" data-module="article">
      <?= $post['html'] ?>
    </div>

    <footer class="article__footer">
      <?= component('share-links', ['url' => $url, 'title' => $post['title']]) ?>
      <?= component('author-box', ['author' => $author]) ?>
    </footer>
  </div>
</div>
