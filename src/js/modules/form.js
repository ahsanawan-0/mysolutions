// form: progressive enhancement for the enquiry form (data-module="form", templates/sections/contact/form.php, P23–P24).
// The form works fully without it (server validation, error summary, PRG). This module adds:
//  · the character counter under textareas with `data-char-counter` (copy in the form's `data-counter`,
//    "{n} of {max} characters"; announced politely, and only near the limit so screen readers are not flooded);
//  · inline validation on blur that mirrors ContactController::validate() (messages from the form's `data-messages`,
//    markup from <template data-error-template>, so it matches the server's): a field is checked once it has been
//    edited or already shows an error, and its error clears as soon as the value is fixed;
//  · on submit, the same checks for every field: errors → an error summary like the server's (focused, links to the
//    fields) and no request; otherwise the submit button's loading state (the logo mark's breathing loop, .is-loading
//    from P07, "Sending…", announced politely) and no second submit until the page comes back (bfcache);
//  · focus on the error summary / notice after a failed submit (the page is already aligned on #quote) or, arriving
//    on #quote, on the first empty required field (after the splash, without scrolling: the P06 hash rule);
//  · summary links that focus their field, including a C31 custom select (its combobox, not the hidden <select>);
//  · the pre-fill chip's × (a link to the bare form without JS): clears the pre-selected sector and service in place.
const ANNOUNCE_FROM = 0.9;   // share of maxlength from which the count is announced
const SCROLL_OFFSET = -120;  // px above a field or the summary when scrolling to it (clears the sticky header)

// ContactController::validate(), field by field (null = valid). v = the value cleaned like App\Validator does.
const EMAIL = /^[^\s@"(),:;<>[\]\\]+@[a-z0-9](?:[a-z0-9-]*[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)+$/i;
const PHONE = /^[0-9 +()-]+$/;
const DATE = /^\d{4}-\d{2}-\d{2}$/;
const len = (v) => [...v].length;   // code points, like mb_strlen
const RULES = {
  name: (v, m) => (!v ? m.name_required : len(v) > 100 ? m.name_length : null),
  company: (v, m) => (len(v) > 150 ? m.company_length : null),
  email: (v, m) => (!v ? m.email_required : len(v) > 254 || !EMAIL.test(v) ? m.email_invalid : null),
  phone: (v, m) => {
    if (!v) return null;
    const digits = v.replace(/\D/g, '').length;
    return PHONE.test(v) && digits >= 7 && digits <= 20 ? null : m.phone_invalid;
  },
  deadline: (v, m, field) => {
    if (field.validity?.badInput || (v && (!DATE.test(v) || Number.isNaN(Date.parse(v))))) return m.deadline_invalid;
    return v && v < today() ? m.deadline_past : null;
  },
  message: (v, m) => (!v ? m.message_required : len(v) < 10 ? m.message_short : len(v) > 2000 ? m.message_long : null),
};

function today() {
  const d = new Date();
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}

const reduced = () => matchMedia('(prefers-reduced-motion: reduce)').matches;

function scrollToBox(box) {
  if (window.lenis) window.lenis.scrollTo(box, { offset: SCROLL_OFFSET, immediate: reduced() });
  else box.scrollIntoView({ block: 'center' });
}

function whenRevealed(fn) {
  if (document.documentElement.classList.contains('splash-done')) requestAnimationFrame(fn);
  else window.addEventListener('splash:done', () => requestAnimationFrame(fn), { once: true });
}

export default function form(root) {
  let messages = {};
  try { messages = JSON.parse(root.dataset.messages || '{}'); } catch { /* no messages: inline checks stay off */ }
  const panel = root.parentElement;
  const status = panel.querySelector('[data-form-status]');
  const errorTpl = root.querySelector('template[data-error-template]');
  const summaryTpl = root.querySelector('template[data-summary-template]');
  const fields = Object.keys(RULES).map((key) => root.elements.namedItem(key)).filter((f) => f instanceof HTMLElement);
  const canValidate = Object.keys(messages).length > 0 && !!errorTpl;

  /* ---------- Character counter ---------- */
  const template = root.dataset.counter || '{n} of {max} characters';
  root.querySelectorAll('textarea[data-char-counter][maxlength]').forEach((field) => {
    const out = field.parentElement.querySelector('[data-char-count]');
    if (!out) return;
    const max = Number(field.getAttribute('maxlength'));
    const update = () => {
      const n = field.value.length;
      out.textContent = template.replace('{n}', n.toLocaleString('en-GB')).replace('{max}', max.toLocaleString('en-GB'));
      out.setAttribute('aria-live', n >= max * ANNOUNCE_FROM ? 'polite' : 'off');
      out.classList.toggle('is-near', n >= max * ANNOUNCE_FROM);
    };
    out.hidden = false;
    update();
    field.addEventListener('input', update);
  });

  /* ---------- Inline validation ---------- */
  const clean = (field) => (field.tagName === 'TEXTAREA' ? field.value.trim() : field.value.replace(/\s+/g, ' ').trim());
  const check = (field) => RULES[field.name](clean(field), messages, field);
  const hasError = (field) => field.getAttribute('aria-invalid') === 'true';

  function setError(field, message) {
    const box = field.closest('.field');
    const id = `${field.id}-error`;
    const current = document.getElementById(id);
    const text = current?.querySelector('[data-error-text]')?.textContent ?? current?.textContent.replace(/^\s*Error:\s*/, '').trim();
    if (message && current && text === message) return;
    current?.remove();
    if (message) {
      const error = errorTpl.content.firstElementChild.cloneNode(true);
      error.id = id;
      error.querySelector('[data-error-text]').textContent = message;
      const counter = box.querySelector('.field__counter');
      if (counter) counter.before(error);
      else box.append(error);
    }
    const hint = document.getElementById(`${field.id}-hint`);
    const ids = [hint && hint.id, message && id].filter(Boolean);
    if (ids.length) field.setAttribute('aria-describedby', ids.join(' '));
    else field.removeAttribute('aria-describedby');
    if (message) field.setAttribute('aria-invalid', 'true');
    else field.removeAttribute('aria-invalid');
    box.classList.toggle('field--error', !!message);
  }

  if (canValidate) {
    fields.forEach((field) => {
      field.addEventListener('input', () => {
        field.dataset.edited = '';
        if (hasError(field) && !check(field)) setError(field, null);
      });
      field.addEventListener('change', () => { field.dataset.edited = ''; });   // date pickers
      field.addEventListener('blur', () => {
        if ('edited' in field.dataset || hasError(field)) setError(field, check(field));
      });
    });
  }

  /* ---------- Error summary ---------- */
  function wireSummary(summary) {
    summary.querySelectorAll('a[href^="#"]').forEach((link) => {
      link.addEventListener('click', (e) => {
        const field = document.getElementById(link.hash.slice(1));
        if (!field) return;
        e.preventDefault();
        const target = document.getElementById(`${field.id}-combo`) || field;
        scrollToBox(target.closest('.field') || target);
        target.focus({ preventScroll: true });
      });
    });
  }

  function showSummary(errors) {
    root.querySelector('[data-error-summary]')?.remove();
    const summary = summaryTpl.content.firstElementChild.cloneNode(true);
    const list = summary.querySelector('[data-summary-list]');
    errors.forEach(([field, message]) => {
      const item = document.createElement('li');
      const link = document.createElement('a');
      link.href = `#${field.id}`;
      link.textContent = message;
      item.append(link);
      list.append(item);
    });
    root.prepend(summary);
    wireSummary(summary);
    scrollToBox(summary);
    summary.focus({ preventScroll: true });
  }

  const serverSummary = root.querySelector('[data-error-summary]');
  if (serverSummary) {
    serverSummary.focus({ preventScroll: !!location.hash });
    wireSummary(serverSummary);
  } else if (location.hash === '#quote') {
    // Arriving from a "Get a Free Quote" link: start typing straight away (focus without scrolling; the page is
    // aligned on #quote by the motion core, which leaves focus alone when it is already inside the target)
    const first = [...root.querySelectorAll('[required]')].find((f) => !f.value.trim());
    if (first) whenRevealed(() => { if (document.activeElement === document.body || !root.contains(document.activeElement)) first.focus({ preventScroll: true }); });
  }

  /* ---------- Submit: client checks, loading state, no double submit ---------- */
  const button = root.querySelector('[data-submit]');
  const label = button?.querySelector('[data-submit-label]');
  const idle = label?.textContent ?? '';
  let sending = false;

  function setLoading(on) {
    if (!button) return;
    button.classList.toggle('is-loading', on);
    ['aria-disabled', 'aria-busy'].forEach((attr) => {
      const el = attr === 'aria-busy' ? root : button;
      if (on) el.setAttribute(attr, 'true');
      else el.removeAttribute(attr);
    });
    if (label) label.textContent = on ? root.dataset.sending || idle : idle;
    if (status) status.textContent = on ? root.dataset.sendingStatus || '' : '';
  }

  root.addEventListener('submit', (e) => {
    if (sending) {
      e.preventDefault();
      return;
    }
    if (canValidate && summaryTpl) {
      const errors = fields.map((field) => [field, check(field)]).filter(([, message]) => message);
      fields.forEach((field) => setError(field, check(field)));
      if (errors.length) {
        e.preventDefault();
        showSummary(errors);
        return;
      }
    }
    sending = true;
    setLoading(true);
  });
  // Back from the thank-you page (bfcache): the form is usable again
  window.addEventListener('pageshow', (e) => {
    if (!e.persisted) return;
    sending = false;
    setLoading(false);
  });

  /* ---------- Pre-fill chip ---------- */
  const chip = panel.querySelector('[data-prefill]');
  chip?.querySelector('[data-prefill-clear]')?.addEventListener('click', (e) => {
    e.preventDefault();
    ['sector', 'service'].forEach((name) => {
      const select = root.elements.namedItem(name);
      if (!(select instanceof HTMLSelectElement) || select.value === '') return;
      select.value = '';
      select.dispatchEvent(new Event('change', { bubbles: true }));   // the C31 combobox follows
    });
    chip.remove();
    if (status) status.textContent = root.dataset.cleared || '';
    history.replaceState(history.state, '', `${location.pathname}${location.hash}`);   // a reload starts clean
    const sector = document.getElementById('f-sector-combo') || document.getElementById('f-sector');
    sector?.focus();
  });
}
