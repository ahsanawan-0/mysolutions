<?php
declare(strict_types=1);
/**
 * Related insights under an article (PROMPTS P26): up to 3 C10 notched cards from App\Blog::related() (same category
 * first, then shared tags, then the newest), with a link to the listing. Renders nothing when there are none.
 * @var array $posts
 */
if ($posts === []) {
    return;
}
$copy = insights('article');
?>
<section class="article-related" aria-labelledby="related-title" data-header-theme="light">
  <div class="container">
    <header class="article-related__head">
      <div>
        <?= heading(2, $copy['related']['title'], $copy['related']['accent'], 'article-related__title', 'related-title') ?>
        <p class="article-related__sub"><?= e($copy['related_sub']) ?></p>
      </div>
      <?= component('button', ['label' => $copy['all'], 'href' => url('/insights/'), 'variant' => 'secondary', 'icon' => 'arrow-right']) ?>
    </header>
    <ul class="article-related__grid" role="list" data-anim="stagger">
      <?php foreach ($posts as $related): ?>
      <li class="article-related__item"><?= component('card-blog', ['post' => $related]) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
