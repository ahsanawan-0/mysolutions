// In-page sub-nav (C34, PROMPTS P18; data-module="subnav" on templates/components/subnav.php).
//  - scrollspy: the section crossing 40 % of the viewport becomes current (aria-current="true" on its link); above
//    the first section nothing is current
//  - a navy pill slides under the current link (0.45s apple ease; instant under reduced motion) and the scroller
//    keeps the current chip centred on small screens
//  - .is-stuck while the bar sits under the header, so it can slide up with the header when that hides (CSS)
import { gsap, ScrollTrigger, reducedMotion } from '../core/motion.js';
import { fontsReady } from '../anim/_util.js';

export default function subnav(nav) {
  const track = nav.querySelector('.subnav__track');
  const pill = nav.querySelector('.subnav__pill');
  const links = [...nav.querySelectorAll('.subnav__link')];
  const sections = links.map((a) => document.getElementById(decodeURIComponent(a.hash.slice(1))));
  let current = null;

  const movePill = (link, instant = false) => {
    // measured against the track (the list sits inside its padding), in the track's scrolled coordinates
    const x = link.getBoundingClientRect().left - track.getBoundingClientRect().left - track.clientLeft + track.scrollLeft;
    gsap.to(pill, {
      x,
      width: link.offsetWidth,
      opacity: 1,
      duration: instant || reducedMotion() ? 0 : 0.45,
      ease: 'apple',
      overwrite: true,
    });
  };

  const setActive = (index) => {
    const link = links[index] ?? null;
    if (link === current) return;
    current?.removeAttribute('aria-current');
    const first = current === null;
    current = link;
    if (!link) {
      gsap.to(pill, { opacity: 0, duration: reducedMotion() ? 0 : 0.2, overwrite: true });
      return;
    }
    link.setAttribute('aria-current', 'true');
    movePill(link, first);
    if (track.scrollWidth > track.clientWidth) {
      const left = link.getBoundingClientRect().left - track.getBoundingClientRect().left + track.scrollLeft;
      track.scrollTo({ left: left - (track.clientWidth - link.offsetWidth) / 2, behavior: reducedMotion() ? 'auto' : 'smooth' });
    }
  };

  fontsReady.then(() => {
    sections.forEach((section, i) => {
      if (!section) return;
      ScrollTrigger.create({
        trigger: section,
        start: 'top 40%',
        end: 'bottom 40%',
        onToggle: (self) => { if (self.isActive) setActive(i); },
        onLeaveBack: () => { if (i === 0) setActive(-1); },
      });
    });

    // .is-stuck: the bar's top has reached its sticky offset (under the header)
    const stuck = () => {
      const top = parseFloat(getComputedStyle(nav).top) || 0;
      nav.classList.toggle('is-stuck', nav.getBoundingClientRect().top <= top + 1);
    };
    ScrollTrigger.create({ start: 0, end: 'max', onUpdate: stuck });
    stuck();

    // Widths change with the viewport and the compact header: re-place the pill without animating
    ScrollTrigger.addEventListener('refresh', () => { if (current) movePill(current, true); });
  });
}
