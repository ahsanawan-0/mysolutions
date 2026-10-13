// nodes: canvas network-node background (docs/06 §3, docs/12 §3.6) for <div class="nodes" data-module="nodes">.
// Navy 200 links between slowly drifting Azure nodes with gentle pointer repulsion. Pauses when off-screen
// (IntersectionObserver) or when the tab is hidden; capped at 30fps on mobile; a static SVG replaces the canvas when
// the device is low-end (hardwareConcurrency <= 4), Save-Data is on, or motion is reduced.
// Colours come from the --nodes-line / --nodes-dot custom properties set by nodes.css (tokens only).
// Options: data-count="48" (nodes), data-link="140" (link distance, px).

const reduceMQ = matchMedia('(prefers-reduced-motion: reduce)');
const lowEnd = () => (navigator.hardwareConcurrency || 8) <= 4 || navigator.connection?.saveData === true;

export default function nodes(el) {
  let live = null;
  const apply = () => {
    if (lowEnd() || reduceMQ.matches) {
      live?.destroy();
      live = null;
      mountStatic(el);
    } else {
      el.querySelector('.nodes__static')?.remove();
      live ??= mountCanvas(el);
    }
  };
  apply();
  reduceMQ.addEventListener('change', apply);
}

/* ---------- Static fallback: a deterministic SVG network ---------- */
function mountStatic(el) {
  if (el.querySelector('.nodes__static')) return;
  const rand = seeded(7);
  const w = 800, h = 450;
  const pts = Array.from({ length: 28 }, () => [Math.round(rand() * w), Math.round(rand() * h)]);
  let lines = '';
  for (let i = 0; i < pts.length; i++) {
    for (let j = i + 1; j < pts.length; j++) {
      const d = Math.hypot(pts[i][0] - pts[j][0], pts[i][1] - pts[j][1]);
      if (d < 150) lines += `<line x1="${pts[i][0]}" y1="${pts[i][1]}" x2="${pts[j][0]}" y2="${pts[j][1]}" opacity="${(1 - d / 150).toFixed(2)}"/>`;
    }
  }
  const dots = pts.map(([x, y], i) => `<circle cx="${x}" cy="${y}" r="${i % 5 === 0 ? 3 : 2}"/>`).join('');
  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  svg.setAttribute('class', 'nodes__static');
  svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
  svg.setAttribute('preserveAspectRatio', 'xMidYMid slice');
  svg.setAttribute('aria-hidden', 'true');
  svg.innerHTML = `<g class="nodes__lines">${lines}</g><g class="nodes__dots">${dots}</g>`;
  el.prepend(svg);
}

function seeded(seed) {
  let s = seed >>> 0;
  return () => {
    s = (s * 1664525 + 1013904223) >>> 0;
    return s / 4294967296;
  };
}

/* ---------- Live canvas ---------- */
function mountCanvas(el) {
  const canvas = document.createElement('canvas');
  canvas.className = 'nodes__canvas';
  canvas.setAttribute('aria-hidden', 'true');
  el.prepend(canvas);
  const ctx = canvas.getContext('2d');
  const mobile = !matchMedia('(min-width: 1024px)').matches || matchMedia('(pointer: coarse)').matches;
  const frameMs = mobile ? 1000 / 30 : 0;
  const link = parseFloat(el.dataset.link) || 140;
  const pointer = { x: -1e4, y: -1e4 };
  let w = 0, h = 0, pts = [], raf = 0, last = 0, visible = true, hidden = document.hidden;
  let colours = { line: 'currentColor', dot: 'currentColor' };

  const readColours = () => {
    const cs = getComputedStyle(el);
    colours = {
      line: cs.getPropertyValue('--nodes-line').trim() || cs.color,
      dot: cs.getPropertyValue('--nodes-dot').trim() || cs.color,
    };
  };

  const resize = () => {
    w = el.clientWidth;
    h = el.clientHeight;
    const dpr = Math.min(2, window.devicePixelRatio || 1);
    canvas.width = Math.round(w * dpr);
    canvas.height = Math.round(h * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    const count = parseInt(el.dataset.count, 10) || Math.max(16, Math.min(80, Math.round((w * h) / (mobile ? 28000 : 16000))));
    while (pts.length < count) pts.push(spawn());
    pts.length = count;
    readColours();
    drawFrame();
  };

  const spawn = () => {
    const a = Math.random() * Math.PI * 2;
    const s = 0.12 + Math.random() * 0.2;
    return { x: Math.random() * w, y: Math.random() * h, vx: Math.cos(a) * s, vy: Math.sin(a) * s, r: Math.random() < 0.2 ? 3 : 2 };
  };

  const update = (dt) => {
    const repel = 120;
    for (const p of pts) {
      const dx = p.x - pointer.x, dy = p.y - pointer.y;
      const d = Math.hypot(dx, dy);
      if (d < repel && d > 0.01) {
        const f = ((repel - d) / repel) * 0.6 * dt;
        p.x += (dx / d) * f;
        p.y += (dy / d) * f;
      }
      p.x += p.vx * dt;
      p.y += p.vy * dt;
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;
      p.x = Math.min(w, Math.max(0, p.x));
      p.y = Math.min(h, Math.max(0, p.y));
    }
  };

  const drawFrame = () => {
    ctx.clearRect(0, 0, w, h);
    ctx.lineWidth = 1;
    ctx.strokeStyle = colours.line;
    for (let i = 0; i < pts.length; i++) {
      for (let j = i + 1; j < pts.length; j++) {
        const dx = pts[i].x - pts[j].x, dy = pts[i].y - pts[j].y;
        const d = Math.hypot(dx, dy);
        if (d >= link) continue;
        ctx.globalAlpha = 1 - d / link;
        ctx.beginPath();
        ctx.moveTo(pts[i].x, pts[i].y);
        ctx.lineTo(pts[j].x, pts[j].y);
        ctx.stroke();
      }
    }
    ctx.globalAlpha = 1;
    ctx.fillStyle = colours.dot;
    for (const p of pts) {
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fill();
    }
  };

  const step = (t) => {
    raf = 0;
    if (!visible || hidden) return;
    if (t - last >= frameMs) {
      const dt = last ? Math.min(2, (t - last) / 16.7) : 1;
      last = t;
      update(dt);
      drawFrame();
    }
    raf = requestAnimationFrame(step);
  };
  const run = () => { if (!raf && visible && !hidden) raf = requestAnimationFrame(step); };
  const stop = () => { if (raf) cancelAnimationFrame(raf); raf = 0; last = 0; };

  const io = new IntersectionObserver(([entry]) => {
    visible = entry.isIntersecting;
    visible ? run() : stop();
  });
  io.observe(el);
  const onVisibility = () => {
    hidden = document.hidden;
    hidden ? stop() : run();
  };
  document.addEventListener('visibilitychange', onVisibility);
  const ro = new ResizeObserver(resize);
  ro.observe(el);
  const onMove = (e) => {
    const r = el.getBoundingClientRect();
    pointer.x = e.clientX - r.left;
    pointer.y = e.clientY - r.top;
  };
  const onLeave = () => { pointer.x = pointer.y = -1e4; };
  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerleave', onLeave);

  resize();
  run();

  return {
    destroy() {
      stop();
      io.disconnect();
      ro.disconnect();
      document.removeEventListener('visibilitychange', onVisibility);
      el.removeEventListener('pointermove', onMove);
      el.removeEventListener('pointerleave', onLeave);
      canvas.remove();
    },
  };
}
