// stack-cards: sticky stacking cards. motion.css makes every child position: sticky (so the stack works without JS);
// this scrubs each covered card to scale 0.94 and dims it while the next card slides over it.
import { gsap } from '../core/motion.js';

export default function stackCards(el) {
  const cards = [...el.children];
  if (cards.length < 2) return;
  const top = () => parseFloat(getComputedStyle(cards[0]).top) || 0;
  cards.forEach((card, i) => {
    const next = cards[i + 1];
    if (!next) return;
    gsap.to(card, {
      scale: 0.94, opacity: 0.6, ease: 'none',
      scrollTrigger: { trigger: next, start: 'top bottom', end: () => 'top ' + top() + 'px', scrub: true, invalidateOnRefresh: true },
    });
  });
}
