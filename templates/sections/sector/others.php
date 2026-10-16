<?php
declare(strict_types=1);
/**
 * "Other sectors we serve" (PROMPTS P22): a chip row linking to the other sector pages (hub-and-spoke internal links,
 * docs/10 §3). Drafts are skipped on production by the template.
 * @var array $sectors  sectors.php entries (the current one already removed)
 */
if (($sectors ?? []) === []) {
    return;
}
?>
<nav class="sector-others" aria-labelledby="sector-others-title" data-header-theme="light">
  <div class="container sector-others__inner">
    <h2 class="sector-others__title" id="sector-others-title"><?= e(ui('sector_page.other')) ?></h2>
    <ul class="sector-others__chips" role="list">
      <?php foreach ($sectors as $s): ?>
      <li>
        <a class="chip chip--outline sector-others__chip" href="<?= e(url($s['url'])) ?>"><?= icon($s['icon'], 'light', ['size' => 18]) ?><span><?= e($s['name']) ?></span></a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>
