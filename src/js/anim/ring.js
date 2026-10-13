// ring (S4): a stat ring fills ([data-ring] circle, strokeDashoffset) while the number counts up ([data-count]).
// data-value="0.82" (0–1) sets the ring; the number uses the counter options (data-to, data-suffix, …).
// Plays once (toggleActions "play none none none"), 1.6s `out`.
import { gsap } from '../core/motion.js';
import { ENTER, num } from './_util.js';

export default function ring(el) {
  const circle = el.querySelector('[data-ring]') || el.querySelector('circle:last-of-type');
  const number = el.querySelector('[data-count]');
  const value = gsap.utils.clamp(0, 1, num(el, 'value', 1));
  const tl = gsap.timeline({ scrollTrigger: { trigger: el, start: ENTER, toggleActions: 'play none none none' } });

  if (circle) {
    const length = circle.getTotalLength();
    gsap.set(circle, { strokeDasharray: length, strokeDashoffset: length });
    tl.to(circle, { strokeDashoffset: length * (1 - value), duration: 1.6, ease: 'out' }, 0);
  }
  if (number) {
    const original = number.textContent;
    const to = num(number, 'to', parseFloat(original.replace(/[^\d.-]/g, '')) || 0);
    const decimals = num(number, 'decimals', 0);
    const prefix = number.dataset.prefix ?? '';
    const suffix = number.dataset.suffix ?? '';
    const format = (v) => prefix + v.toLocaleString('en-GB', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
    const state = { v: 0 };
    tl.to(state, {
      v: to, duration: 1.6, ease: 'out',
      onStart: () => { number.textContent = format(0); },
      onUpdate: () => { number.textContent = format(state.v); },
      onComplete: () => { number.textContent = original; },
    }, 0);
  }
}
