<?php
declare(strict_types=1);
/**
 * Home "Our Process, Step by Step" (docs/08 §4 H6, PROMPTS P13): the C15 process-steps component (compact variant,
 * navy surface) with the five approach steps condensed from the client's Our Approach copy and the link to the full
 * approach on the About page. Copy: app/data/home.php → process.
 */
$p = home('process');
?>
<section class="process" id="process" aria-labelledby="process-title">
  <?= component('process-steps', [
      'eyebrow'    => $p['eyebrow'],
      'heading'    => $p['title'],
      'accent'     => $p['accent'],
      'id'         => 'process-title',
      'steps'      => $p['steps'],
      'variant'    => 'compact',
      'cta'        => $p['cta'],
      'class'      => 'on-dark',
      'pin_length' => 0.6,
  ]) ?>
</section>
