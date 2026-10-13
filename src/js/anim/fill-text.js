// fill-text: image/gradient-filled numerals pan their background from 0% to 100% as they travel through the viewport.
// Pair with the .fill-text utility (motion.css) or any background-clip: text style with background-size > 100%.
import { gsap } from '../core/motion.js';
import { through } from './_util.js';

export default function fillText(el) {
  gsap.fromTo(el, { backgroundPosition: '0% 50%' }, { backgroundPosition: '100% 50%', ease: 'none', scrollTrigger: through(el) });
}
