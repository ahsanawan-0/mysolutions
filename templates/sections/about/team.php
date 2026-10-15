<?php
declare(strict_types=1);
/**
 * About A6 "Behind Our Expertise" (docs/08 §5 A6, docs/04 §1.4, PROMPTS P19): the C11 team cards slider. The section
 * is absent while app/data/team.php is empty (real people with permission only, docs/15 Q08). Heading: about('team').
 * @var string $id  anchor id (team)
 */
$members = team();
if (!$members) return;
$t = about('team');
$titleId = $id . '-title';
?>
<section class="section about-team" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="container">
    <header class="about-team__head">
      <?= component('eyebrow', ['text' => $t['eyebrow']]) ?>
      <?= heading(2, $t['title'], $t['accent'], 'about-team__title', $titleId, ['data-anim' => 'two-voice']) ?>
    </header>
  </div>
  <?= component('team-cards', ['members' => $members, 'label' => $t['title'] . ' ' . $t['accent']]) ?>
</section>
