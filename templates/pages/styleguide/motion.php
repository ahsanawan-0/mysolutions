<?php
declare(strict_types=1);
/**
 * Motion styleguide (local only, noindex): one labelled demo per data-anim effect (docs/06 §3 + §6) and the nodes
 * module, so each can be tested in isolation at 1440 and 360, with reduced motion and without JS.
 */
$page = [
    'title'       => 'Motion styleguide | My Solutionz (dev)',
    'description' => 'Developer motion styleguide for the My Solutionz website: every data-anim effect of the GSAP + Lenis motion core, with a demo block per effect.',
    'canonical'   => '/styleguide/motion/',
    'robots'      => 'noindex, nofollow',
    'status'      => 'draft',
    'breadcrumbs' => [['Home', '/'], ['Styleguide', '/styleguide/'], ['Motion', null]],
    'body_class'  => 'page-styleguide page-styleguide-motion',
];

$services = services();
$sectors = sectors();
$catalogue = [
    'fade-up' => 'y 24 → 0, opacity 0 → 1, 0.7s out, start top 85%, once',
    'stagger' => 'children fade-up 0.08s apart (ScrollTrigger.batch)',
    'split-lines' => 'SplitText lines in masks, yPercent 110 → 0, stagger 0.1, 0.9s out',
    'split-chars' => 'characters, stagger 0.02 (max once per page, never the hero H1)',
    'two-voice' => 'line 1 from the left 40px, accent clips in with skewX −8° → 0',
    'parallax' => 'data-speed → y offset (1 − speed) × distance, scrubbed; mobile halves it',
    'img-reveal' => 'clip-path inset(100% … round r) → inset(0 round r) + inner scale 1.2 → 1, scrubbed',
    'img-zoom-out' => 'inner image scale 1.15 → 1 over the section',
    'counter' => 'count-up from 0, 1.6s out (data-to, data-suffix)',
    'fill-text' => 'background-position 0% → 100% scrubbed',
    'draw' => 'DrawSVG 0 → 100%, scrubbed',
    'pin-steps' => 'pinned N × 100vh, steps switch at thresholds, progress line fills (desktop)',
    'h-scroll' => 'pinned horizontal track (desktop); swipe row below 1024px',
    'stack-cards' => 'sticky cards; covered cards scale to 0.94 and dim',
    'marquee' => 'seamless loop, velocity-aware, hover slows, pause button (WCAG 2.2.2)',
    'magnetic' => 'follows the pointer within 12px, springs back (pointer devices)',
    'tilt' => 'max 6° tilt + glare (pointer devices)',
    'wave' => 'MorphSVG between two wave paths, scrubbed',
    'velocity-skew' => 'images skew up to 4° with the scroll velocity (desktop)',
    'read-along' => 'characters opacity 0.2 → 1 scrubbed (never from 0)',
    'logo-assembly' => 'S1: three pieces fly in and assemble, scrubbed top bottom → top 35%',
    'micro-arrow' => 'S2: arrow turns down at 50–60% then drops 88px, scrub 0.3',
    'blur-zoom' => 'S3: scale 2 + blur 15px → clear at centre (desktop only)',
    'ring' => 'S4: ring fills + count-up, plays once',
    'rise-rotate' => 'S10: faded shape rises and rotates behind the section (also cta-mark)',
    'nodes' => 'module: canvas network, pointer repulsion, paused off-screen, static SVG on low-end devices',
];
$head = static function (string $name, string $spec, ?string $note = null): string {
    $html = '<header class="sgm__head" id="' . e($name) . '"><h2 class="sgm__title"><code>data-anim="' . e($name) . '"</code></h2><p class="sgm__spec">' . e($spec) . '</p>';
    if ($note !== null) $html .= '<p class="sgm__note small">' . e($note) . '</p>';
    return $html . '</header>';
};
$cardHtml = static fn (array $c): string => '<article class="sgm__card">' . component('hex-tile', ['icon' => $c['icon'], 'category' => $c['colour']]) . '<h3>' . e($c['name']) . '</h3><p>' . e($c['summary'] ?? $c['short']) . '</p></article>';
?>
<div class="container sg sgm">
  <header class="sg__header">
    <?= component('eyebrow', ['text' => 'Developer styleguide']) ?>
    <?= heading(1, 'Motion', 'catalogue') ?>
    <p class="lead">Every <code>data-anim</code> effect from <code>docs/06</code> in isolation. Lenis + ScrollTrigger run on this page; test at 1440 and 360, with <em>prefers-reduced-motion</em> (everything static) and with JavaScript off (everything visible).</p>
    <nav class="sg__nav cluster" aria-label="Effects">
      <a class="chip chip--outline" href="<?= e(url('/styleguide/')) ?>">← Styleguide</a>
      <?php foreach (array_keys($catalogue) as $name): ?>
      <a class="chip chip--outline" href="#<?= e($name) ?>"><?= e($name) ?></a>
      <?php endforeach; ?>
    </nav>
  </header>

  <!-- fade-up -->
  <section class="sg__section"><?= $head('fade-up', $catalogue['fade-up']) ?>
    <div class="sgm__grid">
      <?php foreach ($services as $c): ?>
      <div data-anim="fade-up"><?= $cardHtml($c) ?></div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- stagger -->
  <section class="sg__section"><?= $head('stagger', $catalogue['stagger']) ?>
    <ul class="sgm__chips cluster" data-anim="stagger">
      <?php foreach ($sectors as $s): ?>
      <li><?= component('chip', ['label' => $s['name'], 'href' => $s['url'], 'variant' => 'outline', 'icon' => 'caret-right']) ?></li>
      <?php endforeach; ?>
    </ul>
  </section>

  <!-- split-lines -->
  <section class="sg__section"><?= $head('split-lines', $catalogue['split-lines']) ?>
    <div class="sg__panel">
      <h3 class="sgm__h2" data-anim="split-lines">Tender writing that wins contracts, built on over 10 years of business development experience.</h3>
    </div>
  </section>

  <!-- split-chars -->
  <section class="sg__section"><?= $head('split-chars', $catalogue['split-chars']) ?>
    <div class="sg__panel">
      <p class="sgm__display" data-anim="split-chars">Winning</p>
    </div>
  </section>

  <!-- two-voice -->
  <section class="sg__section"><?= $head('two-voice', $catalogue['two-voice']) ?>
    <div class="sg__two-voice">
      <div class="sg__panel">
        <?= component('eyebrow', ['text' => 'About My Solutionz']) ?>
        <?= heading(3, 'Our Approach to', 'Winning Contracts', 'sgm__h2', null, ['data-anim' => 'two-voice']) ?>
      </div>
      <div class="sg__panel on-dark">
        <?= component('eyebrow', ['text' => 'Why work with us']) ?>
        <?= heading(3, 'More than tender writers.', 'A partner in growth.', 'sgm__h2', null, ['data-anim' => 'two-voice']) ?>
      </div>
    </div>
  </section>

  <!-- parallax -->
  <section class="sg__section"><?= $head('parallax', $catalogue['parallax']) ?>
    <div class="sgm__parallax" data-parallax-scope>
      <span class="sgm__layer sgm__layer--1" data-anim="parallax" data-speed="0.6" aria-hidden="true"><?= component('hex-tile', ['icon' => 'hexagon', 'size' => 'lg']) ?></span>
      <span class="sgm__layer sgm__layer--2" data-anim="parallax" data-speed="0.85" aria-hidden="true"><?= component('hex-tile', ['icon' => 'trophy', 'category' => 'bid']) ?></span>
      <span class="sgm__layer sgm__layer--3" data-anim="parallax" data-speed="1.3" aria-hidden="true"><?= component('hex-tile', ['icon' => 'target', 'category' => 'digital', 'size' => 'sm']) ?></span>
      <div class="sgm__parallax-copy glass-thin-dark">
        <strong>Three layers at 0.6, 0.85 and 1.3</strong>
        <p class="small">Slower layers lag behind the scroll; faster layers overtake it. Distances halve below 1024px.</p>
      </div>
    </div>
  </section>

  <!-- img-reveal -->
  <section class="sg__section"><?= $head('img-reveal', $catalogue['img-reveal']) ?>
    <figure class="media-rounded sgm__media" data-anim="img-reveal"><?= img('about/team-meeting', ['alt' => 'Team reviewing a tender', 'ratio' => '16/9']) ?></figure>
  </section>

  <!-- img-zoom-out -->
  <section class="sg__section"><?= $head('img-zoom-out', $catalogue['img-zoom-out']) ?>
    <figure class="media-rounded sgm__media" data-anim="img-zoom-out"><?= img('hero/london-office', ['alt' => 'London office building at dusk', 'ratio' => '16/9']) ?></figure>
  </section>

  <!-- counter -->
  <section class="sg__section"><?= $head('counter', $catalogue['counter']) ?>
    <div class="sgm__stats">
      <div class="sgm__stat"><strong class="sgm__number" data-anim="counter" data-to="10" data-suffix="+">10+</strong><span>years of business development experience</span></div>
      <div class="sgm__stat"><strong class="sgm__number" data-anim="counter" data-to="<?= count($services) ?>"><?= count($services) ?></strong><span>service categories</span></div>
      <div class="sgm__stat"><strong class="sgm__number" data-anim="counter" data-to="<?= count($sectors) ?>"><?= count($sectors) ?></strong><span>sectors we write for</span></div>
    </div>
  </section>

  <!-- fill-text -->
  <section class="sg__section"><?= $head('fill-text', $catalogue['fill-text']) ?>
    <div class="sg__panel"><p class="sgm__display fill-text" data-anim="fill-text">Bid. Win. Grow.</p></div>
  </section>

  <!-- draw -->
  <section class="sg__section"><?= $head('draw', $catalogue['draw']) ?>
    <div class="sg__panel sgm__draw-panel">
      <svg class="sgm__draw" viewBox="0 0 600 220" data-anim="draw" aria-hidden="true" focusable="false">
        <polygon points="110,20 190,66 190,158 110,204 30,158 30,66"/>
        <polyline points="190,112 290,112 340,60 440,60"/>
        <polyline points="190,112 290,112 340,164 440,164"/>
        <circle cx="470" cy="60" r="22"/>
        <circle cx="470" cy="164" r="22"/>
        <polyline points="492,60 560,60"/>
        <polyline points="492,164 560,164"/>
      </svg>
    </div>
  </section>

  <!-- pin-steps -->
  <section class="sg__section"><?= $head('pin-steps', $catalogue['pin-steps'], 'Below 1024px the steps stack and each one activates as it passes 60% of the viewport.') ?></section>
  <section class="sgm__pin on-dark" data-anim="pin-steps">
    <div class="sgm__pin-inner">
      <ol class="sgm__steps">
        <span class="sgm__steps-line" aria-hidden="true"><span class="sgm__steps-fill" data-step-progress></span></span>
        <?php foreach ([['Discovery call', 'We learn your services, sectors and bidding history.'], ['Bid / no-bid review', 'We qualify each opportunity against your win themes.'], ['Writing and evidence', 'Compliant, evaluator-led answers with your evidence.'], ['Review and submission', 'Two reviews, final formatting, portal submission.']] as $i => [$title, $text]): ?>
        <li class="sgm__step" data-step>
          <span class="sgm__step-n"><?= $i + 1 ?></span>
          <div><h3><?= e($title) ?></h3><p><?= e($text) ?></p></div>
        </li>
        <?php endforeach; ?>
      </ol>
      <div class="sgm__panels" aria-hidden="true">
        <?php foreach (['magnifying-glass', 'list-checks', 'pen-nib', 'paper-plane-tilt'] as $i => $iconName): ?>
        <div class="sgm__panel-visual" data-step-panel><?= icon($iconName, 'light', ['size' => 96]) ?><span>Step <?= $i + 1 ?> of 4</span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- h-scroll -->
  <section class="sg__section"><?= $head('h-scroll', $catalogue['h-scroll']) ?></section>
  <section class="sgm__hscroll" data-anim="h-scroll">
    <div class="sgm__hscroll-track" data-h-track>
      <?php foreach ($services as $c): ?>
      <div class="sgm__panel-card"><?= $cardHtml($c) ?></div>
      <?php endforeach; ?>
      <div class="sgm__panel-card sgm__panel-card--cta"><article class="sgm__card"><h3>All services</h3><p>Every category and sub-service on one page.</p><?= component('button', ['label' => 'Explore services', 'href' => '/services/', 'variant' => 'split']) ?></article></div>
    </div>
  </section>

  <!-- stack-cards -->
  <section class="sg__section"><?= $head('stack-cards', $catalogue['stack-cards']) ?>
    <div class="sgm__stack" data-anim="stack-cards">
      <?php foreach ($services as $i => $c): ?>
      <div class="sgm__stack-card sgm__stack-card--<?= $i + 1 ?>"><?= $cardHtml($c) ?></div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- marquee -->
  <section class="sg__section"><?= $head('marquee', $catalogue['marquee']) ?>
    <div class="sg__row sgm__marquee-row">
      <?= component('marquee', ['items' => ui('footer.marquee'), 'label' => 'What we do', 'variant' => 'display', 'speed' => 80]) ?>
    </div>
    <div class="sg__row">
      <?= component('marquee', ['items' => array_column($sectors, 'name'), 'label' => 'Sectors', 'speed' => 50]) ?>
    </div>
  </section>

  <!-- magnetic -->
  <section class="sg__section"><?= $head('magnetic', $catalogue['magnetic']) ?>
    <div class="sg__row cluster">
      <span data-anim="magnetic"><?= component('button', ['label' => 'Get a Free Quote', 'href' => '/contact-us/']) ?></span>
      <span data-anim="magnetic" data-magnetic="18"><?= component('button', ['label' => 'Explore Services', 'href' => '/services/', 'variant' => 'secondary']) ?></span>
      <span data-anim="magnetic"><?= component('button', ['label' => 'Book a Consultation', 'href' => '/contact-us/', 'variant' => 'split']) ?></span>
    </div>
  </section>

  <!-- tilt -->
  <section class="sg__section"><?= $head('tilt', $catalogue['tilt']) ?>
    <div class="sgm__grid">
      <?php foreach (array_slice($sectors, 0, 3) as $s): ?>
      <article class="sgm__card sgm__card--tilt" data-anim="tilt"><?= component('hex-tile', ['icon' => $s['icon'], 'size' => 'sm']) ?><h3><?= e($s['name']) ?></h3><p><?= e($s['summary'] ?? $s['short'] ?? '') ?></p></article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- wave -->
  <section class="sg__section"><?= $head('wave', $catalogue['wave']) ?>
    <div class="sg__dividers">
      <div class="sg__band sg__band--mist"><span class="small">Section on mist</span></div>
      <?= component('divider', ['variant' => 'wave', 'from' => 'ms-mist', 'to' => 'navy-900']) ?>
      <div class="sg__band sg__band--navy on-dark"><span class="small">The wave above morphs between its two paths as it crosses the viewport.</span></div>
    </div>
  </section>

  <!-- velocity-skew -->
  <section class="sg__section"><?= $head('velocity-skew', $catalogue['velocity-skew']) ?>
    <div class="sg__images">
      <figure class="media-rounded" data-anim="velocity-skew"><?= img('services/tender-writing', ['alt' => 'Writer at a desk reviewing a bid', 'ratio' => '4/3']) ?></figure>
      <figure class="media-rounded" data-anim="velocity-skew"><?= img('sectors/construction', ['alt' => 'Construction site in London', 'ratio' => '4/3']) ?></figure>
    </div>
  </section>

  <!-- read-along -->
  <section class="sg__section"><?= $head('read-along', $catalogue['read-along']) ?>
    <div class="container--text">
      <p class="sgm__statement" data-anim="read-along">We help UK businesses find, qualify and win public-sector contracts: evidence-led bids, compliant documentation and a digital presence evaluators can trust. Every submission is built around what gets scored, so your team spends its time on delivery rather than paperwork.</p>
    </div>
  </section>

  <!-- logo-assembly -->
  <section class="sg__section"><?= $head('logo-assembly', $catalogue['logo-assembly'], 'Stand-in: three rhombi forming the hexagon until P07 ships the real mark pieces.') ?>
    <div class="sg__panel sgm__assembly" data-assembly-scope>
      <svg class="sgm__mark" viewBox="0 0 100 110" data-anim="logo-assembly" aria-hidden="true" focusable="false">
        <polygon class="sgm__piece sgm__piece--1" data-piece points="50,9 89.8,32 50,55 10.2,32"/>
        <polygon class="sgm__piece sgm__piece--2" data-piece points="10.2,32 50,55 50,101 10.2,78"/>
        <polygon class="sgm__piece sgm__piece--3" data-piece points="50,55 89.8,32 89.8,78 50,101"/>
      </svg>
      <p class="small">Scrubbed from the moment the panel enters until its top reaches 35% of the viewport.</p>
    </div>
  </section>

  <!-- micro-arrow -->
  <section class="sg__section"><?= $head('micro-arrow', $catalogue['micro-arrow']) ?>
    <div class="sg__row sgm__micro-row"><?= component('micro-cta') ?></div>
  </section>

  <!-- blur-zoom -->
  <section class="sg__section"><?= $head('blur-zoom', $catalogue['blur-zoom']) ?>
    <div class="sg__panel sgm__blur">
      <?= heading(3, 'Results you can', 'measure', 'sgm__h2', null, ['data-anim' => 'blur-zoom']) ?>
    </div>
  </section>

  <!-- ring -->
  <section class="sg__section"><?= $head('ring', $catalogue['ring'], 'Demo values only: the real figures and their evidence come with the client content (docs/10 §10).') ?>
    <div class="sgm__stats">
      <?php foreach ([[0.82, 82, 'Example ratio A (demo)'], [0.64, 64, 'Example ratio B (demo)'], [1, 100, 'Example ratio C (demo)']] as [$value, $to, $label]): ?>
      <div class="sgm__stat sgm__stat--ring" data-anim="ring" data-value="<?= $value ?>">
        <svg class="sgm__ring" viewBox="0 0 120 120" aria-hidden="true" focusable="false"><circle class="sgm__ring-track" cx="60" cy="60" r="52"/><circle class="sgm__ring-fill" data-ring cx="60" cy="60" r="52"/></svg>
        <strong class="sgm__number" data-count data-to="<?= $to ?>" data-suffix="%"><?= $to ?>%</strong>
        <span><?= e($label) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- rise-rotate -->
  <section class="sg__section"><?= $head('rise-rotate', $catalogue['rise-rotate']) ?></section>
  <section class="sgm__rise on-dark">
    <svg class="sgm__rise-mark" viewBox="0 0 100 110" data-anim="rise-rotate" aria-hidden="true" focusable="false"><polygon points="50,4 94,29 94,81 50,106 6,81 6,29" stroke-width="10" stroke-linejoin="round"/></svg>
    <div class="container sgm__rise-copy">
      <?= component('eyebrow', ['text' => 'Behind the final CTA']) ?>
      <?= heading(3, 'A faded mark rises as', 'you scroll past') ?>
    </div>
  </section>
  <div class="sg__bleed sgm__cta"><?= component('cta-band', ['level' => 3]) ?></div>

  <!-- nodes -->
  <section class="sg__section"><?= $head('nodes', $catalogue['nodes']) ?>
    <div class="nodes sgm__nodes" data-module="nodes">
      <div class="sgm__nodes-card glass-regular-light">
        <strong>Network-node background</strong>
        <p class="small">Move the pointer to push the nodes away. Paused when scrolled out of view or when the tab is hidden; 30fps on mobile.</p>
      </div>
    </div>
    <div class="nodes nodes--dark sgm__nodes sgm__nodes--dark on-dark" data-module="nodes" data-count="36">
      <div class="sgm__nodes-card glass-thin-dark">
        <strong>Dark variant</strong>
        <p class="small">Navy 700 links, Sky 300 nodes.</p>
      </div>
    </div>
  </section>
</div>
