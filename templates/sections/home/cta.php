<?php
declare(strict_types=1);
/**
 * Home final CTA band (docs/08 §4 H12, PROMPTS P16): the C21 band with its default copy (app/data/ui.php →
 * cta_band), the soft glows and the rising, rotating logo mark (`cta-mark`). Per-page overrides live in
 * app/data/home.php → cta (C01 may add them).
 */
$c = home('cta');
?>
<?= component('cta-band', ['show_whatsapp' => !empty($c['show_whatsapp'])] + array_intersect_key($c, array_flip(['eyebrow', 'heading', 'accent', 'text', 'primary', 'secondary']))) ?>
