// stagger: the children fade-up in sequence (0.08s apart). Uses ScrollTrigger.batch so card grids that span more
// than one viewport reveal row by row. Options: data-stagger="0.08", data-stagger-target="<selector>".
import { gsap, ScrollTrigger } from '../core/motion.js';
import { ENTER, num, finish } from './_util.js';

export default function stagger(el) {
  const targets = el.dataset.staggerTarget ? [...el.querySelectorAll(el.dataset.staggerTarget)] : [...el.children];
  if (!targets.length) return;
  const each = num(el, 'stagger', 0.08);
  gsap.set(targets, { y: 24, autoAlpha: 0 });
  ScrollTrigger.batch(targets, {
    start: ENTER,
    once: true,
    onEnter: (batch) => gsap.to(batch, {
      y: 0, autoAlpha: 1, duration: 0.7, ease: 'out', stagger: each, overwrite: true,
      onComplete: () => finish(batch),
    }),
  });
}
