// Entry point: styles, the always-on header + back-to-top, then the motion core (Lenis, ScrollTrigger,
// the data-anim catalogue and the data-module loader — src/js/core/motion.js).
import '../css/main.css';
import { initHeader } from './modules/header.js';
import { initBackToTop } from './modules/back-to-top.js';
import { initMotion } from './core/motion.js';

initHeader();
initBackToTop();
initMotion();
