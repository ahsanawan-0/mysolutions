// tilt: 3D card tilt (max 6°, data-tilt="6") with a glare highlight that follows the pointer.
// Pointer devices on desktop only; transformPerspective 1000 (docs/06 §6 S7).
import { gsap } from '../core/motion.js';
import { num } from './_util.js';

export default function tilt(el, { desktop, fine }) {
  if (!desktop || !fine) return;
  const max = num(el, 'tilt', 6);
  const glare = document.createElement('span');
  glare.className = 'tilt__glare';
  glare.setAttribute('aria-hidden', 'true');
  el.append(glare);
  gsap.set(el, { transformPerspective: 1000, transformStyle: 'preserve-3d' });
  const rx = gsap.quickTo(el, 'rotationX', { duration: 0.5, ease: 'out' });
  const ry = gsap.quickTo(el, 'rotationY', { duration: 0.5, ease: 'out' });
  const move = (e) => {
    const r = el.getBoundingClientRect();
    const px = (e.clientX - r.left) / r.width;
    const py = (e.clientY - r.top) / r.height;
    ry((px - 0.5) * 2 * max);
    rx(-(py - 0.5) * 2 * max);
    glare.style.setProperty('--gx', (px * 100).toFixed(1) + '%');
    glare.style.setProperty('--gy', (py * 100).toFixed(1) + '%');
    gsap.to(glare, { opacity: 1, duration: 0.3, ease: 'apple', overwrite: 'auto' });
  };
  const leave = () => {
    rx(0);
    ry(0);
    gsap.to(glare, { opacity: 0, duration: 0.4, ease: 'apple', overwrite: 'auto' });
  };
  el.addEventListener('pointermove', move);
  el.addEventListener('pointerleave', leave);
  return () => {
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerleave', leave);
    glare.remove();
    gsap.set(el, { clearProps: 'transform,transformPerspective,transformStyle' });
  };
}
