<?php
declare(strict_types=1);
/**
 * Sector "Typical buyers and contract types" (PROMPTS P22, docs/08 §8; to be confirmed with the client): the items as
 * chips. Data: sector_page(<slug>)['buyers'] = string[] once the client has confirmed them (a [[PENDING]] string until
 * then: the template shows the dashed pending block off production and omits it on production).
 * @var array $items  string[] buyers / contract types
 */
$t = ui('sector_page.buyers');
?>
<section class="sector-buyers" id="typical-buyers" aria-labelledby="typical-buyers-title" data-header-theme="light">
  <div class="container sector-buyers__inner">
    <div class="sector-buyers__head">
      <?= component('eyebrow', ['text' => $t['eyebrow']]) ?>
      <?= heading(2, $t['heading'], $t['accent'], 'sector-buyers__title', 'typical-buyers-title', ['data-anim' => 'two-voice']) ?>
    </div>
    <ul class="sector-buyers__chips" role="list" data-anim="stagger">
      <?php foreach ($items as $item): ?>
      <li><?= component('chip', ['label' => (string) $item, 'variant' => 'outline', 'icon' => 'hexagon']) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
