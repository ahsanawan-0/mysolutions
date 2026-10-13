<?php
declare(strict_types=1);
/**
 * Dev stub, local only: shown for a planned route whose template hasn't been built yet.
 * @var array<string,mixed> $route
 * @var array<string,string> $params
 * @var string $path
 * @var string $template
 */
$page = [
    'title'      => 'Not built yet: ' . $path . ' | My Solutionz (dev)',
    'canonical'  => false,
    'robots'     => 'noindex, nofollow',
    'status'     => 'draft',
    'body_class' => 'page-stub',
];
?>
<h1>Template not built yet</h1>
<p><strong><?= e($path) ?></strong> is a planned route. Its template <code>templates/<?= e($template) ?>.php</code> is built by <strong><?= e($route['built_by'] ?? 'a later prompt') ?></strong>.</p>
<?php if ($params !== []): ?>
<dl>
<?php foreach ($params as $key => $value): ?>
  <dt><?= e($key) ?></dt><dd><?= e($value) ?></dd>
<?php endforeach; ?>
</dl>
<?php endif; ?>
