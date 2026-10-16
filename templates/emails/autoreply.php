<?php
declare(strict_types=1);
/**
 * Auto-reply to the sender (HTML part; the text part is emails/autoreply-text.php). PROMPTS P23, docs/13 §8 (the
 * privacy notice link is included). Branded, simple: navy header with the company name, thanks, what happens next, a
 * summary of the enquiry, contact details and the privacy link. Colours from tokens.css via token(); values escaped.
 * @var array  $v        The validated values
 * @var array  $t        contact('emails.autoreply')
 * @var string $service  Service name ('' when none was chosen)
 * @var string $sector   Sector name ('' when none was chosen)
 */
$fill = static fn (string $s): string => strtr($s, [
    '{name}'    => strtok($v['name'], ' ') ?: $v['name'],
    '{phone}'   => (string) config('phone_display'),
    '{email}'   => (string) config('email'),
    '{address}' => config('address.street') . ', ' . config('address.locality') . ' ' . config('address.postcode'),
    '{privacy}' => url('/privacy-policy/'),
]);
$ink = token('ms-ink');
$muted = token('ms-ink-2');
?>
<!doctype html>
<html lang="en-GB">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title><?= e($t['subject']) ?></title></head>
<body style="margin:0;padding:24px;background:<?= e(token('ms-canvas')) ?>;font-family:Arial,Helvetica,sans-serif;color:<?= e($ink) ?>;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:<?= e(token('ms-white')) ?>;border-radius:12px;">
    <tr><td style="padding:24px 28px;background:<?= e(token('navy-900')) ?>;border-radius:12px 12px 0 0;">
      <span style="font-size:20px;font-weight:bold;color:<?= e(token('ms-white')) ?>;"><?= e((string) config('name')) ?></span>
    </td></tr>
    <tr><td style="padding:28px;">
      <h1 style="margin:0 0 16px;font-size:22px;line-height:1.3;color:<?= e($ink) ?>;"><?= e($fill($t['heading'])) ?></h1>
      <?php foreach ($t['paras'] as $para): ?>
      <p style="margin:0 0 14px;font-size:15px;line-height:1.6;"><?= e($fill($para)) ?></p>
      <?php endforeach; ?>
      <?php if ($service !== '' || $sector !== ''): ?>
      <p style="margin:20px 0 6px;font-size:13px;color:<?= e($muted) ?>;text-transform:uppercase;letter-spacing:.08em;"><?= e($t['summary']) ?></p>
      <p style="margin:0 0 14px;font-size:15px;line-height:1.6;"><?= e(implode(' · ', array_filter([$service, $sector]))) ?></p>
      <?php endif; ?>
      <p style="margin:24px 0 0;font-size:15px;line-height:1.6;"><?= e($t['signoff']) ?><br><strong><?= e($t['team']) ?></strong></p>
    </td></tr>
    <tr><td style="padding:18px 28px 24px;border-top:1px solid <?= e(token('ms-line')) ?>;font-size:12px;line-height:1.6;color:<?= e($muted) ?>;">
      <?= e($fill($t['contact'])) ?><br>
      <?= e($fill(str_replace(' {privacy}', '', $t['privacy']))) ?> <a href="<?= e(url('/privacy-policy/')) ?>" style="color:<?= e(token('azure-700')) ?>;"><?= e(url('/privacy-policy/')) ?></a><br>
      <?= e((string) config('legal_name')) ?> · <?= e((string) config('company_number_label', 'Company no.')) ?> <?= e((string) config('company_number')) ?>
    </td></tr>
  </table>
</body>
</html>
