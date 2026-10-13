// rise-rotate (S10, also `cta-mark`): a large faded shape behind a section rises and rotates as the section scrolls
// through the viewport (y 120 → −60, rotation −20° → 12°, scrubbed). Mobile halves the travel.
import { gsap } from '../core/motion.js';
import { scope, through, num } from './_util.js';

export default function riseRotate(el, { mobile }) {
  const k = mobile ? 0.5 : 1;
  const rise = num(el, 'rise', 180) * k;
  gsap.fromTo(el, { y: rise * 2 / 3, rotation: -20 }, {
    y: -rise / 3, rotation: 12, ease: 'none',
    scrollTrigger: through(scope(el, 'section'), { scrub: 0.5 }),
  });
}
