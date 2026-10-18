// 404 logo mark (data-module="nf-mark", templates/pages/404.php, P29; docs/07 §6): after the splash the mark
// assembles (hexagon rises, the bars slide up into place), then the navy bar wobbles and falls over to the right,
// pivoting on its foot. The mark becomes a button: click, Enter or Space puts the bar back up with a spring, and the
// next press knocks it over again; its label says which. Under reduced motion nothing runs and the mark stays a
// static, decorative picture (aria-hidden, not focusable).
import { gsap, reducedMotion } from '../core/motion.js';

const FOOT = '587 366';   // the navy bar's bottom-right corner in the logo's viewBox (brand/logo-mark.svg)
// Lying down: 120° turns its long right edge horizontal; lifting it 91 units sets that edge on the ground (y = 366)
const DOWN = { rotation: 120, y: -91 };

export default function nfMark(root) {
  if (reducedMotion()) return;
  const piece = (name) => root.querySelector(`[data-piece="${name}"]`);
  const hex = piece('hex-sky');
  const azure = piece('bar-azure');
  const navy = piece('bar-navy');
  if (!hex || !azure || !navy) return;

  let fallen = false;
  let busy = true;
  const label = () => root.setAttribute('aria-label', fallen ? root.dataset.labelRebuild : root.dataset.labelKnock);

  const knock = () => {
    busy = true;
    gsap.timeline({ onComplete: () => { fallen = true; busy = false; label(); } })
      .to(navy, { rotation: -7, svgOrigin: FOOT, duration: 0.28, ease: 'power1.inOut' })
      .to(navy, { ...DOWN, svgOrigin: FOOT, duration: 1, ease: 'bounce.out' });
  };
  const rebuild = () => {
    busy = true;
    gsap.to(navy, { rotation: 0, y: 0, svgOrigin: FOOT, duration: 0.8, ease: 'back.out(2.2)', onComplete: () => { fallen = false; busy = false; label(); } });
  };
  const toggle = () => { if (!busy) (fallen ? rebuild : knock)(); };

  const start = () => {
    root.classList.add('is-live');
    gsap.timeline({ onComplete: () => gsap.delayedCall(0.5, knock) })
      .from(hex, { y: 70, opacity: 0, duration: 0.55, ease: 'back.out(1.7)' })
      .from([azure, navy], { x: -50, y: 86, opacity: 0, duration: 0.6, ease: 'power3.out', stagger: 0.12 }, '-=0.3');
  };

  // A real button for keyboard and assistive technology users (the markup stays decorative without motion)
  root.removeAttribute('aria-hidden');
  root.setAttribute('role', 'button');
  root.tabIndex = 0;
  label();
  root.addEventListener('click', toggle);
  root.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      toggle();
    }
  });

  if (document.documentElement.classList.contains('splash-done')) start();
  else window.addEventListener('splash:done', start, { once: true });
}
