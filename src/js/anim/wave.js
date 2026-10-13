// wave: section divider morph. The <path data-anim="wave" data-wave-alt="…"> morphs into its alternate path
// (MorphSVG) while the divider travels through the viewport, scrubbed.
import { gsap } from '../core/motion.js';
import { MorphSVGPlugin } from 'gsap/MorphSVGPlugin';
import { scope, through } from './_util.js';

gsap.registerPlugin(MorphSVGPlugin);

export default function wave(el) {
  const alt = el.dataset.waveAlt;
  if (!alt) return;
  gsap.to(el, { morphSVG: alt, ease: 'none', scrollTrigger: through(scope(el, '.divider'), { scrub: 0.5 }) });
}
