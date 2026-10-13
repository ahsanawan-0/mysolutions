// parallax: data-speed="0.8" → the layer moves at 80% of the scroll speed: y offset = (1 − speed) × distance,
// scrubbed over the scope's journey through the viewport. Mobile halves the distance (docs/12 §3.8).
// The trigger is the closest [data-parallax-scope] (or the parent) so the moving layer never measures itself.
import { gsap } from '../core/motion.js';
import { num, scope, through } from './_util.js';

export default function parallax(el, { mobile }) {
  const speed = num(el, 'speed', 0.8);
  const k = (1 - speed) * (mobile ? 0.5 : 1);
  if (k === 0) return;
  const container = scope(el, '[data-parallax-scope]');
  const travel = () => window.innerHeight + container.offsetHeight;
  gsap.fromTo(el, { y: () => -k * travel() / 2 }, {
    y: () => k * travel() / 2,
    ease: 'none',
    scrollTrigger: through(container, { invalidateOnRefresh: true }),
  });
}
