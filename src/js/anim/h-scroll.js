// h-scroll: horizontal scroll track (desktop only). The section pins and the [data-h-track] translates by
// (track width − viewport), scrubbed. Below 1024px (and under reduced motion / without JS) motion.css turns the
// track into a native swipe row with scroll snapping instead.
// Options (P12):
//   [data-h-viewport]  an inner element whose width is the visible window for the track (default: the section),
//                      so a fixed intro column can sit beside the track without being translated.
//   [data-h-progress]  an element scaled on the x axis from 0 → 1 with the pin progress (a thin progress bar).
// Keyboard: focusing a link inside the track scrolls the page so the pinned track shows that item (otherwise
// Tab would land on cards translated out of the clipped area).
import { gsap } from '../core/motion.js';
import { refreshOnLazyImages } from './_util.js';

export default function hScroll(el, { desktop }) {
  if (!desktop) return;
  const track = el.querySelector('[data-h-track]');
  if (!track) return;
  const viewport = el.querySelector('[data-h-viewport]') || el;
  const bar = el.querySelector('[data-h-progress]');
  const distance = () => Math.max(0, track.scrollWidth - viewport.clientWidth);
  const tween = gsap.to(track, {
    x: () => -distance(), ease: 'none',
    scrollTrigger: {
      trigger: el, start: 'top top', end: () => '+=' + distance(),
      pin: true, scrub: true, anticipatePin: 1, invalidateOnRefresh: true,
      onUpdate: bar ? (self) => gsap.set(bar, { scaleX: self.progress }) : undefined,
    },
  });
  if (bar) gsap.set(bar, { scaleX: 0 });
  refreshOnLazyImages(el);

  const onFocus = (e) => {
    const item = e.target.closest('[data-h-item]') || e.target;
    // the browser scrolls overflow-hidden ancestors to reveal a focused element; undo that, the page scroll does it
    for (let a = track.parentElement; a && a !== el; a = a.parentElement) {
      if (a.scrollLeft) a.scrollLeft = 0;
    }
    const st = tween.scrollTrigger;
    const d = distance();
    if (!st || d === 0) return;
    const left = item.offsetLeft;
    const right = left + item.offsetWidth;
    const shown = st.progress * d;
    const vw = viewport.clientWidth;
    if (left >= shown && right <= shown + vw) return;   // already visible
    const p = gsap.utils.clamp(0, 1, (left + item.offsetWidth / 2 - vw / 2) / d);
    const y = st.start + p * (st.end - st.start);
    if (window.lenis) window.lenis.scrollTo(y, { immediate: true });
    else window.scrollTo(0, y);
  };
  track.addEventListener('focusin', onFocus);
  return () => track.removeEventListener('focusin', onFocus);
}
