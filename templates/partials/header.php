<?php
declare(strict_types=1);
/**
 * Site header (C04–C06). Two variants:
 *   default (inner pages, printed by the base layout): logo · glass pill nav with sliding active pill · phone · CTA · burger.
 *   hero    (home only, rendered inside the hero panel by P08): logo · centred links with an active dot · phone · glass CTA.
 * Everything in the menus comes from app/data/{navigation,services,sectors}.php.
 * Also prints the mobile full-screen sheet and the mobile sticky action bar (once, with the default/hero header).
 *
 * @var string|null $variant  'default' | 'hero'
 * @var string|null $theme    initial colour theme under the header: 'dark' (over a photo/navy hero) | 'light'
 * @var bool|null   $follow   P16: a second, fixed `default` header printed on pages whose hero renders the `hero`
 *                            variant. It stays hidden until the hero's own nav has scrolled out, then behaves like the
 *                            sticky header everywhere else (compact glass, hide on scroll down, theme from the
 *                            sections). Prints the <header> only (the mobile sheet and action bar come from the
 *                            hero's header) and namespaces its logo ids with `hf-`.
 * @var array|null  $quote   P24: the page's pre-fill context (['service' => slug] | ['sector' => slug], from
 *                            $page['quote']) for every "Get a Free Quote" link here (quote_url()).
 */
$variant = $variant ?? 'default';
$theme = $theme ?? 'light';
$nav = navigation();
$services = services();
$sectors = sectors();
$current = current_path();
$isCurrent = static fn (array $item): bool => !empty($item['exact']) ? $current === $item['url'] : is_active($item['url']);
$quoteUrl = url(quote_url($quote ?? []));
$follow = !empty($follow) && $variant === 'default';
$logoPrefix = $follow ? 'hf' : 'hl';
$menuId = static fn (string $menu): string => ($follow ? 'hf-' : '') . 'menu-' . $menu;   // unique per header: both print on hero pages
?>
<header class="site-header site-header--<?= e($variant) ?><?= $follow ? ' site-header--follow' : '' ?>" data-header="<?= e($variant) ?>" data-theme="<?= e($theme) ?>"<?= $follow ? ' data-follow' : '' ?>>
  <div class="site-header__inner">
    <a class="site-header__logo logo-link" href="<?= e(url('/')) ?>" aria-label="<?= e(config('name')) ?> home">
      <?= logo('colour', ['height' => 40, 'inline' => true, 'prefix' => $logoPrefix, 'class' => 'site-header__logo-svg']) ?>
    </a>

    <nav class="site-nav" aria-label="Primary">
      <ul class="site-nav__list<?= $variant === 'hero' ? ' site-nav__list--dots' : ' glass-nav' ?>" data-nav-list>
        <?php if ($variant === 'default'): ?><li class="site-nav__pill" aria-hidden="true" data-nav-pill></li><?php endif; ?>
        <?php foreach ($nav as $item): $active = $isCurrent($item); $hasMenu = !empty($item['menu']); ?>
        <li class="site-nav__item<?= $hasMenu ? ' has-menu' : '' ?>" <?= $hasMenu ? 'data-menu="' . e($item['menu']) . '"' : '' ?>>
          <a class="site-nav__link<?= $active ? ' is-active' : '' ?>" href="<?= e(url($item['url'])) ?>"<?= $current === $item['url'] ? ' aria-current="page"' : '' ?> data-nav-link><?= e($item['label']) ?></a>
          <?php if ($hasMenu): ?>
          <button class="site-nav__toggle" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="<?= e($menuId($item['menu'])) ?>" data-menu-trigger>
            <?= icon('caret-down', 'regular', ['size' => 16, 'label' => 'Open ' . $item['label'] . ' menu']) ?>
          </button>
          <?php if ($item['menu'] === 'services'): ?>
          <div class="menu menu--mega glass-thick-dark" id="<?= e($menuId('services')) ?>" hidden data-menu-panel>
            <div class="menu__grid">
              <?php foreach ($services as $category): ?>
              <div class="menu__col" data-menu-col>
                <a class="menu__cat" href="<?= e(url($category['url'])) ?>">
                  <?= component('hex-tile', ['icon' => $category['icon'], 'category' => $category['colour'], 'size' => 'sm']) ?>
                  <span class="menu__cat-name"><?= e($category['name']) ?></span>
                </a>
                <ul class="menu__subs">
                  <?php foreach ($category['subs'] as $sub): ?>
                  <li>
                    <a class="menu__sub" href="<?= e(url($sub['url'])) ?>">
                      <?= icon($sub['icon'], 'light', ['size' => 22, 'class' => 'menu__sub-icon']) ?>
                      <span class="menu__sub-text"><span class="menu__sub-name"><?= e($sub['name']) ?></span><span class="menu__sub-summary"><?= e($sub['summary']) ?></span></span>
                    </a>
                  </li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <?php endforeach; ?>
              <div class="menu__col menu__col--featured" data-menu-col>
                <div class="menu__featured">
                  <?= component('eyebrow', ['text' => 'Free quote', 'tag' => 'span']) ?>
                  <p class="menu__featured-title">Have a tender deadline coming up?</p>
                  <p class="menu__featured-text">Tell us about the opportunity and we'll come back within one working day with how we can help.</p>
                  <?= component('button', ['label' => 'Get a Free Quote', 'href' => $quoteUrl, 'size' => 'sm']) ?>
                </div>
                <a class="link-arrow menu__all" href="<?= e(url('/services/')) ?>"><span class="link-arrow__label">View all services</span><?= icon('arrow-right', 'regular') ?></a>
              </div>
            </div>
          </div>
          <?php elseif ($item['menu'] === 'sectors'): ?>
          <div class="menu menu--drop glass-thick-dark" id="<?= e($menuId('sectors')) ?>" hidden data-menu-panel>
            <ul class="menu__list" data-menu-col>
              <?php foreach ($sectors as $sector): ?>
              <li>
                <a class="menu__sub" href="<?= e(url($sector['url'])) ?>">
                  <?= icon($sector['icon'], 'light', ['size' => 22, 'class' => 'menu__sub-icon']) ?>
                  <span class="menu__sub-text"><span class="menu__sub-name"><?= e($sector['name']) ?></span></span>
                </a>
              </li>
              <?php endforeach; ?>
              <li class="menu__list-all"><a class="link-arrow" href="<?= e(url('/sectors/')) ?>"><span class="link-arrow__label">All sectors</span><?= icon('arrow-right', 'regular') ?></a></li>
            </ul>
          </div>
          <?php endif; ?>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="site-header__actions">
      <a class="site-header__phone" href="tel:<?= e(config('phone_e164')) ?>">
        <?= icon('phone', 'regular', ['size' => 20]) ?><span><?= e(config('phone_display')) ?></span>
      </a>
      <?= component('button', ['label' => 'Get a Free Quote', 'href' => $quoteUrl, 'variant' => 'glass', 'size' => 'sm', 'class' => 'site-header__cta']) ?>
      <button class="site-header__burger btn-icon" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu" data-burger>
        <?= icon('list', 'regular', ['size' => 26]) ?>
      </button>
    </div>
  </div>
</header>
<?php if ($follow) return; ?>

<div class="mobile-menu" id="mobile-menu" hidden data-mobile-menu>
  <div class="mobile-menu__bar">
    <a class="mobile-menu__logo" href="<?= e(url('/')) ?>" aria-label="<?= e(config('name')) ?> home"><?= logo('white', ['height' => 36]) ?></a>
    <button class="mobile-menu__close btn-icon" type="button" aria-label="Close menu" data-mobile-close><?= icon('x', 'regular', ['size' => 26]) ?></button>
  </div>
  <nav class="mobile-menu__nav" aria-label="Mobile">
    <ul class="mobile-menu__list">
      <?php foreach ($nav as $item): $active = $isCurrent($item); ?>
      <li class="mobile-menu__item" data-mobile-item>
        <?php if (!empty($item['menu'])): ?>
        <details class="mobile-acc" <?= $active ? 'open' : '' ?>>
          <summary class="mobile-menu__link mobile-acc__summary"><span><?= e($item['label']) ?></span><?= icon('caret-down', 'regular', ['size' => 22, 'class' => 'mobile-acc__caret']) ?></summary>
          <div class="mobile-acc__body">
            <a class="mobile-acc__all" href="<?= e(url($item['url'])) ?>">All <?= e(strtolower($item['label'])) ?></a>
            <?php if ($item['menu'] === 'services'): ?>
              <?php foreach ($services as $category): ?>
              <details class="mobile-acc mobile-acc--nested" <?= is_active($category['url']) ? 'open' : '' ?>>
                <summary class="mobile-acc__summary mobile-acc__summary--cat">
                  <?= icon($category['icon'], 'light', ['size' => 22, 'class' => 'mobile-acc__icon mobile-acc__icon--' . $category['colour']]) ?><span><?= e($category['name']) ?></span><?= icon('caret-down', 'regular', ['size' => 20, 'class' => 'mobile-acc__caret']) ?>
                </summary>
                <ul class="mobile-acc__subs">
                  <li><a href="<?= e(url($category['url'])) ?>">Overview: <?= e($category['short']) ?></a></li>
                  <?php foreach ($category['subs'] as $sub): ?>
                  <li><a href="<?= e(url($sub['url'])) ?>"><?= e($sub['name']) ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </details>
              <?php endforeach; ?>
            <?php else: ?>
              <ul class="mobile-acc__subs">
                <?php foreach ($sectors as $sector): ?>
                <li><a href="<?= e(url($sector['url'])) ?>"><?= icon($sector['icon'], 'light', ['size' => 20]) ?><?= e($sector['name']) ?></a></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </details>
        <?php else: ?>
        <a class="mobile-menu__link<?= $active ? ' is-active' : '' ?>" href="<?= e(url($item['url'])) ?>"<?= $current === $item['url'] ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="mobile-menu__contact" data-mobile-item>
    <?= component('button', ['label' => 'Get a Free Quote', 'href' => $quoteUrl, 'class' => 'btn--block']) ?>
    <ul class="mobile-menu__contact-list">
      <li><a href="tel:<?= e(config('phone_e164')) ?>"><?= icon('phone', 'regular', ['size' => 22]) ?><?= e(config('phone_display')) ?></a></li>
      <li><a href="<?= e(config('whatsapp')) ?>" rel="noopener"><?= icon('whatsapp-logo', 'regular', ['size' => 22]) ?>WhatsApp</a></li>
      <li><a href="mailto:<?= e(config('email')) ?>"><?= icon('envelope-simple', 'regular', ['size' => 22]) ?><?= e(config('email')) ?></a></li>
    </ul>
    <ul class="mobile-menu__socials" aria-label="Social media">
      <li><a class="btn-icon" href="<?= e(config('socials.linkedin')) ?>" rel="noopener"><?= icon('linkedin-logo', 'regular', ['size' => 24, 'label' => 'LinkedIn']) ?></a></li>
      <li><a class="btn-icon" href="<?= e(config('socials.instagram')) ?>" rel="noopener"><?= icon('instagram-logo', 'regular', ['size' => 24, 'label' => 'Instagram']) ?></a></li>
      <li><a class="btn-icon" href="<?= e(config('socials.facebook')) ?>" rel="noopener"><?= icon('facebook-logo', 'regular', ['size' => 24, 'label' => 'Facebook']) ?></a></li>
    </ul>
  </div>
</div>

<nav class="action-bar" aria-label="Quick contact" data-action-bar>
  <a class="action-bar__item" href="tel:<?= e(config('phone_e164')) ?>"><?= icon('phone', 'regular', ['size' => 22]) ?><span>Call</span></a>
  <a class="action-bar__item" href="<?= e(config('whatsapp')) ?>" rel="noopener"><?= icon('whatsapp-logo', 'regular', ['size' => 22]) ?><span>WhatsApp</span></a>
  <a class="action-bar__item action-bar__item--primary" href="<?= e($quoteUrl) ?>"><?= icon('arrow-up-right', 'regular', ['size' => 22]) ?><span>Get a Quote</span></a>
</nav>
