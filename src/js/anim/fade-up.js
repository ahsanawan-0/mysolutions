// fade-up: y 24 → 0, opacity 0 → 1, 0.7s `out`, start top 85%, once. The html.js pre-state lives in motion.css.
// Options (P10): data-x="40" data-y="-40" set the start offset in px (e.g. slide in from the top-right),
// data-delay="0.3" waits after the trigger fires (seconds).
import { gsap } from '../core/motion.js';
import { once, finish, num } from './_util.js';

export default function fadeUp(el) {
  gsap.fromTo(el, { x: num(el, 'x', 0), y: num(el, 'y', 24), autoAlpha: 0 }, {
    x: 0, y: 0, autoAlpha: 1, duration: 0.7, ease: 'out', delay: num(el, 'delay', 0),
    scrollTrigger: once(el),
    onComplete: () => finish(el),
  });
}
