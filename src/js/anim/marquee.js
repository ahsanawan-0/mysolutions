// marquee: seamless horizontal loop of the [data-marquee-track] children (cloned until the track is wider than the
// viewport + one set). Direction and speed follow the scroll velocity, hover slows it, focus inside pauses it, and a
// pause/play button ([data-marquee-pause], inside or linked with data-marquee-for="<id>") satisfies WCAG 2.2.2.
// Without JS or under reduced motion the track is a static wrapped list (motion.css / marquee.css).
// Options: data-speed="60" (px/s), data-direction="-1" (right to left is the default).
import { gsap, ScrollTrigger } from '../core/motion.js';
import { num, clamp } from './_util.js';

export default function marquee(el) {
  const track = el.querySelector('[data-marquee-track]');
  if (!track || !track.children.length) return;
  const button = el.querySelector('[data-marquee-pause]')
    || (el.id ? document.querySelector(`[data-marquee-pause][data-marquee-for="${el.id}"]`) : null);
  const speed = num(el, 'speed', 60);
  const base = num(el, 'direction', 1);
  const originals = [...track.children];
  const clones = [];
  let tween = null;
  let paused = false;
  let settle = null;

  const build = () => {
    tween?.kill();
    clones.forEach((c) => c.remove());
    clones.length = 0;
    const setWidth = () => track.scrollWidth;
    const needed = el.clientWidth + setWidth();
    let guard = 0;
    while (track.scrollWidth < needed && guard++ < 12) {
      originals.forEach((item) => {
        const clone = item.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        clone.querySelectorAll('a, button, [tabindex]').forEach((f) => f.setAttribute('tabindex', '-1'));
        track.append(clone);
        clones.push(clone);
      });
    }
    const loop = clones.length ? clones[0].offsetLeft - originals[0].offsetLeft : track.scrollWidth;
    if (loop <= 0) return;
    tween = gsap.to(track, {
      x: -loop, duration: loop / speed, ease: 'none', repeat: -1,
      modifiers: { x: gsap.utils.unitize(gsap.utils.wrap(-loop, 0)) },
    });
    tween.timeScale(paused ? 0 : base);
  };

  const setScale = (value, duration = 0.6) => {
    if (!tween || paused) return;
    gsap.to(tween, { timeScale: value, duration, ease: 'out', overwrite: true });
  };

  // Scroll velocity → direction and speed, then settle back to the base speed
  const st = ScrollTrigger.create({
    onUpdate: (self) => {
      const v = self.getVelocity();
      if (Math.abs(v) < 50) return;
      const factor = clamp(1, 6, 1 + Math.abs(v) / 500);
      setScale(base * factor * (v < 0 ? -1 : 1), 0.3);
      settle?.kill();
      settle = gsap.delayedCall(0.4, () => setScale(hovering ? base * 0.25 : base));
    },
  });

  let hovering = false;
  const onEnter = () => { hovering = true; setScale(base * 0.25); };
  const onLeave = () => { hovering = false; setScale(base); };
  const onFocusIn = () => tween?.pause();
  const onFocusOut = (e) => { if (!el.contains(e.relatedTarget) && !paused) tween?.play(); };
  el.addEventListener('mouseenter', onEnter);
  el.addEventListener('mouseleave', onLeave);
  el.addEventListener('focusin', onFocusIn);
  el.addEventListener('focusout', onFocusOut);

  const label = (b, isPaused) => {
    b.setAttribute('aria-pressed', String(isPaused));
    const text = b.querySelector('[data-marquee-label]');
    if (text) text.textContent = isPaused ? (b.dataset.labelPlay || 'Play the moving text') : (b.dataset.labelPause || 'Pause the moving text');
  };
  const toggle = () => {
    paused = !paused;
    el.classList.toggle('is-paused', paused);
    if (button) label(button, paused);
    paused ? tween?.pause() : tween?.play();
  };
  if (button) {
    button.hidden = false;
    label(button, false);
    button.addEventListener('click', toggle);
  }

  build();
  const ro = new ResizeObserver(() => {
    clearTimeout(ro._t);
    ro._t = setTimeout(build, 150);
  });
  ro.observe(el);

  return () => {
    ro.disconnect();
    st.kill();
    settle?.kill();
    tween?.kill();
    clones.forEach((c) => c.remove());
    gsap.set(track, { clearProps: 'transform' });
    el.classList.remove('is-paused');
    el.removeEventListener('mouseenter', onEnter);
    el.removeEventListener('mouseleave', onLeave);
    el.removeEventListener('focusin', onFocusIn);
    el.removeEventListener('focusout', onFocusOut);
    if (button) {
      button.hidden = true;
      button.removeEventListener('click', toggle);
    }
  };
}
