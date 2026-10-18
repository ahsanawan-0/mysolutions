// Analytics event hooks (P24, docs/13 §2). window.msTrack(name, props) is the one call every module uses. It is a
// no-op until analytics is enabled: P28's consent module sets window.msAnalytics = { send(name, props) } only after
// the visitor opts in, so nothing is stored or sent before consent. Props never carry personal data.
// Wired here:
//  - `data-track-event` (+ JSON `data-track-props`) fires once on load: the thank-you page's `form_submit`, printed
//    only after a real submission (one-time session flag). The attribute stays, so P28 can replay it when the visitor
//    consents on that same page;
//  - clicks on tel:, mailto: and WhatsApp links → click_phone / click_email / click_whatsapp ({ page }).
const LINKS = [
  ['a[href^="tel:"]', 'click_phone'],
  ['a[href^="mailto:"]', 'click_email'],
  ['a[href*="wa.me/"], a[href*="api.whatsapp.com/"]', 'click_whatsapp'],
];

const send = (name, props = {}) => {
  const analytics = window.msAnalytics;
  if (analytics && typeof analytics.send === 'function') analytics.send(name, props);
};

/** Fire the page's [data-track-event] events. On load (a no-op before consent) and again by the consent module
 *  (P28) the moment analytics starts, so an event on the page where the visitor consents is not lost. */
export function firePageEvents() {
  document.querySelectorAll('[data-track-event]').forEach((el) => {
    let props = {};
    try { props = JSON.parse(el.dataset.trackProps || '{}'); } catch { /* malformed props: send the event bare */ }
    (window.msTrack ?? send)(el.dataset.trackEvent, props);
  });
}

export function initTracking() {
  window.msTrack ??= send;
  firePageEvents();

  document.addEventListener('click', (e) => {
    const link = e.target instanceof Element ? e.target.closest('a[href]') : null;
    if (!link) return;
    const hit = LINKS.find(([selector]) => link.matches(selector));
    if (hit) window.msTrack(hit[1], { page: location.pathname });
  });
}
