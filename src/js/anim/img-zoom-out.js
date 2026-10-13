// img-zoom-out: Ken-Burns on scroll. The inner image scales 1.15 → 1 while its section travels through the viewport.
// The wrapper gets overflow: hidden from motion.css.
import { gsap } from '../core/motion.js';
import { innerMedia, scope, through } from './_util.js';

export default function imgZoomOut(el) {
  const media = innerMedia(el);
  gsap.fromTo(media, { scale: 1.15 }, { scale: 1, ease: 'none', scrollTrigger: through(scope(el, 'section')) });
}
