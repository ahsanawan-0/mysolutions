// C24 cookie consent (data-module="consent", templates/partials/cookie-banner.php, P28; docs/13 §2).
//  - The choice lives in the first-party cookie `ms_consent` = {v, ts, analytics, marketing} (6 months, SameSite=Lax,
//    Secure on https). A missing, expired (older than maxAge days) or outdated (policy `v` bumped) choice shows the
//    banner again, after the splash.
//  - Mode 'consent' (GA4, or Plausible without the exemption): nothing optional runs until "Accept all" or a saved
//    preference allows it. Mode 'notice' (Plausible + ANALYTICS_EXEMPT): statistics run unless the visitor opts out.
//  - Analytics loading: GA4 = Consent Mode v2 (the default "denied" is printed in <head>), then on consent
//    gtag('consent','update', granted) and only now the gtag.js loader (Basic mode). Plausible = its script, only now.
//    window.msAnalytics is set for window.msTrack (core/track.js) and the page's [data-track-event] events replay.
//  - Withdrawal: the analytics cookies (_ga, _ga_*, _gid) are deleted on every domain level and, when a script had
//    loaded, the page reloads without it.
//  - "Cookie settings" ([data-cookie-settings], footer and cookie policy) and "Manage preferences" open the native
//    modal <dialog> (focus stays inside, Esc closes, focus returns to the opener).
import { firePageEvents } from '../core/track.js';

const DAY = 864e5;

export default function consent(root) {
  let cfg;
  try { cfg = JSON.parse(root.dataset.consent || '{}'); } catch { return; }
  const banner = root.querySelector('[data-consent-banner]');
  const dialog = root.querySelector('[data-consent-dialog]');
  const status = root.querySelector('[data-consent-status]');
  const toggles = Object.fromEntries([...root.querySelectorAll('[data-consent-toggle]')].map((el) => [el.dataset.consentToggle, el]));
  const notice = cfg.mode === 'notice';

  /* ---------- The stored choice ---------- */
  const read = () => {
    const raw = document.cookie.split('; ').find((c) => c.startsWith(`${cfg.cookie}=`));
    if (!raw) return null;
    try {
      const value = JSON.parse(decodeURIComponent(raw.slice(cfg.cookie.length + 1)));
      if (value.v !== cfg.version || !value.ts || Date.now() - value.ts > cfg.maxAge * DAY) return null;
      return value;
    } catch {
      return null;
    }
  };
  const write = (choice) => {
    const value = { v: cfg.version, ts: Date.now(), analytics: Boolean(choice.analytics), marketing: Boolean(choice.marketing) };
    document.cookie = `${cfg.cookie}=${encodeURIComponent(JSON.stringify(value))}; Max-Age=${cfg.maxAge * 86400}; Path=/; SameSite=Lax${location.protocol === 'https:' ? '; Secure' : ''}`;
    return value;
  };
  // What may run: the stored choice, or with no choice yet only the exempt statistics of notice mode
  const allowed = (stored) => ({ analytics: stored ? stored.analytics : notice, marketing: stored ? stored.marketing : false });

  /* ---------- Analytics ---------- */
  let loaded = false;
  const addScript = (src, attrs = {}) => {
    const s = document.createElement('script');
    s.src = src;
    Object.assign(s, attrs.props || {});
    Object.entries(attrs.data || {}).forEach(([k, v]) => { s.dataset[k] = v; });
    document.head.append(s);
  };
  const startAnalytics = () => {
    if (loaded || cfg.provider === 'none' || !cfg.id) return;
    loaded = true;
    if (cfg.provider === 'ga4') {
      window.dataLayer = window.dataLayer || [];
      window.gtag = window.gtag || function gtag() { window.dataLayer.push(arguments); }; // eslint-disable-line prefer-rest-params
      window.gtag('consent', 'update', { analytics_storage: 'granted' });
      window.gtag('js', new Date());
      window.gtag('config', cfg.id);
      addScript(`https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(cfg.id)}`, { props: { async: true } });
      window.msAnalytics = { send: (name, props) => window.gtag('event', name, props) };
    } else if (cfg.provider === 'plausible') {
      window.plausible = window.plausible || function plausible() { (window.plausible.q = window.plausible.q || []).push(arguments); }; // eslint-disable-line prefer-rest-params
      addScript(cfg.src, { props: { defer: true }, data: { domain: cfg.id } });
      window.msAnalytics = { send: (name, props) => window.plausible(name, { props }) };
    }
    firePageEvents();
  };
  const deleteCookies = (pattern) => {
    const host = location.hostname;
    const parts = host.split('.');
    const domains = [''];
    for (let i = 0; i < parts.length - 1; i++) domains.push(`; Domain=.${parts.slice(i).join('.')}`);
    document.cookie.split('; ').map((c) => c.split('=')[0]).filter((name) => pattern.test(name)).forEach((name) => {
      domains.forEach((domain) => { document.cookie = `${name}=; Max-Age=0; Path=/${domain}`; });
    });
  };
  const stopAnalytics = () => {
    if (cfg.provider === 'ga4' && typeof window.gtag === 'function') window.gtag('consent', 'update', { analytics_storage: 'denied' });
    deleteCookies(/^(_ga|_ga_.+|_gid|_gat.*)$/);
    delete window.msAnalytics;
  };

  /* ---------- Banner + dialog ---------- */
  const showBanner = () => {
    banner.hidden = false;
    requestAnimationFrame(() => banner.classList.add('is-in'));
    // Focus moves to the banner on arrival (docs/13 C24) unless the visitor is already using the page; no trap
    if (!document.activeElement || document.activeElement === document.body) banner.focus({ preventScroll: true });
  };
  const hideBanner = () => {
    if (banner.hidden) return;
    const hadFocus = banner.contains(document.activeElement);
    banner.classList.remove('is-in');
    banner.hidden = true;
    if (hadFocus) document.getElementById('main')?.focus({ preventScroll: true });
  };
  const sync = () => {
    const now = allowed(read());
    Object.entries(toggles).forEach(([key, input]) => { input.checked = Boolean(now[key]); });
  };
  const openDialog = () => {
    if (!dialog) return;
    sync();
    if (!dialog.open) dialog.showModal();
  };
  const closeDialog = () => { if (dialog?.open) dialog.close(); };

  const apply = (choice) => {
    const wasLoaded = loaded;
    write(choice);
    hideBanner();
    closeDialog();
    if (status) status.textContent = status.dataset.saved || '';
    if (choice.analytics) {
      startAnalytics();
    } else {
      stopAnalytics();
      if (wasLoaded) setTimeout(() => location.reload(), 300);   // unload the script that already ran
    }
  };

  root.addEventListener('click', (e) => {
    const target = e.target instanceof Element ? e.target.closest('button') : null;
    if (!target) return;
    if (target.matches('[data-consent-accept]')) apply({ analytics: true, marketing: Boolean(toggles.marketing) });
    else if (target.matches('[data-consent-reject]')) apply({ analytics: false, marketing: false });
    else if (target.matches('[data-consent-save]')) apply({ analytics: Boolean(toggles.analytics?.checked), marketing: Boolean(toggles.marketing?.checked) });
    else if (target.matches('[data-consent-manage]')) openDialog();
    else if (target.matches('[data-consent-close]')) closeDialog();
  });
  // Footer "Cookie settings" + the cookie policy's button: open the dialog instead of following the link
  document.addEventListener('click', (e) => {
    const opener = e.target instanceof Element ? e.target.closest('[data-cookie-settings]') : null;
    if (!opener || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    e.preventDefault();
    openDialog();
  });
  // A click on the backdrop (outside the dialog box) closes it
  dialog?.addEventListener('click', (e) => { if (e.target === dialog) closeDialog(); });

  /* ---------- Start ---------- */
  const stored = read();
  if (allowed(stored).analytics) startAnalytics();
  else stopAnalytics();   // clears any analytics cookies left from an earlier choice
  if (!stored && banner) {
    const html = document.documentElement;
    if (html.classList.contains('splash-done')) showBanner();
    else {
      let shown = false;
      const once = () => { if (!shown) { shown = true; showBanner(); } };
      window.addEventListener('splash:done', once, { once: true });
      setTimeout(once, 4000);
    }
  }
}
