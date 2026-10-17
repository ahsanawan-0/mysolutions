// success-check: the thank-you page's hex check mark (data-module="success-check", templates/pages/thank-you.php, P24).
// Plays once, after the splash hands off: the pale hex pops in, the ring draws round, then the tick draws.
// The server HTML is the finished mark; thank-you.css hides its parts under html.js only while motion is allowed, and
// this module claims them at once (.is-anim) with GSAP's from-state, so nothing flashes. Reduced motion: no animation.
import { gsap, reducedMotion } from '../core/motion.js';
import { DrawSVGPlugin } from 'gsap/DrawSVGPlugin';

gsap.registerPlugin(DrawSVGPlugin);

export default function successCheck(root) {
  const fill = root.querySelector('[data-check-fill]');
  const ring = root.querySelector('[data-check-ring]');
  const tick = root.querySelector('[data-check-tick]');
  if (!fill || !ring || !tick || reducedMotion()) return;

  const tl = gsap.timeline({ paused: true, defaults: { ease: 'out' } });
  tl.fromTo(fill, { scale: 0.6, opacity: 0, svgOrigin: '48 48' }, { scale: 1, opacity: 1, duration: 0.5, ease: 'spring' })
    .fromTo(ring, { drawSVG: '0%' }, { drawSVG: '100%', duration: 0.7, ease: 'inout' }, 0.05)
    .fromTo(tick, { drawSVG: '0%' }, { drawSVG: '100%', duration: 0.45 }, 0.55);
  root.classList.add('is-anim');

  const play = () => tl.play();
  if (document.documentElement.classList.contains('splash-done')) play();
  else window.addEventListener('splash:done', play, { once: true });
}
