// blur-zoom (S3): a heading zooms out of a blur as it reaches the centre of the viewport (scale 2 → 1,
// blur 15px → 0, scrubbed from "top bottom" to "center center"). Desktop only; reserved for the stats heading.
import { gsap } from '../core/motion.js';

export default function blurZoom(el, { desktop }) {
  if (!desktop) return;
  gsap.fromTo(el, { scale: 2, filter: 'blur(15px)', autoAlpha: 0 }, {
    scale: 1, filter: 'blur(0px)', autoAlpha: 1, ease: 'none',
    scrollTrigger: { trigger: el, start: 'clamp(top bottom)', end: 'clamp(center center)', scrub: true },
  });
}
