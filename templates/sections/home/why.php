<?php
declare(strict_types=1);
/**
 * Home "Why Work With Us" (docs/08 §4 H7, docs/04 §5 Vencap bento + §8 logo assembly, PROMPTS P14): header, then a
 * 4-column bento of the seven client-approved reasons around the signature centre tile (2 × 2, Navy 900) where the
 * three pieces of the logo mark fly in and assemble as you scroll (`logo-assembly`, scrubbed) above three counters
 * whose numbers come from config() and the data files. Tiles `stagger` in; the seventh is the Azure gradient tile.
 * Copy: app/data/home.php → why.
 */
$w = home('why');
$years = (int) preg_replace('/\D/', '', config('experience'));                        // "over 10 years …" → 10
$sectorCount = count(sectors());
$serviceCount = array_sum(array_map(static fn (array $c): int => count($c['subs']), services()));
$stats = [
    ['to' => $years,        'suffix' => '+', 'label' => $w['stats']['years']],
    ['to' => $sectorCount,  'suffix' => '',  'label' => $w['stats']['sectors']],
    ['to' => $serviceCount, 'suffix' => '',  'label' => $w['stats']['services']],
];
$tiles = $w['tiles'];
?>
<section class="section why" id="why" aria-labelledby="why-title" data-header-theme="light">
  <div class="container">
    <header class="why__head">
      <?= component('eyebrow', ['text' => $w['eyebrow']]) ?>
      <?= heading(2, $w['title'], $w['accent'], 'why__title', 'why-title', ['data-anim' => 'two-voice']) ?>
      <p class="why__lead lead" data-anim="fade-up"><?= e($w['lead']) ?></p>
    </header>

    <div class="why__grid" data-anim="stagger" data-stagger="0.06">
      <?php foreach ($tiles as $i => $tile): ?>
      <?php if ($i === 1): ?>
      <div class="why__tile why__tile--centre on-dark" data-assembly-scope>
        <div class="why__mark-wrap" aria-hidden="true">
          <?= inline_svg('brand/logo-mark.svg', ['prefix' => 'why', 'class' => 'why__mark', 'title' => false, 'attrs' => ['data-anim' => 'logo-assembly']]) ?>
        </div>
        <dl class="why__stats">
          <?php foreach ($stats as $stat): ?>
          <div class="why__stat">
            <dt class="why__stat-n"><span data-anim="counter" data-to="<?= (int) $stat['to'] ?>" data-suffix="<?= e($stat['suffix']) ?>"><?= (int) $stat['to'] ?><?= e($stat['suffix']) ?></span></dt>
            <dd class="why__stat-label"><?= e($stat['label']) ?></dd>
          </div>
          <?php endforeach; ?>
        </dl>
      </div>
      <?php endif; ?>
      <article class="why__tile why__tile--<?= $i + 1 ?><?= $i === 6 ? ' why__tile--accent on-dark' : '' ?> hex-tile-trigger">
        <?= component('hex-tile', ['icon' => $tile['icon'], 'size' => 'sm']) ?>
        <h3 class="why__tile-title"><?= e($tile['title']) ?></h3>
        <p class="why__tile-text"><?= e($tile['text']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
