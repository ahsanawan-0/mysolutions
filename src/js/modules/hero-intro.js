// Hero entrance + scroll parallax (docs/05 §6 🔒; data-module="hero-intro" on .hero).
// Entrance: the pre-states come from CSS under html.js (home.css) so there is no layout shift and nothing is injected
// after paint; this module builds the GSAP timeline, takes the pre-states over (.is-intro-live), waits for
// `splash:done` (or runs at once when the splash already finished or never played) and plays:
//   0.00 panel clip-path opens · 0.10 photo scale 1.15 → 1 (opacity stays 1: it is the LCP element) · 0.30 nav
//   items · 0.45 H1 lines slide up inside SplitText masks (no opacity) · 0.70 lead · 0.80
//   Quick-Start bar · 0.90 cards slide in from +80 and the frame draws itself (--draw) then starts orbiting ·
//   1.10 helper + quick links · 1.20 mini card + chips stagger.
// Scroll: photo yPercent 12, copy yPercent −8 + fade to .6, stage yPercent −14, panel radius 28 → 40 (scrubbed;
// mobile halves the distances). Reduced motion: no entrance, no parallax (the static page as rendered).
import { gsap, ScrollTrigger, reducedMotion } from '../core/motion.js';
import { SplitText } from 'gsap/SplitText';
import { fontsReady, innerMedia } from '../anim/_util.js';

gsap.registerPlugin(SplitText);

export default function heroIntro(hero) {
  const html = document.documentElement;
  const q = (s) => [...hero.querySelectorAll(s)];
  const one = (s) => hero.querySelector(s);
  const panel = one('.hero__panel');
  const photoLayer = one('.hero__photo');
  const photo = photoLayer ? innerMedia(photoLayer) : null;
  const copy = one('.hero__copy');
  const stage = one('.hero__stage');
  const title = one('.hero__title');
  const frame = one('.special-frame');

  const finishStatic = () => {
    hero.classList.add('is-intro-done');
    frame?.classList.add('is-live');
  };
  if (reducedMotion()) {
    finishStatic();
    return;
  }

  const mobile = matchMedia('(max-width: 1023px)').matches;
  const k = mobile ? 0.5 : 1;
  const radius = getComputedStyle(panel).borderRadius || '28px';
  const navItems = [...q('.site-header--hero .site-nav__item'), one('.site-header--hero .site-header__phone'), one('.site-header--hero .site-header__cta')].filter(Boolean);

  const parallax = () => {
    gsap.timeline({ scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true, invalidateOnRefresh: true } })
      .to(photo, { yPercent: 12 * k, ease: 'none' }, 0)   // the image inside its clipped layer, never the layer itself
      .to(copy, { yPercent: -8 * k, opacity: 0.6, ease: 'none' }, 0)
      .to(stage, { yPercent: -14 * k, ease: 'none' }, 0)
      .to(panel, { borderRadius: '40px', ease: 'none' }, 0);
  };

  fontsReady.then(() => {
    // Measured now, after hero-slider.js has laid the cards out: only the three visible cards slide in
    const cardLinks = q('.hero__card').filter((c) => c.getAttribute('aria-hidden') !== 'true' && getComputedStyle(c).visibility !== 'hidden').map((c) => c.querySelector('.hero__card-link')).filter(Boolean);
    const chips = q('.hero__chip-item').filter((c) => getComputedStyle(c).display !== 'none');
    const split = SplitText.create(title, { type: 'lines', mask: 'lines', linesClass: 'hero__line' });
    const tl = gsap.timeline({
      paused: true,
      defaults: { ease: 'expo.out' },
      onComplete: () => {
        split.revert();
        gsap.set([panel, photo, navItems, one('.hero__lead'), one('.quick-start'), cardLinks, one('.hero__helper'), one('.hero__links'), one('.hero__controls'), one('.hero__mini'), chips].filter(Boolean), { clearProps: 'all' });
        hero.classList.add('is-intro-done');
        parallax();
      },
    });
    tl.fromTo(panel, { clipPath: `inset(5% 4% 5% 4% round ${radius})` }, { clipPath: `inset(0% 0% 0% 0% round ${radius})`, duration: 0.9 }, 0)
      .fromTo(photo, { scale: 1.15, transformOrigin: '60% 50%' }, { scale: 1, duration: 1.4, ease: 'power2.out' }, 0.1)   // opacity stays 1 (LCP)
      .fromTo(navItems, { y: -12, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.6, stagger: 0.05 }, 0.3)
      .from(split.lines, { yPercent: 110, duration: 0.9, stagger: 0.08 }, 0.45)
      .fromTo(one('.hero__lead'), { y: 16, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.7 }, 0.7)
      .fromTo(one('.quick-start'), { y: 24, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.7 }, 0.8)
      .fromTo(cardLinks, { x: 80, autoAlpha: 0 }, { x: 0, autoAlpha: 1, duration: 0.8, stagger: 0.08 }, 0.9);
    if (frame) {
      tl.fromTo(frame, { '--draw': '0%' }, { '--draw': '100%', duration: 0.8, ease: 'power2.inOut', onComplete: () => frame.classList.add('is-live') }, 0.9);
    }
    tl.fromTo([one('.hero__helper'), one('.hero__links'), one('.hero__controls')].filter(Boolean), { y: 16, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.6, stagger: 0.05 }, 1.1)
      .fromTo(one('.hero__mini'), { y: 40, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.7 }, 1.2)
      .fromTo(chips, { y: 40, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.7, stagger: 0.06 }, 1.2);

    // GSAP now holds the pre-states (the timeline sits at 0), so the CSS pre-states can go
    tl.progress(0);
    hero.classList.add('is-intro-live');

    // Safety: if the ticker stalls (tab opened in the background, throttled frames), land on the final state anyway
    const play = () => {
      tl.play();
      setTimeout(() => { if (tl.progress() < 1) tl.progress(1); }, 4000);
    };
    if (html.classList.contains('splash-done')) play();
    else window.addEventListener('splash:done', play, { once: true });
  });
}
