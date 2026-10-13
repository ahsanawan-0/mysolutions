<?php
declare(strict_types=1);
/**
 * C29 Section divider between two sections. Place it between the sections (not inside either).
 * @var string|null $variant  'wave' (default) | 'diagonal' (120° brand angle: edge rises to the right)
 * @var string      $to       Colour token of the NEXT section, e.g. 'navy-900', 'ms-canvas', 'ms-white' (fills the shape)
 * @var string|null $from     Colour token of the PREVIOUS section (background behind the shape); default transparent
 * @var bool|null   $flip     Mirror horizontally
 * @var string|null $class
 * The wave carries data-anim="wave" + data-wave-alt (a second path) for P06's scrubbed MorphSVG.
 */
$variant = $variant ?? 'wave';
$token = static fn (?string $t): string => $t !== null && preg_match('/^[a-z0-9-]+$/', $t) === 1 ? 'var(--' . $t . ')' : 'transparent';
$style = '--divider-fill:' . $token($to ?? null) . ';--divider-from:' . $token($from ?? null) . ';';
$classes = 'divider divider--' . $variant . (!empty($flip) ? ' divider--flip' : '') . (isset($class) ? ' ' . $class : '');
?>
<div class="<?= e($classes) ?>" style="<?= e($style) ?>" aria-hidden="true">
  <?php if ($variant === 'diagonal'): ?>
  <svg viewBox="0 0 1440 96" preserveAspectRatio="none" focusable="false"><polygon points="0,96 1440,0 1440,96"/></svg>
  <?php else: ?>
  <svg viewBox="0 0 1440 96" preserveAspectRatio="none" focusable="false"><path data-anim="wave" data-wave-alt="M0,48 C240,0 480,96 720,48 C960,0 1200,96 1440,48 L1440,96 L0,96 Z" d="M0,48 C240,96 480,0 720,48 C960,96 1200,0 1440,48 L1440,96 L0,96 Z"/></svg>
  <?php endif; ?>
</div>
