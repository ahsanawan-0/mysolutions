<?php
declare(strict_types=1);
/**
 * C10 Blog card, the Innovax notched card (docs/04 §1.3, PROMPTS P16): white surface (radius 24) with the image
 * inset (radius 20), the date in a white tab cut into the image's bottom-left (a true mask, `.notch--bl`), the
 * title (two lines) and the meta row (author uppercase + outlined category pill), and a circular arrow button in a
 * concave cut-out at the surface's bottom-right (`.notch--br` on the surface; the button is a sibling outside the
 * mask so it stays visible). Hover: image zoom 1.06, arrow −45° → 0° and fills Azure. The whole card is one link.
 * Pass `soon` instead of `post` for the "More insights coming soon" card.
 * P25: `variant => 'featured'` is the Insights listing's large card (image beside the text from 900px, the
 * description and the reading time); every post card carries `data-flip-id` for the category filter's GSAP Flip.
 * @var array|null  $post    App\Blog post: title, url, date, author, category, image, image_alt, draft, description,
 *                           reading_time
 * @var array|null  $soon    ['title' => …, 'text' => …, 'label' => …, 'url' => …] (external URLs get rel="noopener")
 * @var string|null $variant 'default' | 'featured'
 * @var int|null    $level   Heading level (default 3)
 * @var string|null $sizes   <img> sizes (default '(min-width: 1024px) 400px, (min-width: 768px) 50vw, 100vw')
 * @var bool|null   $priority  Above the fold: no lazy loading
 * @var string|null $class
 */
$level = max(2, min(6, $level ?? 3));
$tag = 'h' . $level;
$isSoon = isset($soon);
$featured = !$isSoon && ($variant ?? '') === 'featured';
$sizes = $sizes ?? ($featured ? '(min-width: 1360px) 760px, (min-width: 900px) 56vw, 100vw' : '(min-width: 1024px) 400px, (min-width: 768px) 50vw, 100vw');
$url = $isSoon ? ($soon['url'] ?? config('socials.linkedin')) : $post['url'];
$external = !str_starts_with($url, '/');
$href = $external ? $url : url($url);
$classes = 'card-blog' . ($isSoon ? ' card-blog--soon' : '') . ($featured ? ' card-blog--featured' : '') . (isset($class) ? ' ' . $class : '');
?>
<article class="<?= e($classes) ?>"<?= $isSoon ? '' : ' data-flip-id="post-' . e($post['slug']) . '"' ?>>
  <div class="card-blog__surface notch notch--br">
    <?php if ($isSoon): ?>
    <div class="card-blog__soon">
      <?= component('hex-tile', ['icon' => 'linkedin-logo', 'size' => 'lg']) ?>
      <<?= $tag ?> class="card-blog__title"><a class="card-blog__link" href="<?= e($href) ?>"<?= $external ? ' rel="noopener"' : '' ?>><?= e($soon['title']) ?></a></<?= $tag ?>>
      <p class="card-blog__text"><?= e($soon['text']) ?></p>
      <span class="card-blog__meta"><span class="card-blog__author"><?= e($soon['label']) ?></span></span>
    </div>
    <?php else: ?>
    <div class="card-blog__media">
      <div class="card-blog__img notch notch--bl">
        <?= img($post['image'], ['alt' => $post['image_alt'], 'ratio' => '16/10', 'sizes' => $sizes, 'cover' => true, 'pattern' => false, 'priority' => !empty($priority)]) ?>
      </div>
      <time class="card-blog__date" datetime="<?= e($post['date']) ?>"><?= e(App\Blog::displayDate($post['date'])) ?></time>
    </div>
    <div class="card-blog__body">
      <<?= $tag ?> class="card-blog__title"><a class="card-blog__link" href="<?= e($href) ?>"><?= e($post['title']) ?></a></<?= $tag ?>>
      <?php if ($featured && ($post['description'] ?? '') !== ''): ?>
      <p class="card-blog__text"><?= e($post['description']) ?></p>
      <?php endif; ?>
      <div class="card-blog__meta">
        <span class="card-blog__author"><?= e($post['author']) ?></span>
        <?php if ($featured && !empty($post['reading_time'])): ?><span class="card-blog__read"><?= icon('clock', 'regular', ['size' => 16]) ?><?= e(str_replace('{n}', (string) $post['reading_time'], (string) insights('read'))) ?></span><?php endif; ?>
        <?php if ($post['category'] !== ''): ?><?= component('chip', ['label' => $post['category'], 'variant' => 'outline']) ?><?php endif; ?>
        <?php if (!empty($post['draft']) && !is_production()): ?><?= component('chip', ['label' => 'Draft', 'variant' => 'solid-bid', 'class' => 'card-blog__draft']) ?><?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <span class="card-blog__arrow" aria-hidden="true"><?= icon($external ? 'arrow-up-right' : 'arrow-right', 'regular') ?></span>
</article>
