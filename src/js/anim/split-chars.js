// split-chars: display word reveal, character by character (stagger 0.02). Use at most once per page and never on
// the hero H1 (docs/10 §9.4): characters rise from 0.5em and fade in, 0.6s `out`, once.
import { gsap } from '../core/motion.js';
import { SplitText } from 'gsap/SplitText';
import { once, fontsReady, tracked } from './_util.js';

gsap.registerPlugin(SplitText);

export default function splitChars(el, ctx) {
  let split = null;
  let done = false;
  fontsReady.then(() => {
    if (done) return;
    tracked(ctx, () => {
      split = SplitText.create(el, {
        type: 'chars,words',
        autoSplit: true,
        charsClass: 'split-char',
        onSplit: (self) => gsap.from(self.chars, {
          y: '0.5em', autoAlpha: 0, duration: 0.6, ease: 'out', stagger: 0.02,
          scrollTrigger: once(el),
          onComplete: () => {
            done = true;
            self.revert();
            el.classList.add('is-done');
            gsap.set(el, { clearProps: 'all' });
          },
        }),
      });
      gsap.set(el, { visibility: 'visible' });
    });
  });
  return () => { done = true; split?.revert(); };
}
