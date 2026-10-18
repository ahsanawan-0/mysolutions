<?php
declare(strict_types=1);
/**
 * C43 Share links (PROMPTS P26, docs/08 §9): LinkedIn, Facebook, X and Email as plain share URLs. No third-party
 * scripts, widgets or tracking pixels; the networks open in a new tab (announced), Email is a mailto: link.
 * 44px round icon buttons with accessible names ("Share on LinkedIn (opens in a new tab)").
 * @var string      $url    Absolute URL of the page to share
 * @var string      $title  Its title
 * @var string|null $label  Group heading (default insights('article.share'))
 * @var string|null $class
 */
$copy = insights('article');
$u = rawurlencode($url);
$t = rawurlencode($title);
$networks = [
    ['LinkedIn', 'linkedin-logo', 'https://www.linkedin.com/sharing/share-offsite/?url=' . $u],
    ['Facebook', 'facebook-logo', 'https://www.facebook.com/sharer/sharer.php?u=' . $u],
    ['X', 'x-logo', 'https://x.com/intent/post?url=' . $u . '&text=' . $t],
    ['Email', 'envelope-simple', 'mailto:?subject=' . $t . '&body=' . rawurlencode(strtr($copy['email_body'], ['{title}' => $title, '{url}' => $url]))],
];
$id = 'share-' . substr(md5($url), 0, 6);
?>
<div class="share<?= isset($class) ? ' ' . e($class) : '' ?>" role="group" aria-labelledby="<?= e($id) ?>">
  <p class="share__label" id="<?= e($id) ?>"><?= e($label ?? $copy['share']) ?></p>
  <ul class="share__list" role="list">
    <?php foreach ($networks as [$name, $iconName, $href]): $mail = str_starts_with($href, 'mailto:'); ?>
    <li>
      <a class="share__link share__link--<?= e(strtolower($name)) ?>" href="<?= e($href) ?>"<?= $mail ? '' : ' target="_blank" rel="noopener noreferrer"' ?>>
        <?= icon($iconName, 'regular', ['size' => 20]) ?>
        <span class="visually-hidden"><?= $mail ? e($copy['share_email']) : e(strtr($copy['share_on'], ['{network}' => $name]) . ' ' . $copy['new_tab']) ?></span>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
