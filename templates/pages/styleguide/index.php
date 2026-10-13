<?php
declare(strict_types=1);
/**
 * Styleguide (local only, noindex): colours, type, buttons, icons, foundation components.
 * Colour values are read from src/css/tokens.css so this page never duplicates a hex.
 */
$page = [
    'title'      => 'Styleguide | My Solutionz (dev)',
    'robots'     => 'noindex, nofollow',
    'status'     => 'draft',
    'body_class' => 'page-styleguide',
];

// ---- tokens.css → colour groups --------------------------------------------------------
$tokensCss = (string) file_get_contents(base_path('src/css/tokens.css'));
preg_match_all('/--([a-z0-9-]+):\s*(#[0-9A-Fa-f]{6})\s*;(?:[ \t]*\/\*[ \t]*(.*?)[ \t]*\*\/)?/', $tokensCss, $m, PREG_SET_ORDER);
$colours = [];
foreach ($m as $row) {
    $colours[$row[1]] = ['hex' => strtoupper($row[2]), 'note' => $row[3] ?? ''];
}
$groups = [
    'Logo anchors' => ['ms-sky', 'ms-azure', 'ms-navy'],
    'Azure'    => array_map(fn ($s) => "azure-$s", [50, 100, 200, 300, 400, 500, 600, 650, 700, 800, 900, 950]),
    'Navy'     => array_map(fn ($s) => "navy-$s", [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950]),
    'Sky'      => array_map(fn ($s) => "sky-$s", [50, 100, 200, 300, 700]),
    'Neutrals' => ['ms-white', 'ms-canvas', 'ms-mist', 'ms-line', 'ms-ink', 'ms-ink-2', 'ms-ink-3', 'ms-midnight'],
    'Semantic' => ['ms-success', 'ms-warning', 'ms-danger', 'ms-focus'],
];

$luminance = static function (string $hex): float {
    $rgb = array_map(static fn (string $c): float => hexdec($c) / 255, str_split(ltrim($hex, '#'), 2));
    $lin = array_map(static fn (float $c): float => $c <= 0.03928 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4, $rgb);
    return 0.2126 * $lin[0] + 0.7152 * $lin[1] + 0.0722 * $lin[2];
};
$contrast = static function (string $a, string $b) use ($luminance): float {
    $l1 = $luminance($a); $l2 = $luminance($b);
    return round((max($l1, $l2) + 0.05) / (min($l1, $l2) + 0.05), 2);
};
$white = $colours['ms-white']['hex'];
$ink = $colours['ms-ink']['hex'];
$grade = static fn (float $r): string => $r >= 4.5 ? 'AA' : ($r >= 3 ? 'AA large' : '—');

// ---- icons.json ----------------------------------------------------------------------
$iconList = json_decode((string) file_get_contents(base_path('src/icons.json')), true) ?: [];
unset($iconList['$comment']);

$typeScale = [
    ['display', 'Display', 'A bold claim in a few words'],
    ['h1', 'Heading 1', 'Tender writing that wins contracts'],
    ['h2', 'Heading 2', 'Our approach to winning contracts'],
    ['h3', 'Heading 3', 'Evidence-led writing, reviewed twice'],
    ['h4', 'Heading 4', 'What you receive'],
    ['lead', 'Lead', 'We help UK businesses find, qualify and win public-sector contracts with evidence-led bids.'],
    ['body', 'Body', 'Every submission is built around what evaluators score: compliance, evidence and a clear delivery model.'],
    ['small', 'Small', 'Registered in England and Wales, company no. 15677749.'],
    ['eyebrow', 'Eyebrow', 'Our Services'],
];
?>
<div class="container sg">
  <header class="sg__header">
    <?= component('eyebrow', ['text' => 'Developer styleguide']) ?>
    <?= heading(1, 'Foundations, tokens and', 'components') ?>
    <p class="lead">Local-only reference built by P02. Everything here comes from <code>tokens.css</code>, the Phosphor sprite and the foundation partials.</p>
    <nav class="sg__nav cluster" aria-label="Styleguide sections">
      <?php foreach (['colours' => 'Colours', 'type' => 'Type', 'buttons' => 'Buttons', 'icons' => 'Icons', 'components' => 'Components', 'glass' => 'Glass', 'images' => 'Images'] as $id => $label): ?>
        <a class="chip chip--outline" href="#<?= $id ?>"><?= $label ?></a>
      <?php endforeach; ?>
    </nav>
  </header>

  <!-- ============ Colours ============ -->
  <section class="sg__section" id="colours">
    <?= heading(2, 'Colour', 'system') ?>
    <p class="lead">Contrast is measured against white and against ink (<?= e($ink) ?>). Azure 500 is for large display words, icons and glows only.</p>
    <?php foreach ($groups as $groupName => $names): ?>
      <h3 class="sg__h3"><?= e($groupName) ?></h3>
      <ul class="sg__swatches">
        <?php foreach ($names as $name): if (!isset($colours[$name])) continue; $hex = $colours[$name]['hex'];
          $cw = $contrast($hex, $white); $ci = $contrast($hex, $ink); ?>
          <li class="sg__swatch">
            <div class="sg__swatch-chip" style="background: var(--<?= e($name) ?>)"></div>
            <div class="sg__swatch-meta">
              <code>--<?= e($name) ?></code>
              <span class="sg__hex"><?= e($hex) ?></span>
              <span class="sg__contrast">vs white <strong><?= $cw ?></strong> <em><?= $grade($cw) ?></em></span>
              <span class="sg__contrast">vs ink <strong><?= $ci ?></strong> <em><?= $grade($ci) ?></em></span>
              <?php if ($colours[$name]['note'] !== ''): ?><span class="sg__note"><?= e($colours[$name]['note']) ?></span><?php endif; ?>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>

    <h3 class="sg__h3">Gradients</h3>
    <div class="sg__gradients">
      <div class="sg__gradient" style="background: var(--ms-grad-brand)"><code>--ms-grad-brand</code></div>
      <div class="sg__gradient" style="background: var(--ms-grad-hero)"><code>--ms-grad-hero</code></div>
      <div class="sg__gradient sg__gradient--glow"><span class="sg__glow-ring"></span><code>--ms-grad-glow (animated)</code></div>
      <div class="sg__gradient sg__gradient--text"><span style="background: var(--ms-grad-text); -webkit-background-clip: text; background-clip: text; color: transparent">Text</span><code>--ms-grad-text</code></div>
    </div>
  </section>

  <!-- ============ Type ============ -->
  <section class="sg__section" id="type">
    <?= heading(2, 'Type', 'scale') ?>
    <p class="lead">Mona Sans for headings, Google Sans for body, Playfair Display Italic for the accent words. All self-hosted from <code>/assets/fonts/</code>.</p>
    <ul class="sg__type">
      <?php foreach ($typeScale as [$token, $label, $sample]): ?>
        <li class="sg__type-row">
          <div class="sg__type-meta"><code>--fs-<?= $token ?></code><span class="small" data-measure="--fs-<?= $token ?>"></span></div>
          <?php if ($token === 'eyebrow'): ?>
            <?= component('eyebrow', ['text' => $sample]) ?>
          <?php elseif (in_array($token, ['h1', 'h2', 'h3', 'h4'], true)): ?>
            <p class="sg__sample sg__sample--<?= $token ?>" role="presentation"><?= e($sample) ?></p>
          <?php else: ?>
            <p class="sg__sample sg__sample--<?= $token ?> <?= in_array($token, ['lead', 'small', 'display'], true) ? $token : '' ?>"><?= e($sample) ?></p>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <h3 class="sg__h3">Two-voice heading</h3>
    <div class="sg__two-voice">
      <div class="sg__panel">
        <?= component('eyebrow', ['text' => 'About My Solutionz']) ?>
        <?= heading(2, 'Our Approach to', 'Winning Contracts') ?>
        <p class="lead">Gradient accent from 48px, Azure 500 below that.</p>
      </div>
      <div class="sg__panel on-dark">
        <?= component('eyebrow', ['text' => 'Why work with us']) ?>
        <?= heading(2, 'More than tender writers.', 'A partner in growth.') ?>
        <p class="lead">On dark surfaces the accent is Sky 300.</p>
      </div>
    </div>
  </section>

  <!-- ============ Buttons ============ -->
  <section class="sg__section" id="buttons">
    <?= heading(2, 'Buttons and', 'links') ?>
    <p class="lead">48px high, 44px minimum targets. Hover lifts 2px; press scales to .98; focus shows the 2px Azure 600 ring.</p>
    <?php foreach (['Default' => [], 'Disabled' => ['disabled' => true]] as $state => $extra): ?>
      <h3 class="sg__h3"><?= $state ?></h3>
      <div class="sg__row cluster">
        <?= component('button', ['label' => 'Get a Free Quote', 'href' => '/contact-us/'] + $extra) ?>
        <?= component('button', ['label' => 'Explore Services', 'href' => '/services/', 'variant' => 'secondary'] + $extra) ?>
        <?= component('button', ['label' => 'Book a Consultation', 'href' => '/contact-us/', 'variant' => 'split'] + $extra) ?>
        <?= component('button', ['label' => 'Call now', 'href' => 'tel:' . config('phone_e164'), 'icon' => 'phone'] + $extra) ?>
        <?= component('button', ['label' => 'Submit enquiry', 'type' => 'submit'] + $extra) ?>
        <?= component('button', ['label' => 'More About Us', 'href' => '/about-us/', 'variant' => 'link'] + $extra) ?>
      </div>
    <?php endforeach; ?>
    <h3 class="sg__h3">On dark</h3>
    <div class="sg__row sg__row--dark on-dark cluster">
      <?= component('button', ['label' => 'Get a Free Quote', 'href' => '/contact-us/']) ?>
      <?= component('button', ['label' => 'Watch how it works', 'href' => '#', 'variant' => 'glass', 'icon' => 'play']) ?>
      <?= component('button', ['label' => 'Explore Services', 'href' => '/services/', 'variant' => 'split', 'class' => 'btn--glass']) ?>
      <?= component('button', ['label' => 'View all services', 'href' => '/services/', 'variant' => 'link']) ?>
    </div>
    <h3 class="sg__h3">Sizes and icon buttons</h3>
    <div class="sg__row cluster">
      <?= component('button', ['label' => 'Small', 'href' => '#', 'size' => 'sm']) ?>
      <?= component('button', ['label' => 'Default', 'href' => '#']) ?>
      <?= component('button', ['label' => 'Large', 'href' => '#', 'size' => 'lg']) ?>
      <button class="btn-icon" type="button"><?= icon('x', 'regular', ['label' => 'Close']) ?></button>
      <button class="btn-icon" type="button"><?= icon('list', 'regular', ['label' => 'Open menu']) ?></button>
      <button class="btn-icon" type="button"><?= icon('arrow-up', 'regular', ['label' => 'Back to top']) ?></button>
    </div>
  </section>

  <!-- ============ Icons ============ -->
  <section class="sg__section" id="icons">
    <?= heading(2, 'Icon', 'sprite') ?>
    <p class="lead"><?= array_sum(array_map('count', $iconList)) ?> Phosphor symbols in <code>/assets/icons/sprite.svg</code>. Light = content, regular = UI. Add names to <code>src/icons.json</code> and run <code>npm run icons</code>.</p>
    <?php foreach ($iconList as $weight => $names): sort($names); ?>
      <h3 class="sg__h3"><?= e(ucfirst($weight)) ?> (<?= count($names) ?>)</h3>
      <ul class="sg__icons">
        <?php foreach ($names as $name): ?>
          <li class="sg__icon"><?= icon($name, $weight, ['size' => 28]) ?><code><?= e($name) ?></code></li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
  </section>

  <!-- ============ Components ============ -->
  <section class="sg__section" id="components">
    <?= heading(2, 'Foundation', 'components') ?>

    <h3 class="sg__h3">C07 Hex icon tiles</h3>
    <div class="sg__row cluster">
      <div class="sg__tile-demo"><?= component('hex-tile', ['icon' => 'kanban', 'category' => 'bid']) ?><span>Bid &amp; Proposal<br>Management</span></div>
      <div class="sg__tile-demo"><?= component('hex-tile', ['icon' => 'seal-check', 'category' => 'compliance']) ?><span>Compliance &amp;<br>Virtual Assistance</span></div>
      <div class="sg__tile-demo"><?= component('hex-tile', ['icon' => 'devices', 'category' => 'digital']) ?><span>Digital<br>Presence</span></div>
      <div class="sg__tile-demo"><?= component('hex-tile', ['icon' => 'handshake']) ?><span>Default</span></div>
      <div class="sg__tile-demo"><?= component('hex-tile', ['icon' => 'pen-nib', 'size' => 'sm']) ?><span>Small</span></div>
      <div class="sg__tile-demo"><?= component('hex-tile', ['icon' => 'trophy', 'size' => 'lg']) ?><span>Large</span></div>
    </div>
    <div class="sg__row sg__row--dark on-dark cluster">
      <?= component('hex-tile', ['icon' => 'kanban', 'category' => 'bid']) ?>
      <?= component('hex-tile', ['icon' => 'seal-check', 'category' => 'compliance']) ?>
      <?= component('hex-tile', ['icon' => 'devices', 'category' => 'digital']) ?>
      <?= component('hex-tile', ['icon' => 'first-aid-kit']) ?>
    </div>

    <h3 class="sg__h3">Chips</h3>
    <div class="sg__row cluster">
      <?= component('chip', ['label' => 'Neutral']) ?>
      <?= component('chip', ['label' => 'Outline', 'variant' => 'outline']) ?>
      <?= component('chip', ['label' => 'Bid & Proposal', 'variant' => 'bid', 'dot' => true]) ?>
      <?= component('chip', ['label' => 'Compliance & VA', 'variant' => 'compliance', 'dot' => true]) ?>
      <?= component('chip', ['label' => 'Digital Presence', 'variant' => 'digital', 'dot' => true]) ?>
      <?= component('chip', ['label' => 'Bid', 'variant' => 'solid-bid']) ?>
      <?= component('chip', ['label' => 'Compliance', 'variant' => 'solid-compliance']) ?>
      <?= component('chip', ['label' => 'Digital', 'variant' => 'solid-digital']) ?>
      <?= component('chip', ['label' => 'Healthcare & Social Care', 'href' => '/sectors/healthcare-social-care/', 'icon' => 'caret-right']) ?>
      <?= component('chip', ['label' => 'Cleaning', 'href' => '/sectors/cleaning/', 'variant' => 'outline']) ?>
    </div>
    <div class="sg__row sg__row--dark on-dark cluster">
      <?= component('chip', ['label' => 'Neutral']) ?>
      <?= component('chip', ['label' => 'Outline', 'variant' => 'outline']) ?>
      <?= component('chip', ['label' => 'Bid & Proposal', 'variant' => 'bid', 'dot' => true]) ?>
      <?= component('chip', ['label' => 'Compliance & VA', 'variant' => 'compliance', 'dot' => true]) ?>
      <?= component('chip', ['label' => 'Digital Presence', 'variant' => 'digital', 'dot' => true]) ?>
    </div>

    <h3 class="sg__h3">C14 Avatar stack</h3>
    <div class="sg__row cluster">
      <?= component('avatar-stack', ['avatars' => [['initials' => 'MS', 'alt' => 'Team member'], ['initials' => 'WA', 'alt' => 'Team member'], ['initials' => 'JB', 'alt' => 'Team member']], 'more' => 2, 'label' => 'Strategic bids for sustainable growth']) ?>
      <?= component('avatar-stack', ['avatars' => [['image' => 'team/member-1', 'alt' => 'Team member'], ['image' => 'team/member-2', 'alt' => 'Team member'], ['image' => 'team/member-3', 'alt' => 'Team member']], 'class' => 'avatar-stack--lg']) ?>
    </div>
  </section>

  <!-- ============ Glass ============ -->
  <section class="sg__section" id="glass">
    <?= heading(2, 'Glass', 'materials') ?>
    <p class="lead">Only over imagery or colour; max three visible at once. Reduced transparency swaps in solid fills.</p>
    <div class="sg__glass-stage">
      <div class="glass-panel glass-ultrathin-dark"><strong>.glass-ultrathin-dark</strong><p class="small">photos / dark</p></div>
      <div class="glass-panel glass-thin-dark"><strong>.glass-thin-dark</strong><p class="small">hero cards, nav on hero</p></div>
      <div class="glass-panel glass-regular-light"><strong>.glass-regular-light</strong><p class="small">sticky nav on light pages</p></div>
      <div class="glass-panel glass-thick-dark"><strong>.glass-thick-dark</strong><p class="small">nav over mixed content, mega menu</p></div>
    </div>
  </section>

  <!-- ============ Images ============ -->
  <section class="sg__section" id="images">
    <?= heading(2, 'Images and', 'placeholders') ?>
    <p class="lead"><code>img('key', [...])</code> prints a <code>&lt;picture&gt;</code> from <code>public/assets/img/manifest.json</code>; unknown keys show the branded placeholder at the requested ratio.</p>
    <div class="sg__images">
      <figure class="media-rounded"><?= img('hero/london-office', ['alt' => 'London office building at dusk', 'ratio' => '16/9']) ?><figcaption class="small">16/9 placeholder</figcaption></figure>
      <figure class="media-rounded"><?= img('about/team-meeting', ['alt' => 'Team reviewing a tender', 'ratio' => '4/3']) ?><figcaption class="small">4/3 placeholder</figcaption></figure>
      <figure class="media-rounded"><?= img('services/tender-writing', ['decorative' => true, 'ratio' => '1/1']) ?><figcaption class="small">1/1 decorative (aria-hidden)</figcaption></figure>
    </div>
  </section>
</div>
<script>
  // Dev-only: show the computed size of each type token at the current viewport
  document.querySelectorAll('[data-measure]').forEach((el) => {
    const probe = document.createElement('span');
    probe.style.cssText = 'position:absolute;visibility:hidden;font-size:var(' + el.dataset.measure + ')';
    document.body.append(probe);
    el.textContent = Math.round(parseFloat(getComputedStyle(probe).fontSize)) + 'px';
    probe.remove();
  });
</script>
