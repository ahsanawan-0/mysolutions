// h-scroll: horizontal scroll track (desktop only). The section pins and the [data-h-track] translates by
// (track width − viewport), scrubbed. Below 1024px (and under reduced motion / without JS) motion.css turns the
// track into a native swipe row with scroll snapping instead.
import { gsap } from '../core/motion.js';
import { refreshOnLazyImages } from './_util.js';

export default function hScroll(el, { desktop }) {
  if (!desktop) return;
  const track = el.querySelector('[data-h-track]');
  if (!track) return;
  const distance = () => Math.max(0, track.scrollWidth - el.clientWidth);
  gsap.to(track, {
    x: () => -distance(), ease: 'none',
    scrollTrigger: {
      trigger: el, start: 'top top', end: () => '+=' + distance(),
      pin: true, scrub: true, anticipatePin: 1, invalidateOnRefresh: true,
    },
  });
  refreshOnLazyImages(el);
}
