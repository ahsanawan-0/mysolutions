<?php
declare(strict_types=1);
/**
 * C24 consent banner + preferences dialog (PROMPTS P28, docs/13 §2, docs/03 C24). Printed by the base layout only
 * when something non-essential is configured (App\Consent::needsBanner(); with ANALYTICS_PROVIDER=none there is no
 * banner and no consent script). Behaviour: src/js/modules/consent.js (data-module="consent").
 *  - Banner: a non-blocking glass sheet bottom-left (max 560px; full width on small screens), hidden in the HTML and
 *    shown by the script when there is no valid choice. Mode 'consent': "Accept all", "Reject all", "Manage
 *    preferences", three equally prominent buttons. Mode 'notice' (exempt statistics only): "OK", "Opt out", "Manage
 *    preferences".
 *  - Dialog: a native modal <dialog> (focus kept inside, Esc closes, focus returns), one section per category with
 *    its switch (necessary: "Always on"; the others off by default, or on in notice mode) and its technologies.
 *  - Footer "Cookie settings" links ([data-cookie-settings]) open the dialog; without JS they go to the cookie policy.
 * Copy: ui('consent'); categories and technologies: app/config/cookies.php.
 */
use App\Consent;

if (!Consent::needsBanner()) {
    return;
}
$c = ui('consent');
$d = $c['dialog'];
$mode = Consent::mode();
$categories = Consent::categories();
$button = static fn (string $label, string $attr): string => '<button class="consent-btn" type="button" ' . $attr . '>' . e($label) . '</button>';
?>
<div class="consent" data-module="consent" data-consent="<?= e((string) json_encode(Consent::client(), JSON_UNESCAPED_SLASHES)) ?>">
  <section class="consent-banner" role="region" aria-labelledby="consent-title" tabindex="-1" data-consent-banner hidden>
    <h2 class="consent-banner__title" id="consent-title"><?= icon('cookie', 'regular', ['size' => 22]) ?><span><?= e($c['title']) ?></span></h2>
    <p class="consent-banner__text"><?= e($mode === 'notice' ? $c['notice'] : $c['text']) ?> <?= rich($c['policy']) ?></p>
    <div class="consent-banner__actions">
      <?= $button($mode === 'notice' ? $c['ok'] : $c['accept'], 'data-consent-accept') ?>
      <?= $button($mode === 'notice' ? $c['opt_out'] : $c['reject'], 'data-consent-reject') ?>
      <?= $button($c['manage'], 'data-consent-manage aria-haspopup="dialog"') ?>
    </div>
  </section>

  <dialog class="consent-dialog" aria-labelledby="consent-dialog-title" data-consent-dialog>
    <div class="consent-dialog__inner">
      <div class="consent-dialog__head">
        <h2 class="consent-dialog__title" id="consent-dialog-title"><?= e($d['title']) ?></h2>
        <button class="consent-dialog__close" type="button" data-consent-close>
          <?= icon('x', 'regular', ['size' => 20]) ?><span class="visually-hidden"><?= e($d['close']) ?></span>
        </button>
      </div>
      <p class="consent-dialog__intro"><?= e($d['intro']) ?> <?= rich($c['policy']) ?></p>

      <?php foreach ($categories as $key => $cat): $title = 'consent-cat-' . $key; ?>
      <section class="consent-cat" aria-labelledby="<?= e($title) ?>">
        <div class="consent-cat__head">
          <h3 class="consent-cat__title" id="<?= e($title) ?>"><?= e($cat['label']) ?></h3>
          <?php if ($cat['required']): ?>
          <span class="consent-cat__always"><?= icon('check', 'regular', ['size' => 16]) ?><?= e($d['always']) ?></span>
          <?php else: ?>
          <label class="consent-switch">
            <input class="consent-switch__input" type="checkbox" role="switch" data-consent-toggle="<?= e($key) ?>" aria-labelledby="<?= e($title) ?>" aria-describedby="<?= e($title) ?>-desc">
            <span class="consent-switch__track" aria-hidden="true"><span class="consent-switch__thumb"></span></span>
          </label>
          <?php endif; ?>
        </div>
        <p class="consent-cat__desc" id="<?= e($title) ?>-desc"><?= e($cat['description']) ?><?php if (!$cat['required'] && $mode === 'notice'): ?> <?= e($d['exempt']) ?><?php endif; ?></p>
        <details class="consent-cat__details">
          <summary><?= e($d['details']) ?> (<?= count($cat['items']) ?>)<?= icon('caret-down', 'regular', ['size' => 16, 'class' => 'consent-cat__caret']) ?></summary>
          <ul class="consent-tech" role="list">
            <?php foreach ($cat['items'] as $tech): ?>
            <li class="consent-tech__item">
              <p class="consent-tech__name"><strong><?= e($tech['name']) ?></strong> <span><?= e($tech['type']) ?> · <?= e($tech['provider']) ?></span></p>
              <p class="consent-tech__purpose"><?= e($tech['purpose']) ?> <span class="consent-tech__duration"><?= e($d['columns'][3]) ?>: <?= e($tech['duration']) ?>.</span></p>
            </li>
            <?php endforeach; ?>
          </ul>
        </details>
      </section>
      <?php endforeach; ?>

      <div class="consent-dialog__actions">
        <?= $button($mode === 'notice' ? $c['opt_out'] : $c['reject'], 'data-consent-reject') ?>
        <?= $button($c['accept'], 'data-consent-accept') ?>
        <?= $button($d['save'], 'data-consent-save') ?>
      </div>
    </div>
  </dialog>

  <p class="visually-hidden" role="status" data-consent-status data-saved="<?= e($c['saved']) ?>"></p>
</div>
