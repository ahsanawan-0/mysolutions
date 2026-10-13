// read-along: statement paragraphs light up as you read. Characters go from opacity 0.2 → 1, scrubbed between
// top 80% and bottom 50% of the viewport. Never from 0 (docs/10 §9.3): unscrolled text stays readable.
import { gsap } from '../core/motion.js';
import { SplitText } from 'gsap/SplitText';
import { fontsReady, tracked } from './_util.js';

gsap.registerPlugin(SplitText);

export default function readAlong(el, ctx) {
  let split = null;
  let dead = false;
  fontsReady.then(() => {
    if (dead) return;
    tracked(ctx, () => {
      split = SplitText.create(el, {
        type: 'words,chars',
        autoSplit: true,
        charsClass: 'read-char',
        onSplit: (self) => gsap.fromTo(self.chars, { opacity: 0.2 }, {
          opacity: 1, ease: 'none', stagger: 0.05,
          scrollTrigger: { trigger: el, start: 'clamp(top 80%)', end: 'clamp(bottom 50%)', scrub: 0.4 },
        }),
      });
    });
  });
  return () => { dead = true; split?.revert(); };
}
