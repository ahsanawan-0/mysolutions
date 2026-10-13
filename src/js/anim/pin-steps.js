// pin-steps: pinned process. On desktop the section is pinned for N × 100vh; the active step ([data-step]) and panel
// ([data-step-panel]) change at progress thresholds and the progress line ([data-step-progress="y|x"]) fills.
// On mobile (and without JS) the steps simply stack; each becomes active as it passes 60% of the viewport.
// The pin-spacer is created on init (ScrollTrigger.refresh in the runner), before the user reaches the section.
import { gsap, ScrollTrigger } from '../core/motion.js';
import { refreshOnLazyImages } from './_util.js';

export default function pinSteps(el, { desktop }) {
  const steps = [...el.querySelectorAll('[data-step]')];
  const panels = [...el.querySelectorAll('[data-step-panel]')];
  const line = el.querySelector('[data-step-progress]');
  const n = steps.length;
  if (!n) return;

  const activate = (i) => {
    steps.forEach((s, j) => s.classList.toggle('is-active', j === i));
    panels.forEach((p, j) => p.classList.toggle('is-active', j === i));
    el.style.setProperty('--step', String(i));
  };
  activate(0);

  if (!desktop) {
    steps.forEach((step, i) => ScrollTrigger.create({
      trigger: step, start: 'top 60%', end: 'bottom 60%',
      onToggle: (self) => self.isActive && activate(i),
    }));
    return;
  }

  const end = () => '+=' + n * window.innerHeight;
  ScrollTrigger.create({
    trigger: el, start: el.dataset.pinStart || 'top top', end,
    pin: true, scrub: true, anticipatePin: 1, invalidateOnRefresh: true,
    onUpdate: (self) => activate(Math.min(n - 1, Math.floor(self.progress * n))),
  });
  if (line) {
    const axis = line.dataset.stepProgress === 'x' ? 'scaleX' : 'scaleY';
    gsap.fromTo(line, { [axis]: 0 }, {
      [axis]: 1, ease: 'none',
      scrollTrigger: { trigger: el, start: el.dataset.pinStart || 'top top', end, scrub: true, invalidateOnRefresh: true },
    });
  }
  refreshOnLazyImages(el);
  return () => activate(0);
}
