// Inner-page hero (C32, PROMPTS P17; data-module="hero-inner" on templates/components/hero-inner.php).
// Entrance: the pre-states come from CSS under html.js (components/hero-inner.css) so nothing shifts after paint;
// this module builds the timeline, takes the pre-states over (.is-intro-live), waits for `splash:done` (or runs at
// once when the splash already finished or never played) and plays:
//   0.00 breadcrumbs + eyebrow/chip · 0.00 wide image settles 1.12 → 1 (never hidden: it is the LCP element) ·
//   0.10 narrow pill columns clip up (stagger 0.12) · 0.15 H1 lines slide up inside SplitText masks (no opacity) ·
//   0.40 sub · 0.50 lead · 0.60 CTAs · 0.70 trust / meta · 0.30 framed hex card rises, 0.60 the frame draws itself
//   then orbits · 0.75 floating labels float in (stagger 0.12).
// Scroll (≥ 1024 only): every [data-speed] layer (columns 0.9 / 1 / 1.1, labels 1.1 / 1 / 0.9, the framed tile)
// drifts by (1 − speed) × the hero's height, scrubbed from the top of the page.
// Reduced motion: no entrance, no parallax, the frame rests (special-frame.css).
import { gsap, reducedMotion } from '../core/motion.js';
import { SplitText } from 'gsap/SplitText';
import { fontsReady } from '../anim/_util.js';

gsap.registerPlugin(SplitText);

export default function heroInner(hero) {
  const html = document.documentElement;
  if (!('registerProperty' in CSS)) html.classList.add('no-at-property');
  const q = (s) => [...hero.querySelectorAll(s)];
  const one = (s) => hero.querySelector(s);
  const frame = one('.special-frame');
  const title = one('.hero-inner__title');

  if (reducedMotion()) {
    hero.classList.add('is-intro-done');
    frame?.classList.add('is-live');
    return;
  }

  const parallax = () => {
    gsap.matchMedia().add('(min-width: 1024px)', () => {
      const st = () => ({ trigger: hero, start: 'top top', end: 'bottom top', scrub: true, invalidateOnRefresh: true });
      q('[data-speed]').forEach((el) => {
        const k = 1 - parseFloat(el.dataset.speed || '1');
        if (!k) return;
        gsap.fromTo(el, { y: 0 }, { y: () => k * hero.offsetHeight, ease: 'none', scrollTrigger: st() });
      });
    });
  };

  fontsReady.then(() => {
    const lead = [one('.hero-inner__crumbs'), one('.hero-inner__eyebrow, .hero-inner__parent, .hero-inner__chip')].filter(Boolean);
    const wide = one('.hero-inner__col--1 img, .hero-inner__col--1 .img-placeholder');
    const narrow = q('.hero-inner__col--2 .hero-inner__col-media, .hero-inner__col--3 .hero-inner__col-media');
    const labels = q('.hero-inner__label');
    const card = one('.hero-inner__badge-card');
    const after = [one('.hero-inner__sub'), one('.hero-inner__lead'), one('.hero-inner__ctas'), one('.hero-inner__trust, .hero-inner__meta')];
    const split = title ? SplitText.create(title, { type: 'lines', mask: 'lines', linesClass: 'hero-inner__line' }) : null;

    const tl = gsap.timeline({
      paused: true,
      defaults: { ease: 'expo.out' },
      onComplete: () => {
        split?.revert();
        gsap.set([...lead, wide, ...narrow, ...labels, card, ...after].filter(Boolean), { clearProps: 'all' });
        hero.classList.add('is-intro-done');
        parallax();
      },
    });
    tl.fromTo(lead, { y: 12, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.6, stagger: 0.06 }, 0);
    if (wide) tl.fromTo(wide, { scale: 1.12 }, { scale: 1, duration: 1.4, ease: 'power2.out' }, 0);   // opacity stays 1 (LCP)
    if (narrow.length) {
      tl.fromTo(narrow, { clipPath: 'inset(100% 0% 0% 0% round 999px)' }, { clipPath: 'inset(0% 0% 0% 0% round 999px)', duration: 1.1, ease: 'expo.inOut', stagger: 0.12 }, 0.1);
    }
    if (split) tl.from(split.lines, { yPercent: 110, duration: 0.9, stagger: 0.08 }, 0.15);
    after.forEach((el, i) => {
      if (el) tl.fromTo(el, { y: 16, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.7 }, 0.4 + i * 0.1);
    });
    if (card) tl.fromTo(card, { y: 28, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.9 }, 0.3);
    if (frame) {
      tl.fromTo(frame, { '--draw': '0%' }, { '--draw': '100%', duration: 0.8, ease: 'power2.inOut', onComplete: () => frame.classList.add('is-live') }, 0.6);
    }
    if (labels.length) {
      tl.fromTo(labels, { y: 24, scale: 0.94, autoAlpha: 0 }, { y: 0, scale: 1, autoAlpha: 1, duration: 0.8, stagger: 0.12, ease: 'back.out(1.6)' }, 0.75);
    }

    // GSAP now holds the pre-states (the timeline sits at 0), so the CSS pre-states can go
    tl.progress(0);
    hero.classList.add('is-intro-live');

    // Safety: if the ticker stalls (background tab, throttled frames), land on the final state anyway
    const play = () => {
      tl.play();
      setTimeout(() => { if (tl.progress() < 1) tl.progress(1); }, 4000);
    };
    if (html.classList.contains('splash-done')) play();
    else window.addEventListener('splash:done', play, { once: true });
  });
}
