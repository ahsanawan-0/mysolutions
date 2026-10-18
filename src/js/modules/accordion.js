// accordion: GSAP height animation for the C17 <details> accordion (data-module="accordion",
// templates/components/accordion.php). The native element stays the source of truth: the module intercepts summary
// clicks (mouse, Enter and Space all arrive as a click), animates the panel, then sets or clears `open`. Items that
// share a <details name> stay "one open at a time": the module animates the others closed (the native behaviour would
// snap them shut), so the name is lifted while the module runs and put back under reduced motion, where the browser's
// own toggle is used and nothing animates.
import { gsap } from '../core/motion.js';

const reduceMQ = matchMedia('(prefers-reduced-motion: reduce)');
const OPEN = { duration: 0.45, ease: 'apple' };
const CLOSE = { duration: 0.32, ease: 'apple' };

export default function accordion(root) {
  const items = [...root.querySelectorAll(':scope > details')];
  if (!items.length) return;
  const names = new Map();
  let enhanced = false;

  const panel = (d) => d.querySelector(':scope > .accordion__panel');
  const groupOf = (d) => names.get(d) ?? null;

  const open = (d) => {
    const p = panel(d);
    const group = groupOf(d);
    if (group) items.forEach((o) => { if (o !== d && o.open && groupOf(o) === group) close(o); });
    gsap.killTweensOf(p);
    d.classList.remove('is-closing');
    d.open = true;
    gsap.fromTo(p, { height: 0, opacity: 0 }, { height: 'auto', opacity: 1, ...OPEN, onComplete: () => gsap.set(p, { clearProps: 'height,opacity' }) });
  };
  const close = (d) => {
    const p = panel(d);
    gsap.killTweensOf(p);
    d.classList.add('is-closing');
    gsap.to(p, { height: 0, opacity: 0, ...CLOSE, onComplete: () => {
      d.open = false;
      d.classList.remove('is-closing');
      gsap.set(p, { clearProps: 'height,opacity' });
    } });
  };

  const onClick = (e) => {
    const summary = e.target.closest('summary');
    const d = summary?.parentElement;
    if (!summary || !d || !items.includes(d)) return;
    e.preventDefault();
    d.open && !d.classList.contains('is-closing') ? close(d) : open(d);
  };

  const enhance = () => {
    if (enhanced) return;
    enhanced = true;
    items.forEach((d) => {
      if (d.hasAttribute('name')) {
        names.set(d, d.getAttribute('name'));
        d.removeAttribute('name');
      }
    });
    root.addEventListener('click', onClick);
    root.classList.add('is-enhanced');
  };
  const restore = () => {
    if (!enhanced) return;
    enhanced = false;
    root.removeEventListener('click', onClick);
    root.classList.remove('is-enhanced');
    items.forEach((d) => {
      gsap.killTweensOf(panel(d));
      gsap.set(panel(d), { clearProps: 'height,opacity' });
      d.classList.remove('is-closing');
      const n = names.get(d);
      if (n) d.setAttribute('name', n);
    });
  };

  const apply = () => (reduceMQ.matches ? restore() : enhance());
  apply();
  reduceMQ.addEventListener('change', apply);

  // Deep link (#faq-cost): open the target instantly so the answer is readable
  const reveal = () => {
    const target = location.hash.length > 1 && root.querySelector(`:scope > details#${CSS.escape(decodeURIComponent(location.hash.slice(1)))}`);
    if (target && !target.open) target.open = true;
  };
  reveal();
  window.addEventListener('hashchange', reveal);
}
