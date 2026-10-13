// Hero services slider (docs/05 §4 🔒; data-module="hero-slider" on .hero__stage). GSAP Draggable + InertiaPlugin,
// no Swiper. The 12 server-rendered cards loop infinitely: each card is placed by its wrapped distance from the
// fractional "current" index, so prev / active / next read exactly like the reference (neighbours at opacity .45,
// scale .92, blur 1px, edge-faded by the viewport's mask).
//  - autoplay every 3.5s, 700ms cubic-bezier(.22,1,.36,1); pauses on hover, focus-within, hidden tab, dragging and
//    via the pause button (WCAG 2.2.2); reduced motion = no autoplay, instant moves
//  - drag / swipe with inertia snapping to the nearest card; a drag never fires the card link's click
//  - ← → when focus is inside; focusing a neighbour's link brings it to the centre
//  - prev / next / dots / pause controls; the live region announces user-initiated changes only
//  - the frame "breathes" on every card change (special-frame.css .is-breathing)
import { gsap, reducedMotion } from '../core/motion.js';
import { Draggable } from 'gsap/Draggable';
import { InertiaPlugin } from 'gsap/InertiaPlugin';
import { CustomEase } from 'gsap/CustomEase';

gsap.registerPlugin(Draggable, InertiaPlugin, CustomEase);
if (!CustomEase.get('slide')) CustomEase.create('slide', '0.22,1,0.36,1');

const AUTOPLAY = 3.5;
const DURATION = 0.7;

export default function heroSlider(stage) {
  const viewport = stage.querySelector('[data-hero-viewport]');
  const track = stage.querySelector('[data-hero-track]');
  const cards = [...stage.querySelectorAll('[data-hero-card]')];
  const n = cards.length;
  if (!viewport || !track || n < 2) return;

  if (!('registerProperty' in CSS)) document.documentElement.classList.add('no-at-property');

  const special = stage.querySelector('[data-special]');
  const controls = stage.querySelector('[data-hero-controls]');
  const live = stage.querySelector('[data-hero-live]');
  const dots = [...stage.querySelectorAll('[data-hero-dot]')];
  const pauseBtn = stage.querySelector('[data-hero-pause]');
  const mobileMQ = matchMedia('(max-width: 1023px)');
  const links = cards.map((c) => c.querySelector('a'));
  const names = cards.map((c) => c.querySelector('.hero__card-title')?.textContent.trim() ?? '');

  const state = { current: cards.findIndex((c) => c.classList.contains('is-active')) };
  if (state.current < 0) state.current = 0;
  const wrapIndex = gsap.utils.wrap(0, n);
  const wrapOffset = gsap.utils.wrap(-n / 2, n / 2);
  let step = 0, slotLeft = 0, cardW = 0;
  let tween = null;
  let timer = null;
  const hold = { hover: false, focus: false, hidden: document.hidden, drag: false, paused: false };

  stage.classList.add('is-js');   // reveals the controls (their space is reserved by CSS, so nothing shifts)

  /* ---------- Layout ---------- */
  const measure = () => {
    cardW = cards[0].offsetWidth;
    const cs = getComputedStyle(track);
    const gap = parseFloat(cs.columnGap) || parseFloat(cs.gap) || 24;
    step = cardW + gap;
    // the track already sits inside the viewport's padding, so the mobile slot is its left edge
    slotLeft = mobileMQ.matches ? 0 : (track.clientWidth - cardW) / 2;
  };

  const render = () => {
    const cur = state.current;
    let activeIndex = 0;
    cards.forEach((card, i) => {
      const d = wrapOffset(i - cur);
      const t = Math.min(Math.abs(d), 1);
      const visible = Math.abs(d) <= 2.5;
      gsap.set(card, {
        x: slotLeft + d * step,
        scale: 1 - 0.08 * t,
        opacity: visible ? 1 - 0.55 * t : 0,
        filter: t > 0.01 ? `blur(${t}px)` : 'none',
        visibility: visible ? 'inherit' : 'hidden',
      });
      const active = Math.abs(d) < 0.5;
      if (active) activeIndex = i;
      card.classList.toggle('is-active', active);
      const reachable = Math.abs(d) < 1.5;        // the three visible cards keep their links in the tab order
      card.setAttribute('aria-hidden', reachable ? 'false' : 'true');
      links[i]?.setAttribute('tabindex', reachable ? '0' : '-1');
    });
    dots.forEach((dot, i) => (i === activeIndex ? dot.setAttribute('aria-current', 'true') : dot.removeAttribute('aria-current')));
    return activeIndex;
  };

  /* ---------- Moves ---------- */
  const breathe = () => {
    if (!special || reducedMotion()) return;
    special.classList.remove('is-breathing');
    void special.offsetWidth;
    special.classList.add('is-breathing');
  };
  const settle = (announce) => {
    state.current = wrapIndex(Math.round(state.current));
    const active = render();
    breathe();
    if (announce && live) live.textContent = `Slide ${active + 1} of ${n}: ${names[active]}`;
  };

  /** Move by a delta (±1 …) or to an absolute index via the shortest wrapped path. */
  const goTo = (target, { user = false, delta = false } = {}) => {
    tween?.kill();
    const from = state.current;
    const to = delta ? from + target : from + wrapOffset(target - wrapIndex(Math.round(from)));
    if (Math.abs(to - from) < 0.001) return;
    if (reducedMotion()) {
      state.current = to;
      settle(user);
      return;
    }
    tween = gsap.to(state, {
      current: to, duration: DURATION, ease: 'slide', overwrite: true,
      onUpdate: render,
      onComplete: () => settle(user),
    });
  };

  /* ---------- Autoplay ---------- */
  const canPlay = () => !hold.hover && !hold.focus && !hold.hidden && !hold.drag && !hold.paused && !reducedMotion();
  const schedule = () => {
    timer?.kill();
    timer = gsap.delayedCall(AUTOPLAY, () => {
      if (canPlay()) goTo(1, { delta: true });
      schedule();
    });
  };
  const setPaused = (paused) => {
    hold.paused = paused;
    stage.classList.toggle('is-paused', paused);
    if (pauseBtn) {
      pauseBtn.setAttribute('aria-pressed', String(paused));
      const label = pauseBtn.querySelector('[data-hero-pause-label]');
      if (label) label.textContent = paused ? 'Play the services slider' : 'Pause the services slider';
    }
  };

  /* ---------- Drag with inertia (a proxy element carries the Draggable; cards follow `current`) ---------- */
  const proxy = document.createElement('div');
  let dragStartCurrent = 0;
  let dragMoved = false;
  const fromProxy = function () { state.current = dragStartCurrent - this.x / step; render(); };
  const endDrag = () => {
    if (!hold.drag) return;
    hold.drag = false;
    viewport.classList.remove('is-dragging');
    settle(true);
    setTimeout(() => { dragMoved = false; }, 120);   // the click that follows a drag is swallowed below
  };
  const draggable = Draggable.create(proxy, {
    type: 'x',
    trigger: viewport,
    inertia: true,
    allowNativeTouchScrolling: true,       // vertical swipes still scroll the page (touch-action: pan-y)
    minimumMovement: 4,
    snap: (value) => Math.round(value / step) * step,
    onPress() {
      tween?.kill();
      dragStartCurrent = state.current;
      dragMoved = false;
      gsap.set(proxy, { x: 0 });
      this.update();
    },
    onDragStart() {
      hold.drag = true;
      dragMoved = true;
      viewport.classList.add('is-dragging');
    },
    onDrag: fromProxy,
    onThrowUpdate: fromProxy,
    onThrowComplete: endDrag,
    onRelease() {
      requestAnimationFrame(() => { if (!this.isThrowing && !this.tween?.isActive()) endDrag(); });
    },
  })[0];
  viewport.addEventListener('click', (e) => { if (dragMoved) { e.preventDefault(); e.stopPropagation(); } }, true);
  viewport.addEventListener('dragstart', (e) => e.preventDefault());   // no native image/link ghost while dragging

  /* ---------- Controls, keys, focus ---------- */
  stage.querySelector('[data-hero-prev]')?.addEventListener('click', () => goTo(-1, { delta: true, user: true }));
  stage.querySelector('[data-hero-next]')?.addEventListener('click', () => goTo(1, { delta: true, user: true }));
  dots.forEach((dot, i) => dot.addEventListener('click', () => goTo(i, { user: true })));
  pauseBtn?.addEventListener('click', () => setPaused(!hold.paused));
  stage.addEventListener('keydown', (e) => {
    if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
    if (e.target.matches('select, input, textarea')) return;
    e.preventDefault();
    goTo(e.key === 'ArrowRight' ? 1 : -1, { delta: true, user: true });
  });
  stage.addEventListener('focusin', (e) => {
    hold.focus = true;
    const card = e.target.closest('[data-hero-card]');
    if (card && !card.classList.contains('is-active')) goTo(cards.indexOf(card), { user: true });
  });
  stage.addEventListener('focusout', (e) => { if (!stage.contains(e.relatedTarget)) hold.focus = false; });
  stage.addEventListener('pointerenter', (e) => { if (e.pointerType !== 'touch') hold.hover = true; });
  stage.addEventListener('pointerleave', () => { hold.hover = false; });
  document.addEventListener('visibilitychange', () => { hold.hidden = document.hidden; });

  /* ---------- Boot ---------- */
  measure();
  render();
  new ResizeObserver(() => { measure(); render(); }).observe(viewport);
  mobileMQ.addEventListener('change', () => { measure(); render(); });
  schedule();
  matchMedia('(prefers-reduced-motion: reduce)').addEventListener('change', schedule);
}
