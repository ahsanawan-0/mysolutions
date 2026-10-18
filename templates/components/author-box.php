<?php
declare(strict_types=1);
/**
 * C42 Author box (PROMPTS P26, docs/10 §10 E-E-A-T): who wrote the article. A Person shows their photo, name, job
 * title, bio and LinkedIn; an Organization (the `team` fallback) shows the logo mark in a hex tile, the team name, its
 * `role` line, bio, the company LinkedIn and an "About us" link. Data: content/authors.php via App\Blog::author().
 * @var array       $author  App\Blog::author() entry (name, type, role|jobTitle, bio, photo?, url?, sameAs[]?)
 * @var string|null $id      Heading id (default 'author-box-title')
 * @var string|null $class
 */
$copy = insights('article');
$id = $id ?? 'author-box-title';
$isPerson = ($author['type'] ?? 'Organization') === 'Person';
$role = (string) ($isPerson ? ($author['jobTitle'] ?? '') : ($author['role'] ?? ''));
$linkedin = null;
foreach ((array) ($author['sameAs'] ?? []) as $profile) {
    if (is_string($profile) && str_contains($profile, 'linkedin.com/')) {
        $linkedin = $profile;
        break;
    }
}
?>
<section class="author-box<?= isset($class) ? ' ' . e($class) : '' ?>" aria-labelledby="<?= e($id) ?>">
  <div class="author-box__avatar">
    <?php if ($isPerson && !empty($author['photo'])): ?>
    <?= img((string) $author['photo'], ['alt' => '', 'decorative' => true, 'sizes' => '96px', 'ratio' => '1/1', 'pattern' => false]) ?>
    <?php else: ?>
    <span class="author-box__mark"><?= logo('colour', ['lockup' => 'mark', 'height' => 40, 'alt' => '']) ?></span>
    <?php endif; ?>
  </div>
  <div class="author-box__body">
    <h2 class="author-box__eyebrow" id="<?= e($id) ?>"><?= e($copy['author']) ?></h2>
    <p class="author-box__name"><?= e((string) $author['name']) ?></p>
    <?php if ($role !== ''): ?>
    <p class="author-box__role"><?= e($role) ?></p>
    <?php endif; ?>
    <?php if (!empty($author['bio'])): ?>
    <p class="author-box__bio"><?= e((string) $author['bio']) ?></p>
    <?php endif; ?>
    <div class="author-box__links">
      <?php if ($linkedin !== null): ?>
      <a class="author-box__link" href="<?= e($linkedin) ?>" target="_blank" rel="noopener noreferrer">
        <?= icon('linkedin-logo', 'regular', ['size' => 18]) ?><span><?= e($copy['linkedin']) ?></span><span class="visually-hidden"> <?= e($copy['new_tab']) ?></span>
      </a>
      <?php endif; ?>
      <?php if (!$isPerson && !empty($author['url'])): ?>
      <a class="author-box__link" href="<?= e(url((string) $author['url'])) ?>">
        <span><?= e($copy['about']) ?></span><?= icon('arrow-right', 'regular', ['size' => 16]) ?>
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>
