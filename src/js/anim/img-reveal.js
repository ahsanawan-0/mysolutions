// img-reveal: clip-path inset(100% 0 0 0 round r) → inset(0 round r) while the inner image settles from scale 1.2
// to 1, scrubbed between top 85% and top 35% of the viewport. r = the wrapper's own border radius (token).
import { gsap } from '../core/motion.js';
import { innerMedia } from './_util.js';

export default function imgReveal(el) {
  const media = innerMedia(el);
  const r = getComputedStyle(el).borderTopLeftRadius || '0px';
  const tl = gsap.timeline({
    scrollTrigger: { trigger: el, start: 'clamp(top 85%)', end: 'clamp(top 35%)', scrub: 0.5 },
  });
  tl.fromTo(el, { clipPath: `inset(100% 0 0 0 round ${r})` }, { clipPath: `inset(0% 0 0 0 round ${r})`, ease: 'none' }, 0);
  if (media !== el) tl.fromTo(media, { scale: 1.2 }, { scale: 1, ease: 'none' }, 0);
}
