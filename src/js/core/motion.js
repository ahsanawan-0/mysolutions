// Motion core (docs/06 §1, §5, §5b; docs/12 §3): GSAP + ScrollTrigger + Lenis, the `data-anim` runner and the
// `data-module` loader. Templates only add attributes; everything here is progressive enhancement:
//  - Lenis runs only when motion is not reduced; it is created after the browser's native #hash jump on load.
//  - Same-page anchor links scroll through Lenis with the header offset and push the hash (back/forward keep working).
//  - Effects (src/js/anim/*.js) are code-split, loaded only when the page uses them, and created inside one
//    gsap.matchMedia() context (desktop / mobile / reduce) so breakpoint or preference changes rebuild them cleanly.
//  - Under prefers-reduced-motion every effect is skipped and the content stays in its final, server-rendered state.
import { gsap, reducedMotion } from './eases.js';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';
import { effects } from '../anim/index.js';

gsap.registerPlugin(ScrollTrigger);
ScrollTrigger.config({ ignoreMobileResize: true });

export { gsap, ScrollTrigger, reducedMotion };
export const reduceMQ = matchMedia('(prefers-reduced-motion: reduce)');
export const BREAKPOINTS = {
  desktop: '(min-width: 1024px)',
  mobile: '(max-width: 1023px)',
  reduce: '(prefers-reduced-motion: reduce)',
};
export const mm = gsap.matchMedia();
export let lenis = null;

/* ---------- Boot ---------- */
export function initMotion() {
  initLenis();
  initAnchors();
  initAnimations();
  initModules();
  document.fonts?.ready.then(() => ScrollTrigger.refresh());
  if (document.readyState !== 'complete') window.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });
}

/* ---------- Lenis ---------- */
const tick = (time) => lenis?.raf(time * 1000);

function startLenis() {
  if (lenis || reduceMQ.matches) return;
  lenis = new Lenis({ lerp: 0.1, smoothWheel: true });   // touch devices keep native scrolling
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add(tick);
  gsap.ticker.lagSmoothing(0);
  window.lenis = window.__lenis = lenis;
}

function stopLenis() {
  if (!lenis) return;
  gsap.ticker.remove(tick);
  lenis.destroy();
  lenis = null;
  window.lenis = window.__lenis = null;
}

function initLenis() {
  // Hash rule (docs/10 §9.6): with a #hash on load the browser jumps natively first, then Lenis takes over.
  // history.scrollRestoration stays 'auto'; the only position forced on load is the #hash target (alignHash).
  if (location.hash) {
    const go = () => requestAnimationFrame(() => {
      startLenis();
      alignHash();
    });
    document.readyState === 'complete' ? go() : window.addEventListener('load', go, { once: true });
  } else {
    startLenis();
  }
  reduceMQ.addEventListener('change', (e) => (e.matches ? stopLenis() : startLenis()));
}

// The native jump lands on the target's position at parse time, but the layout keeps moving afterwards (fonts, the
// dev-mode stylesheet, pin spacers, modules that load later), and ScrollTrigger's refresh can reset it. So once the
// page has loaded, re-align on the #hash target, and again after every ScrollTrigger refresh during the next 3s,
// unless the reader has started scrolling.
function alignHash() {
  const id = decodeURIComponent(location.hash.slice(1));
  const target = id && document.getElementById(id);
  if (!target) return;
  let reader = false;
  const stop = () => { reader = true; };
  ['wheel', 'touchstart', 'keydown', 'pointerdown'].forEach((type) => window.addEventListener(type, stop, { once: true, passive: true }));
  const align = () => { if (!reader) scrollTo(target, { immediate: true }); };
  ScrollTrigger.addEventListener('refresh', align);
  setTimeout(() => ScrollTrigger.removeEventListener('refresh', align), 3000);
  align();
}

/* ---------- Anchor links (same page) ---------- */
// The header offset itself comes from `scroll-padding-top: var(--header-h)` (base.css), which Lenis honours for
// element targets; this is the extra breathing space below the header.
const ANCHOR_MARGIN = 16;
const scrollPadding = () => parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop) || 0;

function focusTarget(target) {
  if (target === document.body) return;
  // focus already inside the target (e.g. a form's error summary after #quote) stays where it is
  if (document.activeElement !== document.body && target.contains(document.activeElement)) return;
  if (!target.hasAttribute('tabindex')) {
    target.setAttribute('tabindex', '-1');
    target.addEventListener('blur', () => target.removeAttribute('tabindex'), { once: true });
  }
  target.focus({ preventScroll: true });
}

/** Scroll to an element (or a pixel value) through Lenis when it runs, natively otherwise. */
export function scrollTo(target, options = {}) {
  const el = typeof target === 'string' ? document.querySelector(target) : target;
  if (el == null) return;
  const immediate = reduceMQ.matches;
  if (lenis) {
    lenis.scrollTo(el, { offset: -ANCHOR_MARGIN, immediate, ...options, onComplete: () => el instanceof Element && focusTarget(el) });
    return;
  }
  if (el instanceof Element) {
    const top = el.getBoundingClientRect().top + window.scrollY - scrollPadding() - ANCHOR_MARGIN;
    window.scrollTo({ top, behavior: immediate ? 'auto' : 'smooth' });
    focusTarget(el);
  } else {
    window.scrollTo({ top: el, behavior: immediate ? 'auto' : 'smooth' });
  }
}

function initAnchors() {
  document.addEventListener('click', (e) => {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    const a = e.target.closest('a[href]');
    if (!a || a.target === '_blank' || a.hasAttribute('download')) return;
    const url = new URL(a.href, location.href);
    if (url.origin !== location.origin || url.pathname !== location.pathname || url.search !== location.search || !url.hash) return;
    const id = decodeURIComponent(url.hash.slice(1));
    const target = id === 'top' ? document.body : document.getElementById(id) || document.getElementsByName(id)[0];
    if (!target) return;
    e.preventDefault();
    if (url.hash !== location.hash) history.pushState(null, '', url.hash);
    scrollTo(target);
  });
}

/* ---------- data-anim runner ---------- */
const names = (el) => (el.dataset.anim || '').split(/\s+/).filter(Boolean);

export async function initAnimations(root = document) {
  const els = [...root.querySelectorAll('[data-anim]')];
  if (!els.length) return;

  // Load only the effects this page uses (one small chunk each)
  const wanted = new Set(els.flatMap(names));
  const loaded = {};
  await Promise.all([...wanted].map(async (name) => {
    const loader = effects[name];
    if (!loader) {
      console.warn(`[anim] unknown effect "${name}"`);
      return;
    }
    loaded[name] = (await loader()).default;
  }));

  mm.add(BREAKPOINTS, (context) => {
    const { desktop, mobile, reduce } = context.conditions;
    els.forEach((el) => el.classList.add('is-anim'));      // cancels the CSS safety timers (motion.css)
    if (reduce) return;                                     // static: CSS shows everything in its final state

    const fine = matchMedia('(hover: hover) and (pointer: fine)').matches;
    const cleanups = [];
    els.forEach((el) => {
      names(el).forEach((name) => {
        const effect = loaded[name];
        if (!effect) return;
        try {
          const cleanup = effect(el, { desktop, mobile, fine, context });
          if (typeof cleanup === 'function') cleanups.push(cleanup);
        } catch (err) {
          console.error(`[anim] ${name} failed`, el, err);
        }
      });
    });
    ScrollTrigger.refresh();
    return () => cleanups.forEach((fn) => fn());
  });
}

/* ---------- data-module loader (code-split per module) ---------- */
// header.js and back-to-top.js are always on (imported statically by main.js), so they are not lazy modules
const moduleLoaders = import.meta.glob(['../modules/*.js', '!../modules/header.js', '!../modules/back-to-top.js', '!../modules/splash.js']);

export function initModules(root = document) {
  root.querySelectorAll('[data-module]').forEach((el) => {
    (el.dataset.module || '').split(/\s+/).filter(Boolean).forEach((name) => {
      const load = moduleLoaders[`../modules/${name}.js`];
      if (!load) {
        console.warn(`[module] unknown module "${name}"`);
        return;
      }
      load().then((m) => (m.default ?? m.init)?.(el)).catch((err) => console.error(`[module] ${name} failed`, err));
    });
  });
}
