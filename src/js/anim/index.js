// The data-anim catalogue (docs/06 §3 + §6). Each entry is a lazy loader so a page only downloads the effects it uses.
// Usage in templates: data-anim="fade-up", data-anim="parallax" data-speed="0.8", data-anim="stagger fade-up" (several).
export const effects = {
  // Catalogue (docs/06 §3)
  'fade-up':       () => import('./fade-up.js'),
  'stagger':       () => import('./stagger.js'),
  'split-lines':   () => import('./split-lines.js'),
  'split-chars':   () => import('./split-chars.js'),
  'two-voice':     () => import('./two-voice.js'),
  'parallax':      () => import('./parallax.js'),
  'img-reveal':    () => import('./img-reveal.js'),
  'img-zoom-out':  () => import('./img-zoom-out.js'),
  'counter':       () => import('./counter.js'),
  'fill-text':     () => import('./fill-text.js'),
  'draw':          () => import('./draw.js'),
  'pin-steps':     () => import('./pin-steps.js'),
  'h-scroll':      () => import('./h-scroll.js'),
  'stack-cards':   () => import('./stack-cards.js'),
  'marquee':       () => import('./marquee.js'),
  'magnetic':      () => import('./magnetic.js'),
  'tilt':          () => import('./tilt.js'),
  'wave':          () => import('./wave.js'),
  'velocity-skew': () => import('./velocity-skew.js'),
  'read-along':    () => import('./read-along.js'),
  // strategicproposals patterns (docs/06 §6)
  'logo-assembly': () => import('./logo-assembly.js'),   // S1
  'micro-arrow':   () => import('./micro-arrow.js'),     // S2
  'blur-zoom':     () => import('./blur-zoom.js'),       // S3 (desktop only)
  'ring':          () => import('./ring.js'),            // S4
  'rise-rotate':   () => import('./rise-rotate.js'),     // S10
  'cta-mark':      () => import('./rise-rotate.js'),     // alias used by the CTA band (P05)
};
