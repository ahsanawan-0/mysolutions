// Team cards slider (C11, PROMPTS P19; data-module="team-cards"): the track is a CSS scroll-snap row, so this only adds
// the prev/next buttons (scroll by one card), the dots (one per "page" of visible cards; the current one follows the
// scroll position) and keyboard arrows on the track. Smooth scrolling is instant under reduced motion.
import { reducedMotion } from '../core/motion.js';

export default function teamCards(root) {
  const track = root.querySelector('[data-team-track]');
  const cards = [...root.querySelectorAll('[data-team-card]')];
  const dots = root.querySelector('[data-team-dots]');
  const prev = root.querySelector('[data-team-prev]');
  const next = root.querySelector('[data-team-next]');
  if (!track || cards.length < 2) { root.querySelector('.team-cards__controls')?.remove(); return; }

  const behavior = () => (reducedMotion() ? 'auto' : 'smooth');
  const step = () => cards[1].offsetLeft - cards[0].offsetLeft;
  const perPage = () => Math.max(1, Math.round(track.clientWidth / step()));
  const pages = () => Math.ceil(cards.length / perPage());
  const page = () => Math.round(track.scrollLeft / (step() * perPage()));

  const buildDots = () => {
    dots.replaceChildren();
    for (let i = 0; i < pages(); i++) {
      const li = document.createElement('li');
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'team-cards__dot';
      b.setAttribute('aria-label', `Go to team members page ${i + 1}`);
      b.addEventListener('click', () => track.scrollTo({ left: i * step() * perPage(), behavior: behavior() }));
      li.append(b);
      dots.append(li);
    }
    sync();
  };
  const sync = () => {
    const p = page();
    [...dots.children].forEach((li, i) => li.firstElementChild.toggleAttribute('aria-current', i === p));
    const max = track.scrollWidth - track.clientWidth - 2;
    prev.disabled = track.scrollLeft <= 2;
    next.disabled = track.scrollLeft >= max;
  };

  prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: behavior() }));
  next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: behavior() }));
  track.addEventListener('scroll', sync, { passive: true });
  track.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') { e.preventDefault(); next.click(); }
    if (e.key === 'ArrowLeft') { e.preventDefault(); prev.click(); }
  });
  new ResizeObserver(buildDots).observe(track);
  buildDots();

  // Share popovers: native top-layer popovers, placed under their button (right-aligned, kept inside the viewport)
  // when they open and re-placed on every scroll (Lenis keeps emitting scroll events while it settles, so closing on
  // scroll would shut them at once); a popover closes only when its button has left the viewport.
  const place = (pop, button) => {
    const r = button.getBoundingClientRect();
    if (r.bottom < 0 || r.top > window.innerHeight) { pop.hidePopover(); return; }
    const w = pop.offsetWidth;
    const h = pop.offsetHeight;
    const left = Math.max(8, Math.min(r.right - w, window.innerWidth - w - 8));
    const top = r.bottom + 8 + h > window.innerHeight ? r.top - 8 - h : r.bottom + 8;
    pop.style.left = `${Math.round(left)}px`;
    pop.style.top = `${Math.round(top)}px`;
  };
  const pops = [...root.querySelectorAll('.team-card__pop')].map((pop) => [pop, root.querySelector(`[popovertarget="${pop.id}"]`)]).filter(([, b]) => b);
  pops.forEach(([pop, button]) => pop.addEventListener('toggle', (e) => { if (e.newState === 'open') place(pop, button); }));
  const follow = () => pops.forEach(([pop, button]) => { if (pop.matches(':popover-open')) place(pop, button); });
  window.addEventListener('scroll', follow, { passive: true });
  track.addEventListener('scroll', follow, { passive: true });
}
