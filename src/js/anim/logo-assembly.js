// logo-assembly (S1, the signature): the three pieces of the mark ([data-piece] inside the SVG) fly in and assemble,
// scrubbed from "top bottom" to "top 35%". Each piece may set data-from="x,y,rotation"; defaults fan out along the
// brand's 120° axes. Use once per page.
import { gsap } from '../core/motion.js';
import { scope } from './_util.js';

const DEFAULTS = [[-140, 90, -120], [0, -160, 120], [140, 90, -120]];

export default function logoAssembly(el, { mobile }) {
  const pieces = [...el.querySelectorAll('[data-piece]')];
  if (!pieces.length) return;
  const k = mobile ? 0.6 : 1;
  const tl = gsap.timeline({
    scrollTrigger: { trigger: scope(el, '[data-assembly-scope]'), start: 'top bottom', end: 'top 35%', scrub: 0.6 },
  });
  pieces.forEach((piece, i) => {
    const [x, y, rotation] = (piece.dataset.from || '').split(',').map(Number).filter(Number.isFinite).length === 3
      ? piece.dataset.from.split(',').map(Number)
      : DEFAULTS[i % DEFAULTS.length];
    tl.from(piece, { x: x * k, y: y * k, rotation, scale: 2, autoAlpha: 0, transformOrigin: '50% 50%', ease: 'none', duration: 0.9 }, i * 0.05);
  });
}
