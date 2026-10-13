// magnetic: the element follows the pointer within ±12px (data-magnetic="12") and springs back on leave.
// Pointer devices on desktop only. Put it on a wrapper (e.g. <span data-anim="magnetic">) around buttons that
// have their own hover transition (motion.css removes transitions on the magnetic element itself).
import { gsap } from '../core/motion.js';
import { num, clamp } from './_util.js';

export default function magnetic(el, { desktop, fine }) {
  if (!desktop || !fine) return;
  const strength = num(el, 'magnetic', 12);
  const toX = gsap.quickTo(el, 'x', { duration: 0.4, ease: 'out' });
  const toY = gsap.quickTo(el, 'y', { duration: 0.4, ease: 'out' });
  const move = (e) => {
    const r = el.getBoundingClientRect();
    const dx = (e.clientX - (r.left + r.width / 2)) / (r.width / 2);
    const dy = (e.clientY - (r.top + r.height / 2)) / (r.height / 2);
    toX(clamp(-strength, strength, dx * strength));
    toY(clamp(-strength, strength, dy * strength));
  };
  const leave = () => gsap.to(el, { x: 0, y: 0, duration: 0.6, ease: 'spring', overwrite: 'auto' });
  el.addEventListener('pointermove', move);
  el.addEventListener('pointerleave', leave);
  return () => {
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerleave', leave);
    gsap.set(el, { clearProps: 'transform' });
  };
}
