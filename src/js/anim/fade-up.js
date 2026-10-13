// fade-up: y 24 → 0, opacity 0 → 1, 0.7s `out`, start top 85%, once. The html.js pre-state lives in motion.css.
import { gsap } from '../core/motion.js';
import { once, finish } from './_util.js';

export default function fadeUp(el) {
  gsap.fromTo(el, { y: 24, autoAlpha: 0 }, {
    y: 0, autoAlpha: 1, duration: 0.7, ease: 'out',
    scrollTrigger: once(el),
    onComplete: () => finish(el),
  });
}
