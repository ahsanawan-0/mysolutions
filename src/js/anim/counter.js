// counter: number count-up from 0, 1.6s `out`, once. The server renders the final text ("10+"); the tween only
// replaces it while counting. data-to="10" data-suffix="+" data-prefix="£" data-decimals="1".
import { gsap } from '../core/motion.js';
import { once, num } from './_util.js';

export default function counter(el) {
  const original = el.textContent;
  const to = num(el, 'to', parseFloat(original.replace(/[^\d.-]/g, '')) || 0);
  const decimals = num(el, 'decimals', 0);
  const prefix = el.dataset.prefix ?? '';
  const suffix = el.dataset.suffix ?? '';
  const format = (v) => prefix + v.toLocaleString('en-GB', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
  const state = { v: 0 };
  gsap.to(state, {
    v: to, duration: 1.6, ease: 'out',
    scrollTrigger: once(el),
    onStart: () => { el.textContent = format(0); },
    onUpdate: () => { el.textContent = format(state.v); },
    onComplete: () => { el.textContent = original; el.classList.add('is-done'); },
  });
}
