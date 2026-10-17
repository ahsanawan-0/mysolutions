<?php
declare(strict_types=1);
/**
 * Quote form `#quote` (PROMPTS P23, docs/08 §11, docs/12 §5). Posts to /contact-us/ (App\Controllers\ContactController),
 * which re-renders this section with the values kept, an error summary (focused, links to the fields) and inline
 * messages (`aria-invalid` + `aria-describedby`), or a notice (rate limit, send failure). Works fully without JS: the
 * selects are native (C31 enhances them), the counter and focus handling are src/js/modules/form.js.
 * Hidden fields: honeypot `website` (people never see it), `ts` (signed render time, ≥ 3 s), `_token` (CSRF).
 * P24: `$prefill` (ContactController::prefill(), plain GETs only) pre-selects the sector / service and prints the
 * dismissible "You selected: …" chip (its × is a link to the bare form, so clearing works without JS too). The
 * <template>s feed form.js, which mirrors the server rules inline (on blur) and in an error summary on submit, shows
 * the logo-mark breathing loop on the submit button (.is-loading, P07) and blocks a second submit.
 * Copy: contact('form'). Styles: src/css/components/form.css + src/css/pages/contact.css.
 * @var array      $form     ['values' => [field => value], 'errors' => [field => message], 'notice' => ?string]
 * @var array|null $prefill  ['values' => [sector, service], 'chip' => ['label' => …, 'items' => [names]]]
 */
use App\Controllers\ContactController;
use App\Csrf;

$t = contact('form');
$values = $form['values'] ?? ($prefill['values'] ?? []);
$chip = $prefill['chip'] ?? null;
$errors = $form['errors'] ?? [];
$notice = $form['notice'] ?? null;
$value = static fn (string $key): string => (string) ($values[$key] ?? '');
$sprite = asset('icons/sprite.svg');
$turnstile = ContactController::turnstileEnabled();

// id, aria-invalid and aria-describedby for a field (hint first, then the error)
$attrs = static function (string $key) use ($errors, $t): string {
    $ids = [];
    if (isset($t['hints'][$key])) $ids[] = 'f-' . $key . '-hint';
    if (isset($errors[$key])) $ids[] = 'f-' . $key . '-error';
    return 'id="f-' . e($key) . '" name="' . e($key) . '"'
        . (isset($errors[$key]) ? ' aria-invalid="true"' : '')
        . ($ids ? ' aria-describedby="' . e(implode(' ', $ids)) . '"' : '');
};
$label = static function (string $key, bool $required = false) use ($t): string {
    return '<label class="field__label" for="f-' . e($key) . '">' . e($t['labels'][$key])
        . ($required ? ' <span class="field__req" aria-hidden="true">*</span>' : '') . '</label>';
};
$help = static function (string $key) use ($errors, $t): string {
    $out = '';
    if (isset($t['hints'][$key])) {
        $out .= '<p class="field__hint" id="f-' . e($key) . '-hint">' . e($t['hints'][$key]) . '</p>';
    }
    if (isset($errors[$key])) {
        $out .= '<p class="field__error" id="f-' . e($key) . '-error">' . icon('warning-circle', 'regular', ['size' => 16]) . '<span><span class="visually-hidden">Error: </span>' . e($errors[$key]) . '</span></p>';
    }
    return $out;
};
$cls = static fn (string $key, string $extra = ''): string => 'field' . ($extra !== '' ? ' ' . $extra : '') . (isset($errors[$key]) ? ' field--error' : '');
$direct = strtr(e($t['direct']), [
    '{phone}' => '<a href="tel:' . e((string) config('phone_e164')) . '">' . e((string) config('phone_display')) . '</a>',
    '{email}' => '<a href="mailto:' . e((string) config('email')) . '">' . e((string) config('email')) . '</a>',
]);
?>
<section class="contact-form" id="quote" aria-labelledby="quote-title" data-header-theme="light">
  <div class="container contact-form__inner">
    <div class="contact-form__aside">
      <?= component('eyebrow', ['text' => $t['eyebrow']]) ?>
      <?= heading(2, $t['heading'], $t['accent'], 'contact-form__title', 'quote-title', ['data-anim' => 'two-voice']) ?>
      <p class="contact-form__intro"><?= e($t['intro']) ?></p>
      <p class="contact-form__direct"><?= $direct ?></p>
    </div>

    <div class="contact-form__panel">
      <?php if ($chip !== null): ?>
      <div class="prefill" data-prefill>
        <p class="prefill__text"><?= icon('check-circle', 'regular', ['size' => 18, 'class' => 'prefill__icon']) ?><span><span class="prefill__label"><?= e($chip['label']) ?></span> <strong class="prefill__items"><?= e(implode(' · ', $chip['items'])) ?></strong></span></p>
        <a class="prefill__clear" href="<?= e(url('/contact-us/')) ?>#quote" aria-label="<?= e($t['prefill']['clear']) ?>" data-prefill-clear><?= icon('x', 'regular', ['size' => 16]) ?></a>
      </div>
      <?php endif; ?>
      <p class="visually-hidden" role="status" data-form-status></p>
      <form class="form" action="<?= e(url('/contact-us/')) ?>#quote" method="post" novalidate data-module="form"
        data-counter="<?= e($t['counter']) ?>" data-sending="<?= e($t['sending']) ?>" data-sending-status="<?= e($t['sending_status']) ?>"
        data-cleared="<?= e($t['prefill']['cleared']) ?>" data-messages="<?= e((string) json_encode($t['errors'], JSON_UNESCAPED_UNICODE)) ?>">
        <?php if ($errors): ?>
        <div class="form__summary" role="alert" tabindex="-1" aria-labelledby="form-summary-title" data-error-summary>
          <h3 class="form__summary-title" id="form-summary-title"><?= icon('warning-circle', 'regular', ['size' => 20]) ?><?= e($t['errors']['summary']) ?></h3>
          <ul class="form__summary-list" role="list" data-summary-list>
            <?php foreach ($errors as $key => $message): ?>
            <li><a href="#f-<?= e($key === 'turnstile' ? 'message' : $key) ?>"><?= e($message) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php elseif ($notice !== null): ?>
        <div class="form__notice" role="alert" tabindex="-1" data-error-summary>
          <?= icon('info', 'regular', ['size' => 20]) ?>
          <p><?= e($notice) ?></p>
        </div>
        <?php endif; ?>

        <p class="form__required"><?= e($t['required']) ?></p>

        <div class="form__grid">
          <div class="<?= $cls('name') ?>">
            <?= $label('name', true) ?>
            <input class="field__input" type="text" <?= $attrs('name') ?> value="<?= e($value('name')) ?>" autocomplete="name" maxlength="100" required>
            <?= $help('name') ?>
          </div>

          <div class="<?= $cls('company') ?>">
            <?= $label('company') ?>
            <input class="field__input" type="text" <?= $attrs('company') ?> value="<?= e($value('company')) ?>" autocomplete="organization" maxlength="150">
            <?= $help('company') ?>
          </div>

          <div class="<?= $cls('email') ?>">
            <?= $label('email', true) ?>
            <input class="field__input" type="email" <?= $attrs('email') ?> value="<?= e($value('email')) ?>" autocomplete="email" spellcheck="false" maxlength="254" required>
            <?= $help('email') ?>
          </div>

          <div class="<?= $cls('phone') ?>">
            <?= $label('phone') ?>
            <input class="field__input" type="tel" <?= $attrs('phone') ?> value="<?= e($value('phone')) ?>" autocomplete="tel" maxlength="30">
            <?= $help('phone') ?>
          </div>

          <div class="<?= $cls('sector') ?>" data-select-anchor>
            <?= $label('sector') ?>
            <span class="field__control select" data-module="select" data-sprite="<?= e($sprite) ?>">
              <select class="field__input field__select" <?= $attrs('sector') ?>>
                <option value="" data-icon="squares-four"><?= e($t['options']['sector_any']) ?></option>
                <?php foreach (sectors() as $sector): ?>
                <option value="<?= e($sector['slug']) ?>" data-icon="<?= e($sector['icon']) ?>"<?= $value('sector') === $sector['slug'] ? ' selected' : '' ?>><?= e($sector['name']) ?></option>
                <?php endforeach; ?>
                <option value="other" data-icon="hexagon"<?= $value('sector') === 'other' ? ' selected' : '' ?>><?= e($t['options']['sector_other']) ?></option>
              </select>
              <?= icon('caret-down', 'regular', ['size' => 16, 'class' => 'field__caret']) ?>
            </span>
            <?= $help('sector') ?>
          </div>

          <div class="<?= $cls('service') ?>" data-select-anchor>
            <?= $label('service') ?>
            <span class="field__control select" data-module="select" data-sprite="<?= e($sprite) ?>">
              <select class="field__input field__select" <?= $attrs('service') ?>>
                <option value="" data-icon="squares-four"><?= e($t['options']['service_any']) ?></option>
                <?php foreach (services() as $category): ?>
                <optgroup label="<?= e($category['name']) ?>" data-icon="<?= e($category['icon']) ?>" data-colour="<?= e($category['colour']) ?>">
                  <?php foreach ($category['subs'] as $sub): ?>
                  <option value="<?= e($sub['slug']) ?>" data-icon="<?= e($sub['icon']) ?>"<?= $value('service') === $sub['slug'] ? ' selected' : '' ?>><?= e($sub['name']) ?></option>
                  <?php endforeach; ?>
                </optgroup>
                <?php endforeach; ?>
              </select>
              <?= icon('caret-down', 'regular', ['size' => 16, 'class' => 'field__caret']) ?>
            </span>
            <?= $help('service') ?>
          </div>

          <div class="<?= $cls('deadline') ?>">
            <?= $label('deadline') ?>
            <input class="field__input field__date" type="date" <?= $attrs('deadline') ?> value="<?= e($value('deadline')) ?>" min="<?= e(date('Y-m-d')) ?>">
            <?= $help('deadline') ?>
          </div>

          <div class="<?= $cls('callback') ?>" data-select-anchor>
            <?= $label('callback') ?>
            <span class="field__control select" data-module="select" data-sprite="<?= e($sprite) ?>">
              <select class="field__input field__select" <?= $attrs('callback') ?>>
                <?php foreach ($t['options']['callback'] as $key => $text): ?>
                <option value="<?= e((string) $key) ?>" data-icon="<?= $key === '' ? 'clock' : 'phone' ?>"<?= $value('callback') === (string) $key ? ' selected' : '' ?>><?= e($text) ?></option>
                <?php endforeach; ?>
              </select>
              <?= icon('caret-down', 'regular', ['size' => 16, 'class' => 'field__caret']) ?>
            </span>
            <?= $help('callback') ?>
          </div>

          <div class="<?= $cls('message', 'field--full') ?>">
            <?= $label('message', true) ?>
            <textarea class="field__input field__textarea" <?= $attrs('message') ?> rows="6" minlength="10" maxlength="2000" required data-char-counter><?= e($value('message')) ?></textarea>
            <?= $help('message') ?>
            <p class="field__counter" aria-live="polite" hidden data-char-count></p>
          </div>

          <div class="field field--full field--check">
            <input class="field__checkbox" type="checkbox" id="f-marketing" name="marketing" value="1"<?= !empty($values['marketing']) ? ' checked' : '' ?>>
            <label class="field__check-label" for="f-marketing"><?= e($t['labels']['marketing']) ?></label>
          </div>
        </div>

        <!-- Honeypot: hidden from people and assistive technology; bots that fill it are silently dropped -->
        <div class="form__hp" aria-hidden="true">
          <label for="f-website"><?= e($t['labels']['website']) ?></label>
          <input type="text" id="f-website" name="website" value="" tabindex="-1" autocomplete="off">
        </div>
        <input type="hidden" name="ts" value="<?= e(Csrf::stamp()) ?>">
        <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">

        <?php if ($turnstile): ?>
        <div class="form__turnstile cf-turnstile" data-sitekey="<?= e((string) env('TURNSTILE_SITE_KEY', '')) ?>"></div>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        <?php endif; ?>

        <div class="form__footer">
          <p class="form__privacy"><?= rich($t['privacy']) ?></p>
          <button class="btn btn--lg form__submit" type="submit" data-submit>
            <span class="form__submit-mark" aria-hidden="true"><?= inline_svg('brand/logo-mark-white.svg', ['prefix' => 'fsm', 'title' => false]) ?></span>
            <span data-submit-label><?= e($t['submit']) ?></span><?= icon('arrow-right', 'regular', ['class' => 'form__submit-icon']) ?>
          </button>
        </div>

        <!-- form.js builds client-side errors from these, so they match the server's markup exactly -->
        <template data-error-template>
          <p class="field__error"><?= icon('warning-circle', 'regular', ['size' => 16]) ?><span><span class="visually-hidden">Error: </span><span data-error-text></span></span></p>
        </template>
        <template data-summary-template>
          <div class="form__summary" role="alert" tabindex="-1" aria-labelledby="form-summary-title" data-error-summary>
            <h3 class="form__summary-title" id="form-summary-title"><?= icon('warning-circle', 'regular', ['size' => 20]) ?><?= e($t['errors']['summary']) ?></h3>
            <ul class="form__summary-list" role="list" data-summary-list></ul>
          </div>
        </template>
      </form>
    </div>
  </div>
</section>
