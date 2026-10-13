// split-lines: masked line reveal. SplitText lines (mask: 'lines', autoSplit), yPercent 110 → 0, stagger 0.1,
// 0.9s `out`, once. Text is never faded from opacity 0 (the lines slide up inside overflow-clipped masks), so it is
// safe for hero headings. The split is reverted once the reveal has played, restoring the original markup.
import { gsap } from '../core/motion.js';
import { SplitText } from 'gsap/SplitText';
import { once, fontsReady, tracked } from './_util.js';

gsap.registerPlugin(SplitText);

export default function splitLines(el, ctx) {
  let split = null;
  let done = false;
  fontsReady.then(() => {
    if (done) return;
    tracked(ctx, () => {
      split = SplitText.create(el, {
        type: 'lines',
        mask: 'lines',
        autoSplit: true,
        linesClass: 'split-line',
        onSplit: (self) => gsap.from(self.lines, {
          yPercent: 110, duration: 0.9, ease: 'out', stagger: 0.1,
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
