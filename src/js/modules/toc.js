// C41 table of contents (data-module="toc", templates/components/toc.php, P26).
//  - Below 1100px the <details> starts collapsed (it is open in the HTML, so without JS the list is always visible)
//    and closes again once a link is followed; from 1100px it stays open beside the body (sticky, pages/article.css).
//  - Scrollspy: the last heading above 30 % of the viewport marks its link aria-current="true"; inside a scrolling
//    panel the current link is kept in view. Same-page links scroll through Lenis (core/motion.js anchors).
import { ScrollTrigger } from '../core/motion.js';
import { fontsReady } from '../anim/_util.js';

const WIDE = matchMedia('(min-width: 1100px)');

export default function toc(nav) {
  const details = nav.querySelector('.toc__details');
  const panel = nav.querySelector('.toc__panel');
  const links = [...nav.querySelectorAll('.toc__link')];
  const targets = links.map((a) => document.getElementById(decodeURIComponent(a.hash.slice(1))));
  if (!details || !links.length) return;

  details.open = WIDE.matches;
  WIDE.addEventListener('change', (e) => { details.open = e.matches; });
  // Collapse before the anchor scroll measures its target, so the jump lands on the heading
  links.forEach((a) => a.addEventListener('click', () => { if (!WIDE.matches) details.open = false; }));

  let current = null;
  const update = () => {
    const line = window.innerHeight * 0.3;
    let index = -1;
    targets.forEach((el, i) => { if (el && el.getBoundingClientRect().top <= line) index = i; });
    const link = links[index] ?? null;
    if (link === current) return;
    current?.removeAttribute('aria-current');
    current = link;
    if (!link) return;
    link.setAttribute('aria-current', 'true');
    if (panel && panel.scrollHeight > panel.clientHeight) {
      const top = link.offsetTop - panel.offsetTop;
      if (top < panel.scrollTop || top + link.offsetHeight > panel.scrollTop + panel.clientHeight) {
        panel.scrollTop = top - panel.clientHeight / 2;
      }
    }
  };

  fontsReady.then(() => {
    const body = targets.find(Boolean)?.closest('.prose') ?? document.body;
    ScrollTrigger.create({ trigger: body, start: 'top bottom', end: 'bottom top', onUpdate: update, onRefresh: update });
    update();
  });
}
