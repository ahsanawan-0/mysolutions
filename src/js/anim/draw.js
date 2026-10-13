// draw: SVG line draw, DrawSVG 0 → 100% scrubbed between top 85% and top 30%. Put data-anim on the <svg> (every
// stroked shape draws, 0.05s apart) or on a single path/line/circle.
import { gsap } from '../core/motion.js';
import { DrawSVGPlugin } from 'gsap/DrawSVGPlugin';

gsap.registerPlugin(DrawSVGPlugin);

const SHAPES = 'path, line, polyline, polygon, circle, ellipse, rect';

export default function draw(el) {
  const targets = el.matches(SHAPES) ? [el] : [...el.querySelectorAll(SHAPES)];
  if (!targets.length) return;
  gsap.fromTo(targets, { drawSVG: '0%' }, {
    drawSVG: '100%', ease: 'none', stagger: 0.05,
    scrollTrigger: { trigger: el, start: 'clamp(top 85%)', end: 'clamp(top 30%)', scrub: 0.5 },
  });
}
