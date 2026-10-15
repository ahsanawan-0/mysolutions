// Header behaviour (C04–C06):
//  - sticky: glass after 80px, 88 → 68px, hides on scroll down / reveals on scroll up (never while focused or a menu is open)
//  - theme: follows [data-header-theme] sections under the header
//  - sliding active pill (gsap.quickTo) on the desktop glass nav
//  - mega menu / dropdown: hover-intent + click, Esc, outside click, focus-out
//  - mobile sheet: focus trap, scroll lock, stagger, Esc
//  - mobile action bar: appears after the first viewport
import { gsap, reducedMotion } from '../core/eases.js';

const SCROLL_GLASS = 80;
const HIDE_AFTER = 200;
const HOVER_DELAY = 150;
const LEAVE_DELAY = 200;

export function initHeader() {
  const header = document.querySelector('[data-header="default"]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  const burgers = [...document.querySelectorAll('[data-burger]')];   // the hero header and the follow header each have one
  const actionBar = document.querySelector('[data-action-bar]');

  const state = { menusOpen: 0, sheetOpen: false };

  if (header) {
    initSticky(header, state);
    initThemeWatcher(header);
    initPill(header);
  }
  document.querySelectorAll('[data-menu-trigger]').forEach((trigger) => initMenu(trigger, state));
  if (mobileMenu && burgers.length) initMobileMenu(mobileMenu, burgers, state);
  if (actionBar) initActionBar(actionBar, state);
}

/* ---------- Sticky header ---------- */
function initSticky(header, state) {
  const root = document.documentElement;
  const styles = getComputedStyle(root);
  const hInitial = styles.getPropertyValue('--header-h-initial').trim();
  const hCompact = styles.getPropertyValue('--header-h-compact').trim();
  let lastY = window.scrollY;
  let hidden = false;
  let ticking = false;
  // Follow header (P16): hidden until the hero's own nav has scrolled out of the viewport
  const heroNav = header.hasAttribute('data-follow') ? document.querySelector('[data-header="hero"]') : null;
  const followFrom = () => (heroNav ? heroNav.getBoundingClientRect().bottom + window.scrollY : 0);
  if (heroNav) {
    hidden = true;
    gsap.set(header, { yPercent: -100 });
  }

  const show = () => {
    if (!hidden) return;
    hidden = false;
    root.classList.remove('is-header-hidden');   // sticky bars under the header follow it (subnav.css)
    gsap.to(header, { yPercent: 0, duration: reducedMotion() ? 0 : 0.4, ease: 'apple', overwrite: true });
  };
  const hide = () => {
    if (hidden) return;
    hidden = true;
    root.classList.add('is-header-hidden');
    gsap.to(header, { yPercent: -100, duration: reducedMotion() ? 0 : 0.4, ease: 'apple', overwrite: true });
  };

  const update = () => {
    ticking = false;
    const y = window.scrollY;
    const scrolled = y > SCROLL_GLASS;
    header.classList.toggle('is-scrolled', scrolled);
    root.style.setProperty('--header-h', scrolled ? hCompact : hInitial);

    const locked = state.menusOpen > 0 || state.sheetOpen || header.contains(document.activeElement);
    if (heroNav) {
      const following = y > followFrom();
      header.classList.toggle('is-following', following);
      if (!following) {
        hide();
        lastY = y;
        return;
      }
    }
    if (y > lastY + 4 && y > HIDE_AFTER && !locked) hide();
    else if (y < lastY - 4 || y <= HIDE_AFTER) show();
    lastY = y;
  };

  window.addEventListener('scroll', () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  }, { passive: true });
  header.addEventListener('focusin', show);
  update();
}

/* ---------- Theme watcher: which section sits under the header? ---------- */
function initThemeWatcher(header) {
  const sections = [...document.querySelectorAll('[data-header-theme]')];
  if (!sections.length) return;
  const base = header.dataset.theme;
  let ticking = false;
  const update = () => {
    ticking = false;
    const probe = header.offsetHeight / 2;
    const under = sections.find((s) => {
      const r = s.getBoundingClientRect();
      return r.top <= probe && r.bottom >= probe;
    });
    const theme = under ? under.dataset.headerTheme : base;
    if (header.dataset.theme !== theme) header.dataset.theme = theme;
  };
  window.addEventListener('scroll', () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  }, { passive: true });
  window.addEventListener('resize', update);
  update();
}

/* ---------- Sliding active pill ---------- */
function initPill(header) {
  const list = header.querySelector('[data-nav-list]');
  const pill = header.querySelector('[data-nav-pill]');
  if (!list || !pill) return;
  const links = [...list.querySelectorAll('[data-nav-link]')];
  const active = links.find((l) => l.classList.contains('is-active')) || null;
  const dur = reducedMotion() ? 0 : 0.25;
  const toX = gsap.quickTo(pill, 'x', { duration: dur, ease: 'apple' });
  const toW = gsap.quickTo(pill, 'width', { duration: dur, ease: 'apple' });
  let current = active;

  const place = (link, immediate = false) => {
    links.forEach((l) => l.classList.toggle('is-under-pill', l === link));
    if (!link) {
      gsap.to(pill, { opacity: 0, duration: dur, overwrite: 'auto' });
      return;
    }
    // Measure against the list, not the link's offsetParent (each .site-nav__item is position: relative)
    // The pill covers the link AND its caret toggle (when the item has a menu)
    const listRect = list.getBoundingClientRect();
    const rect = link.getBoundingClientRect();
    const toggle = link.closest('.site-nav__item')?.querySelector('[data-menu-trigger]');
    const right = toggle ? toggle.getBoundingClientRect().right : rect.right;
    const x = rect.left - listRect.left;
    const w = right - rect.left;
    if (immediate) {
      gsap.set(pill, { x, width: w, opacity: 1 });
    } else {
      gsap.to(pill, { opacity: 1, duration: dur, overwrite: 'auto' });
      toX(x);
      toW(w);
    }
  };

  place(current, true);
  links.forEach((link) => {
    const item = link.closest('.site-nav__item');
    const enter = () => place(link);
    item.addEventListener('mouseenter', enter);
    link.addEventListener('focus', enter);
    item.querySelector('[data-menu-trigger]')?.addEventListener('focus', enter);
  });
  list.addEventListener('mouseleave', () => place(current));
  list.addEventListener('focusout', (e) => {
    if (!list.contains(e.relatedTarget)) place(current);
  });
  window.addEventListener('resize', () => place(current, true));
  document.fonts?.ready.then(() => place(current, true));
}

/* ---------- Mega menu / dropdown ---------- */
function initMenu(trigger, state) {
  const item = trigger.closest('.has-menu');
  const panel = document.getElementById(trigger.getAttribute('aria-controls'));
  if (!item || !panel) return;
  const cols = panel.querySelectorAll('[data-menu-col]');
  let open = false;
  let openTimer = 0;
  let closeTimer = 0;

  const show = () => {
    if (open) return;
    open = true;
    state.menusOpen++;
    closeOthers(item);
    trigger.setAttribute('aria-expanded', 'true');
    item.classList.add('is-open');
    panel.hidden = false;
    if (reducedMotion()) {
      gsap.set([panel, cols], { clearProps: 'all' });
      return;
    }
    gsap.fromTo(panel, { autoAlpha: 0, y: 8 }, { autoAlpha: 1, y: 0, duration: 0.25, ease: 'apple', overwrite: true });
    gsap.fromTo(cols, { autoAlpha: 0, y: 6 }, { autoAlpha: 1, y: 0, duration: 0.3, ease: 'apple', stagger: 0.04, overwrite: true });
  };
  const hide = ({ focusTrigger = false } = {}) => {
    if (!open) return;
    open = false;
    state.menusOpen = Math.max(0, state.menusOpen - 1);
    trigger.setAttribute('aria-expanded', 'false');
    item.classList.remove('is-open');
    const done = () => { panel.hidden = true; };
    if (reducedMotion()) done();
    else gsap.to(panel, { autoAlpha: 0, y: 8, duration: 0.2, ease: 'apple', overwrite: true, onComplete: done });
    if (focusTrigger) trigger.focus();
  };
  item._closeMenu = hide;

  // Hover intent (pointer devices)
  item.addEventListener('mouseenter', () => {
    clearTimeout(closeTimer);
    openTimer = window.setTimeout(show, HOVER_DELAY);
  });
  item.addEventListener('mouseleave', () => {
    clearTimeout(openTimer);
    closeTimer = window.setTimeout(hide, LEAVE_DELAY);
  });
  // Click / Enter / Space on the trigger button
  trigger.addEventListener('click', () => {
    clearTimeout(openTimer);
    open ? hide() : show();
  });
  // Esc anywhere inside
  item.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && open) {
      e.stopPropagation();
      hide({ focusTrigger: true });
    }
  });
  // Focus leaving the item (link, trigger or panel) closes it
  item.addEventListener('focusout', (e) => {
    if (!item.contains(e.relatedTarget)) hide();
  });
  // Outside click
  document.addEventListener('pointerdown', (e) => {
    if (open && !item.contains(e.target)) hide();
  });
}

function closeOthers(except) {
  document.querySelectorAll('.has-menu.is-open').forEach((other) => {
    if (other !== except) other._closeMenu?.();
  });
}

/* ---------- Mobile sheet ---------- */
function initMobileMenu(sheet, burgers, state) {
  const closeBtn = sheet.querySelector('[data-mobile-close]');
  let opener = burgers[0];
  const expanded = (v) => burgers.forEach((b) => b.setAttribute('aria-expanded', String(v)));
  const items = sheet.querySelectorAll('[data-mobile-item]');
  const focusable = () => [...sheet.querySelectorAll('a[href], button, summary, [tabindex]:not([tabindex="-1"])')]
    .filter((el) => el.offsetParent !== null || el === closeBtn);

  const open = (e) => {
    if (state.sheetOpen) return;
    state.sheetOpen = true;
    opener = e?.currentTarget || opener;
    sheet.hidden = false;
    expanded(true);
    document.documentElement.classList.add('is-locked');
    window.lenis?.stop?.();
    if (!reducedMotion()) {
      gsap.fromTo(sheet, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.3, ease: 'apple', overwrite: true });
      gsap.fromTo(items, { autoAlpha: 0, y: 16 }, { autoAlpha: 1, y: 0, duration: 0.45, ease: 'out', stagger: 0.05, delay: 0.1, overwrite: true });
    }
    closeBtn.focus();
  };
  const close = () => {
    if (!state.sheetOpen) return;
    state.sheetOpen = false;
    expanded(false);
    document.documentElement.classList.remove('is-locked');
    window.lenis?.start?.();
    const done = () => { sheet.hidden = true; opener.focus(); };
    if (reducedMotion()) done();
    else gsap.to(sheet, { autoAlpha: 0, duration: 0.2, ease: 'apple', overwrite: true, onComplete: done });
  };

  burgers.forEach((b) => b.addEventListener('click', open));
  closeBtn.addEventListener('click', close);
  sheet.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      e.preventDefault();
      close();
      return;
    }
    if (e.key !== 'Tab') return;
    const els = focusable();
    if (!els.length) return;
    const first = els[0];
    const last = els[els.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  });
  // Close automatically when the viewport grows into the desktop nav
  matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
    if (e.matches) close();
  });
}

/* ---------- Mobile action bar ---------- */
function initActionBar(bar, state) {
  let ticking = false;
  const update = () => {
    ticking = false;
    const visible = window.scrollY > window.innerHeight * 0.9 && !state.sheetOpen;
    bar.classList.toggle('is-visible', visible);
  };
  window.addEventListener('scroll', () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  }, { passive: true });
  update();
}
