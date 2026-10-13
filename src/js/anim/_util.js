// Shared helpers for the data-anim effects. Every effect is `export default (el, ctx) => cleanup?`
// where ctx = { desktop, mobile, fine (hover + fine pointer), context (the gsap.matchMedia context) }.
import { gsap, ScrollTrigger } from '../core/motion.js';

/** Numeric data attribute with a fallback: num(el, 'speed', 0.8) reads data-speed. */
export const num = (el, name, fallback) => {
  const v = parseFloat(el.dataset[name]);
  return Number.isFinite(v) ? v : fallback;
};

/** Entrance reveals start no lower than top 85% and are clamped so elements near the end of short pages still fire. */
export const ENTER = 'clamp(top 85%)';

/** ScrollTrigger vars for a play-once entrance. */
export const once = (el, extra = {}) => ({ trigger: el, start: ENTER, once: true, ...extra });

/** ScrollTrigger vars for a scrub over the element's whole journey through the viewport (docs/06 §6). */
export const through = (el, extra = {}) => ({ trigger: el, start: 'top bottom', end: 'bottom top', scrub: true, ...extra });

export const fontsReady = document.fonts?.ready ?? Promise.resolve();

/** The picture/image inside a wrapper (or the element itself). */
export const innerMedia = (el) =>
  el.matches('img, svg, video') ? el : el.querySelector('picture img, img, .img-placeholder, video, svg') ?? el;

/** Closest ancestor matching `selector`, else the parent, else the element. */
export const scope = (el, selector) => el.closest(selector) || el.parentElement || el;

/** Re-measure pinned sections once their lazy images have loaded (docs/06 §5). */
export function refreshOnLazyImages(el) {
  el.querySelectorAll('img[loading="lazy"]').forEach((img) => {
    if (!img.complete) img.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });
  });
}

/** Mark a one-shot reveal as finished: the html.js pre-state no longer applies and GSAP's inline styles are removed. */
export function finish(targets) {
  gsap.utils.toArray(targets).forEach((t) => t.classList.add('is-done'));
  gsap.set(targets, { clearProps: 'all' });
}

/** Run `fn` inside the matchMedia context so animations created later (after fonts load) are still reverted with it. */
export const tracked = (ctx, fn) => (ctx?.context ? ctx.context.add(fn) : fn());

export const clamp = gsap.utils.clamp;
