// insights-filter: the Insights category chips filter in place (data-module="insights-filter",
// templates/sections/insights/listing.php, P25). Every chip is a real link to a crawlable listing URL, and that stays
// the source of truth: a click fetches the chip's page, swaps `[data-insights-results]` plus the hero copy
// (breadcrumbs, H1, lead), the chips, <title>, meta description and canonical, pushes the URL and animates the cards
// with a GSAP Flip (cards in both states glide to their new place, leaving ones fade out first, new ones fade in).
// Back / forward replay the swap. The chip keeps focus and the new result count is announced politely.
// Any failure falls back to a normal page load; modified clicks (new tab, etc.) are left alone.
// Reduced motion: the same swap without the animation.
import { gsap, ScrollTrigger, reducedMotion } from '../core/motion.js';
import { Flip } from 'gsap/Flip';

gsap.registerPlugin(Flip);

const LISTING = /^\/insights\/(category\/[a-z0-9-]+\/)?(page\/[1-9][0-9]*\/)?$/;   // every listing state, pages included

export default function insightsFilter(root) {
  const nav = root.querySelector('.insights-filter');
  const status = root.querySelector('[data-filter-status]');
  if (!nav) return;
  let controller = null;
  let shown = location.pathname;   // the listing currently on screen

  const cards = () => [...root.querySelectorAll('[data-insights-results] [data-flip-id]')];

  async function swap(doc, next) {
    // The motion runner never sees these nodes: Flip animates them instead of the scroll reveals
    next.querySelectorAll('[data-anim]').forEach((el) => el.removeAttribute('data-anim'));
    const motion = !reducedMotion();
    const before = cards();
    const nextIds = new Set([...next.querySelectorAll('[data-flip-id]')].map((el) => el.dataset.flipId));
    const leaving = before.filter((el) => !nextIds.has(el.dataset.flipId));
    if (motion && leaving.length) await gsap.to(leaving, { opacity: 0, scale: 0.97, duration: 0.18, ease: 'power1.in' });
    const state = motion ? Flip.getState(before.filter((el) => !leaving.includes(el))) : null;

    root.querySelector('[data-insights-results]').replaceWith(document.importNode(next, true));
    updateChrome(doc);

    if (motion) {
      Flip.from(state, {
        targets: cards(),
        duration: 0.6,
        ease: 'out',
        absolute: true,
        onEnter: (els) => gsap.fromTo(els, { opacity: 0, y: 24 }, { opacity: 1, y: 0, duration: 0.5, ease: 'out', stagger: 0.05 }),
        onComplete: () => ScrollTrigger.refresh(),
      });
    } else {
      ScrollTrigger.refresh();
    }
  }

  function updateChrome(doc) {
    const focused = document.activeElement?.closest?.('[data-filter-link]')?.getAttribute('href');
    const list = doc.querySelector('.insights-filter__list');
    if (list) nav.querySelector('.insights-filter__list').innerHTML = list.innerHTML;
    if (focused) nav.querySelector(`[data-filter-link][href="${CSS.escape(focused)}"]`)?.focus({ preventScroll: true });

    const swapNode = (selector, how) => {
      const from = doc.querySelector(selector);
      const to = document.querySelector(selector);
      if (from && to) how(to, from);
    };
    swapNode('.hero-inner__crumbs', (to, from) => to.replaceWith(document.importNode(from, true)));
    swapNode('.hero-inner__title', (to, from) => { to.innerHTML = from.innerHTML; });
    swapNode('.hero-inner__lead', (to, from) => { to.textContent = from.textContent; });
    document.title = doc.title;
    swapNode('meta[name="description"]', (to, from) => to.setAttribute('content', from.getAttribute('content') || ''));
    swapNode('link[rel="canonical"]', (to, from) => to.setAttribute('href', from.getAttribute('href') || ''));

    const next = root.querySelector('[data-insights-results]');
    if (status && next?.dataset.summary) status.textContent = next.dataset.summary;
  }

  async function go(url, push) {
    controller?.abort();
    controller = new AbortController();
    nav.classList.add('is-busy');
    try {
      const res = await fetch(url, { signal: controller.signal, headers: { Accept: 'text/html' } });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const doc = new DOMParser().parseFromString(await res.text(), 'text/html');
      const next = doc.querySelector('[data-insights-results]');
      if (!next) throw new Error('no results in the response');
      if (push) history.pushState({ insights: true }, '', url);
      shown = new URL(url).pathname;
      await swap(doc, next);
    } catch (err) {
      if (err.name !== 'AbortError') location.assign(url);
    } finally {
      nav.classList.remove('is-busy');
    }
  }

  nav.addEventListener('click', (e) => {
    const link = e.target.closest('a[data-filter-link]');
    if (!link || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    const url = new URL(link.href);
    if (url.origin !== location.origin || !LISTING.test(url.pathname)) return;
    e.preventDefault();
    if (url.pathname === location.pathname) return;
    go(url.href, true);
  });

  window.addEventListener('popstate', () => {
    // only when the path changed (hash-only history entries from same-page anchors are left alone)
    if (LISTING.test(location.pathname) && location.pathname !== shown && document.contains(root)) go(location.href, false);
  });
}
