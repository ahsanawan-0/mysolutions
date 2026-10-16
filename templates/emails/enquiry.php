<?php
declare(strict_types=1);
/**
 * Enquiry email to the office (HTML part; the text part is emails/enquiry-text.php). PROMPTS P23. Simple table layout
 * with inline styles (email clients); colours come from src/css/tokens.css via token(). Every value is escaped.
 * @var array  $v        The validated values (ContactController::validate)
 * @var array  $t        contact('emails.enquiry')
 * @var string $service  Service name ('' when none was chosen)
 * @var string $sector   Sector name ('' when none was chosen)
 */
$rows = [
    $t['labels']['name']      => $v['name'],
    $t['labels']['company']   => $v['company'],
    $t['labels']['email']     => $v['email'],
    $t['labels']['phone']     => $v['phone'],
    $t['labels']['sector']    => $sector,
    $t['labels']['service']   => $service,
    $t['labels']['deadline']  => $v['deadline'] !== '' ? (new DateTimeImmutable($v['deadline']))->format('j F Y') : '',
    $t['labels']['callback']  => $v['callback'] !== '' ? (string) contact('form.options.callback.' . $v['callback']) : '',
    $t['labels']['marketing'] => $v['marketing'] ? $t['marketing']['yes'] : $t['marketing']['no'],
];
$ink = token('ms-ink');
$muted = token('ms-ink-2');
$line = token('ms-line');
?>
<!doctype html>
<html lang="en-GB">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title><?= e($t['heading']) ?></title></head>
<body style="margin:0;padding:24px;background:<?= e(token('ms-canvas')) ?>;font-family:Arial,Helvetica,sans-serif;color:<?= e($ink) ?>;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:<?= e(token('ms-white')) ?>;border-radius:12px;">
    <tr><td style="padding:24px 28px;background:<?= e(token('navy-900')) ?>;border-radius:12px 12px 0 0;color:<?= e(token('ms-white')) ?>;font-size:18px;font-weight:bold;"><?= e($t['heading']) ?></td></tr>
    <tr><td style="padding:20px 28px;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.5;">
        <?php foreach ($rows as $label => $value): ?>
        <?php if ($value === '') continue; ?>
        <tr>
          <td style="padding:8px 12px 8px 0;border-bottom:1px solid <?= e($line) ?>;color:<?= e($muted) ?>;width:38%;vertical-align:top;"><?= e($label) ?></td>
          <td style="padding:8px 0;border-bottom:1px solid <?= e($line) ?>;vertical-align:top;"><?= e($value) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
      <p style="margin:20px 0 8px;font-size:14px;color:<?= e($muted) ?>;"><?= e($t['labels']['message']) ?></p>
      <p style="margin:0;font-size:14px;line-height:1.6;white-space:pre-wrap;"><?= nl2br(e($v['message'])) ?></p>
    </td></tr>
    <tr><td style="padding:16px 28px 24px;font-size:12px;color:<?= e($muted) ?>;"><?= e(strtr($t['footer'], ['{site}' => (string) config('url')])) ?></td></tr>
  </table>
</body>
</html>
