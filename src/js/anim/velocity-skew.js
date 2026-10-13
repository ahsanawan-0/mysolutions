// velocity-skew: images skew up to ±4° with the scroll velocity, then settle. Desktop only (docs/12 §3.8).
// One shared ScrollTrigger feeds every element on the page.
import { gsap, ScrollTrigger } from '../core/motion.js';
import { num, clamp } from './_util.js';

const targets = new Map();
let trigger = null;
let settle = null;

function ensureTrigger() {
  if (trigger) return;
  trigger = ScrollTrigger.create({
    onUpdate: (self) => {
      const v = self.getVelocity();
      targets.forEach((set, el) => set(clamp(-1, 1, -v / 1200) * num(el, 'skew', 4)));
      settle?.kill();
      settle = gsap.delayedCall(0.12, () => targets.forEach((set) => set(0)));
    },
  });
}

export default function velocitySkew(el, { desktop }) {
  if (!desktop) return;
  const set = gsap.quickTo(el, 'skewY', { duration: 0.5, ease: 'out' });
  targets.set(el, set);
  ensureTrigger();
  return () => {
    targets.delete(el);
    gsap.set(el, { clearProps: 'transform' });
    if (!targets.size) {
      trigger?.kill();
      trigger = null;
      settle?.kill();
    }
  };
}
