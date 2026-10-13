// two-voice: the two-voice heading (C02). Line 1 slides in from the left (40px); the italic <em> accent clips in from
// the right with a slight skew (−8° → 0). The heading stays one element: line 1 is wrapped in a span for the tween
// and unwrapped again when the reveal has played.
import { gsap } from '../core/motion.js';
import { once, finish } from './_util.js';

export default function twoVoice(el) {
  const em = el.querySelector('em');
  const line = document.createElement('span');
  line.className = 'two-voice__line';
  const nodes = [];
  for (const node of el.childNodes) {
    if (node === em) break;
    nodes.push(node);
  }
  if (!nodes.length && !em) return;
  if (nodes.length) {
    el.insertBefore(line, nodes[0]);
    nodes.forEach((n) => line.append(n));
  }

  const tl = gsap.timeline({
    scrollTrigger: once(el),
    onComplete: () => {
      finish(el);
      if (em) gsap.set(em, { clearProps: 'all' });
      line.replaceWith(...line.childNodes);
    },
  });
  gsap.set(el, { visibility: 'visible' });
  if (nodes.length) tl.fromTo(line, { x: -40, autoAlpha: 0 }, { x: 0, autoAlpha: 1, duration: 0.8, ease: 'out' }, 0);
  if (em) {
    tl.fromTo(em,
      { clipPath: 'inset(0 100% 0 0)', skewX: -8, x: 24, display: 'inline-block' },
      { clipPath: 'inset(0 0% 0 0)', skewX: 0, x: 0, duration: 0.9, ease: 'out' },
      nodes.length ? 0.15 : 0,
    );
  }
  return () => { if (line.parentNode) line.replaceWith(...line.childNodes); };
}
