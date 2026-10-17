// Entry point: styles, the always-on header + back-to-top, the motion core (Lenis, ScrollTrigger,
// the data-anim catalogue and the data-module loader — src/js/core/motion.js), then the first-visit splash
// (after the motion core so it can pause Lenis; it dispatches `splash:done` when the page is revealed).
// The analytics hooks come first so window.msTrack exists for every module (a no-op until consent, P24/P28).
import '../css/main.css';
import { initTracking } from './core/track.js';
import { initHeader } from './modules/header.js';
import { initBackToTop } from './modules/back-to-top.js';
import { initMotion } from './core/motion.js';
import { initSplash } from './modules/splash.js';

initTracking();
initHeader();
initBackToTop();
initMotion();
initSplash();
