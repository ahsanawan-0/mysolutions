// Our Story (About A2, PROMPTS P18; data-module="about-story"): the sticky photo column crossfades to the photo of
// the paragraph being read. Each paragraph carries data-story-step="n" (n = index of its photo); the step crossing
// 55 % of the viewport wins. The fade itself is a CSS opacity transition (none under reduced motion); below 1024 only
// the first photo is shown (about.css), so this only matters on desktop.
import { ScrollTrigger } from '../core/motion.js';

export default function aboutStory(section) {
  const photos = [...section.querySelectorAll('[data-story-img]')];
  if (photos.length < 2) return;
  const show = (n) => photos.forEach((el) => el.classList.toggle('is-active', el.dataset.storyImg === n));
  section.querySelectorAll('[data-story-step]').forEach((step) => {
    ScrollTrigger.create({
      trigger: step,
      start: 'top 55%',
      end: 'bottom 55%',
      onToggle: (self) => { if (self.isActive) show(step.dataset.storyStep); },
    });
  });
}
