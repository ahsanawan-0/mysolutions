<?php
declare(strict_types=1);
/**
 * Small "Draft" ribbon shown on non-production environments for pages with status draft,
 * listing any SEO contract warnings from App\Seo. Never rendered in production.
 * @var array<string,mixed> $page
 */
$warnings = $page['warnings'] ?? [];
?>
<aside class="draft-ribbon" aria-label="Draft page notice">
  <strong class="draft-ribbon__badge">Draft</strong>
  <span class="draft-ribbon__text">noindex · not in sitemap<?= $warnings !== [] ? ' · ' . count($warnings) . ' SEO warning' . (count($warnings) === 1 ? '' : 's') : '' ?></span>
  <?php if ($warnings !== []): ?>
  <ul class="draft-ribbon__list">
    <?php foreach ($warnings as $w): ?><li><?= e($w) ?></li><?php endforeach; ?>
  </ul>
  <?php endif; ?>
</aside>
