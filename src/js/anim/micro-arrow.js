// micro-arrow (S2): the micro-CTA arrow points right, turns to point down at 50–60% of the row's journey through
// the viewport, then drops 88px (44px on mobile). Scrubbed (0.3) over the whole .micro-cta row.
import { gsap } from '../core/motion.js';
import { scope, through } from './_util.js';

export default function microArrow(el, { mobile }) {
  const row = scope(el, '.micro-cta');
  const tl = gsap.timeline({ scrollTrigger: through(row, { scrub: 0.3 }) });
  tl.fromTo(el, { rotation: 0 }, { rotation: 90, duration: 0.1, ease: 'none' }, 0.5)
    .to(el, { y: mobile ? 44 : 88, duration: 0.25, ease: 'none' }, 0.6);
}
