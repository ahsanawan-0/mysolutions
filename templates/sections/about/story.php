<?php
declare(strict_types=1);
/**
 * About A2 "Our Story" (docs/08 §5 A2, PROMPTS P18): eyebrow + two-voice H2 over the azure swirl lines (drawn on
 * scroll), then a sticky image column (≥ 1024; the photos crossfade as the paragraphs are read, modules/about-story.js)
 * beside the client's 8 paragraphs. The sentence "We have never believed in taking a standard approach to every
 * tender." and the last paragraph light up with read-along; the closing statement is set large in the brand blue
 * with a quote mark that draws itself. Copy: about('story'). Below 1024 only the first photo shows, above the text.
 * @var string $id  anchor id (our-story)
 */
$s = about('story');
$titleId = $id . '-title';
$quotePath = 'M116,72v88a48.05,48.05,0,0,1-48,48,8,8,0,0,1,0-16,32,32,0,0,0,32-32v-8H40a16,16,0,0,1-16-16V72A16,16,0,0,1,40,56h60A16,16,0,0,1,116,72ZM216,56H156a16,16,0,0,0-16,16v64a16,16,0,0,0,16,16h60v8a32,32,0,0,1-32,32,8,8,0,0,0,0,16,48.05,48.05,0,0,0,48-48V72A16,16,0,0,0,216,56Z'; // Phosphor quotes (fill)
?>
<section class="section about-story" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light" data-module="about-story">
  <div class="about-story__art" aria-hidden="true"><?= inline_svg('img/art/swirl.svg', ['title' => false, 'attrs' => ['data-anim' => 'draw']]) ?></div>

  <div class="container">
    <header class="about-story__head">
      <?= component('eyebrow', ['text' => $s['eyebrow']]) ?>
      <?= heading(2, $s['title'], $s['accent'], 'about-story__title', $titleId, ['data-anim' => 'two-voice']) ?>
    </header>

    <div class="about-story__grid">
      <div class="about-story__media">
        <div class="about-story__frame">
          <?php foreach ($s['images'] as $i => $image): ?>
          <div class="about-story__img<?= $i === 0 ? ' is-active' : '' ?>" data-story-img="<?= $i ?>">
            <?= img($image['key'], ['alt' => $image['alt'], 'ratio' => '4/5', 'sizes' => '(min-width: 1024px) 38vw, 100vw', 'cover' => true, 'pattern' => false]) ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="about-story__text">
        <?php foreach ($s['paras'] as $para):
            $read = $para['read'] ?? false;
            $text = $para['text'];
            $step = ' data-story-step="' . (int) $para['image'] . '"';
            if ($read === true): ?>
        <p class="about-story__para about-story__para--read"<?= $step ?> data-anim="read-along"><?= e($text) ?></p>
        <?php elseif (is_string($read) && str_starts_with($text, $read)): ?>
        <p class="about-story__para"<?= $step ?>><span class="about-story__read" data-anim="read-along"><?= e($read) ?></span><?= e(substr($text, strlen($read))) ?></p>
        <?php else: ?>
        <p class="about-story__para"<?= $step ?>><?= e($text) ?></p>
        <?php endif; endforeach; ?>

        <div class="about-story__statement"<?= ' data-story-step="' . (int) (count($s['images']) - 1) . '"' ?>>
          <svg class="about-story__mark" viewBox="0 0 256 256" aria-hidden="true" focusable="false" data-anim="draw"><path d="<?= e($quotePath) ?>"/></svg>
          <p class="about-story__statement-text"><?= e($s['statement']) ?></p>
        </div>
      </div>
    </div>
  </div>
</section>
