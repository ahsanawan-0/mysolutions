// C25 Back-to-top: shows after one viewport, fills the progress ring (--progress 0–1) as you scroll,
// scrolls to the top through Lenis when P06 exposes window.lenis, otherwise native smooth scroll.
import { reducedMotion } from '../core/eases.js';

export function initBackToTop() {
  const button = document.querySelector('[data-back-to-top]');
  if (!button) return;
  let ticking = false;

  const update = () => {
    ticking = false;
    const max = document.documentElement.scrollHeight - window.innerHeight;
    const y = window.scrollY;
    const progress = max > 0 ? Math.min(1, Math.max(0, y / max)) : 0;
    button.style.setProperty('--progress', progress.toFixed(4));
    button.classList.toggle('is-visible', y > window.innerHeight);
  };

  window.addEventListener('scroll', () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  }, { passive: true });
  window.addEventListener('resize', update);

  button.addEventListener('click', () => {
    if (window.lenis?.scrollTo) {
      window.lenis.scrollTo(0, { immediate: reducedMotion() });
    } else {
      window.scrollTo({ top: 0, behavior: reducedMotion() ? 'auto' : 'smooth' });
    }
    // The button hides once at the top; move focus to the page so it never rests on an invisible control
    document.getElementById('main')?.focus({ preventScroll: true });
  });

  update();
}
