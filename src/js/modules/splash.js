// Splash screen (docs/07 §4–§5). The CSS choreography (splash.css) starts on first paint; this module only
//  - records the view (sessionStorage['ms_splash']) so the splash plays once per session,
//  - lays out the two overlay panels along the 120° brand diagonal and holds scrolling meanwhile (Lenis stop +
//    wheel/touch prevented on the overlay; never `overflow: hidden`, which would drop the scrollbar, widen the
//    viewport and re-report the LCP element later),
//  - makes it skippable (click / tap / Esc / Enter / Space → jump to the final frame, hand off at once),
//  - waits for every animation under #logo-root, then runs the hand-off: GSAP Flip flies the mark, wordmark and
//    tagline onto the header's inline logo while the panels slide apart, and the overlay leaves the DOM,
//  - hard-caps the whole thing at 2.4 s (splash.css adds a CSS-only failsafe at 2.6 s),
//  - dispatches `splash:done` on window (immediately when there is no splash) for the hero entrance (P09).
import { gsap, reducedMotion } from '../core/eases.js';

const HARD_CAP = 2400;   // ms from the first paint (= the CSS animations' start): the overlay is gone by then, whatever happened
const HANDOFF = 0.55;    // s: Flip flight; the panel split starts 0.05 s in, so the hand-off ends at 0.6 s (1600 + 600 = 2200 < cap)
const PARTS = [['mark', 'hl-mark'], ['wordmark', 'hl-wordmark'], ['tagline', 'hl-tagline']];

let finished = false;

function announce() {
  document.documentElement.classList.add('splash-done');
  window.dispatchEvent(new CustomEvent('splash:done'));
}

export function initSplash() {
  const splash = document.getElementById('splash');
  const html = document.documentElement;
  const skipped = !splash || html.classList.contains('no-splash') || reducedMotion() || getComputedStyle(splash).display === 'none';
  if (skipped) {
    splash?.remove();
    announce();
    return;
  }
  try { sessionStorage.setItem('ms_splash', '1'); } catch { /* storage unavailable: the splash plays again next time */ }

  const logoRoot = splash.querySelector('#logo-root');
  const panels = [...splash.querySelectorAll('[data-splash-panel]')];
  const geometry = layoutPanels(splash, panels);
  const block = (e) => e.preventDefault();
  splash.addEventListener('wheel', block, { passive: false });
  splash.addEventListener('touchmove', block, { passive: false });
  window.lenis?.stop?.();

  // Load Flip while the logo animates so it is ready for the hand-off
  const flipReady = import('gsap/Flip').then((m) => { gsap.registerPlugin(m.Flip); return m.Flip; }).catch(() => null);

  const animations = () => (logoRoot.getAnimations ? logoRoot.getAnimations({ subtree: true }) : []);
  let handingOff = false;
  let tl = null;

  const finish = () => {
    if (finished) return;
    finished = true;
    clearTimeout(capTimer);
    tl?.kill();
    document.removeEventListener('keydown', onKey);
    splash.remove();
    window.lenis?.start?.();
    announce();
  };

  const handoff = async () => {
    if (handingOff || finished) return;
    handingOff = true;
    const Flip = await flipReady;
    if (finished) return;
    const header = document.querySelector('[data-header="hero"] .site-header__logo-svg') || document.querySelector('.site-header__logo-svg');
    tl = gsap.timeline({ defaults: { duration: HANDOFF, ease: 'apple' }, onComplete: finish });
    let flown = 0;
    if (Flip && header) {
      PARTS.forEach(([from, to]) => {
        const el = logoRoot.querySelector(`#${from}`);
        const target = header.querySelector(`#${to}`);
        if (!el || !target) return;
        try {
          tl.add(Flip.fit(el, target, { scale: true, duration: HANDOFF, ease: 'apple' }), 0);
          flown++;
        } catch { /* fall through to the plain exit below */ }
      });
    }
    if (flown) {
      tl.to(logoRoot, { opacity: 0, duration: 0.18, ease: 'none' }, HANDOFF - 0.18);   // hides any theme mismatch at touchdown
    } else {
      tl.to(logoRoot, { scale: 0.92, opacity: 0, duration: 0.4, transformOrigin: '50% 50%' }, 0);
    }
    // The canvas splits along the diagonal and the two halves slide apart, revealing the page
    splash.style.background = 'transparent';
    tl.to(panels[0], { x: -geometry.dx, y: -geometry.dy, ease: 'inout' }, 0.05)
      .to(panels[1], { x: geometry.dx, y: geometry.dy, ease: 'inout' }, 0.05);
  };

  const skip = () => {
    animations().forEach((a) => { try { a.finish(); } catch { a.cancel(); } });
    handoff();
  };
  const onKey = (e) => {
    if (!['Escape', 'Enter', ' '].includes(e.key)) return;
    if (e.key !== 'Escape' && document.activeElement && document.activeElement !== document.body) return; // let a focused control act
    e.preventDefault();
    skip();
  };
  splash.addEventListener('pointerdown', skip, { once: true });
  document.addEventListener('keydown', onKey);

  // The CSS animations started at first paint, before this module ran: the cap counts from there
  const elapsed = Math.max(0, ...animations().map((a) => Number(a.currentTime) || 0));
  const capTimer = setTimeout(finish, Math.max(200, HARD_CAP - elapsed));

  // Wait for the choreography. If the stylesheet has not applied yet (no animations found), look again next frame.
  const waitForChoreography = (attempt = 0) => {
    const list = animations();
    if (!list.length) {
      if (attempt < 3) return requestAnimationFrame(() => waitForChoreography(attempt + 1));
      return handoff();
    }
    Promise.all(list.map((a) => a.finished)).then(handoff, handoff);
  };
  waitForChoreography();
}

/**
 * Clip the two panels to the halves of the 120° diagonal (rising to the right, through the viewport centre) with a
 * 1 px overlap, and return how far each must travel along the normal to clear the viewport.
 */
function layoutPanels(splash, panels) {
  const w = splash.clientWidth || innerWidth;
  const h = splash.clientHeight || innerHeight;
  const d = [Math.cos(-Math.PI / 3), Math.sin(-Math.PI / 3)];   // along the line: up-right
  const n = [-d[1], d[0]];                                         // normal: down-right
  const c = [w / 2, h / 2];
  const L = 2 * Math.max(w, h);
  const pt = (x, y) => `${x.toFixed(1)}px ${y.toFixed(1)}px`;
  const half = (sign) => {
    const o = -sign * 0.5;                                          // 1 px overlap on the seam
    const p1 = [c[0] - d[0] * L + n[0] * o, c[1] - d[1] * L + n[1] * o];
    const p2 = [c[0] + d[0] * L + n[0] * o, c[1] + d[1] * L + n[1] * o];
    const p3 = [p2[0] + sign * n[0] * L, p2[1] + sign * n[1] * L];
    const p4 = [p1[0] + sign * n[0] * L, p1[1] + sign * n[1] * L];
    return `polygon(${pt(...p1)}, ${pt(...p2)}, ${pt(...p3)}, ${pt(...p4)})`;
  };
  panels[0].style.clipPath = half(-1);   // up-left half
  panels[1].style.clipPath = half(1);    // down-right half
  const travel = 0.5 * (Math.abs(n[0]) * w + Math.abs(n[1]) * h) + 48;
  return { dx: n[0] * travel, dy: n[1] * travel };
}
