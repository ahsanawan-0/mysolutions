// C44 reading progress (data-module="reading-progress", sections/insights/article.php, P26): the bar under the header
// scales from 0 to 1 while the article body (data-target) scrolls past, from its top reaching 25 % of the viewport
// to its bottom reaching the bottom edge. Scroll-linked, never autoplaying, so it also runs under reduced motion.
import { gsap, ScrollTrigger } from '../core/motion.js';

export default function readingProgress(el) {
  const target = document.querySelector(el.dataset.target || 'main');
  const bar = el.querySelector('.reading-progress__bar');
  if (!target || !bar) return;
  const set = gsap.quickSetter(bar, 'scaleX');
  ScrollTrigger.create({
    trigger: target,
    start: 'top 25%',
    end: 'bottom bottom',
    onUpdate: (self) => set(self.progress),
    onRefresh: (self) => set(self.progress),
  });
}
