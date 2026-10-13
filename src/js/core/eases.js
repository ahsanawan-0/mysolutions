// Brand easings shared by every module (docs/03 §2.6). Registered once; P06 adds the full motion core.
import gsap from 'gsap';
import { CustomEase } from 'gsap/CustomEase';

gsap.registerPlugin(CustomEase);

if (!CustomEase.get('apple')) {
  CustomEase.create('apple', '0.28,0.11,0.32,1');
  CustomEase.create('out', '0.16,1,0.3,1');
  CustomEase.create('inout', '0.65,0,0.35,1');
  CustomEase.create('spring', '0.34,1.56,0.64,1');
}

export const reducedMotion = () => matchMedia('(prefers-reduced-motion: reduce)').matches;
export { gsap };
