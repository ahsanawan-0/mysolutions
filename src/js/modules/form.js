// form: progressive enhancement for the enquiry form (data-module="form", templates/sections/contact/form.php, P23).
// The form works fully without it (server validation, error summary, PRG). This module adds:
//  · the character counter under textareas with `data-char-counter` (copy in the form's `data-counter`,
//    "{n} of {max} characters"; announced politely, and only near the limit so screen readers are not flooded);
//  · focus on the error summary / notice after a failed submit (the page is already aligned on #quote);
//  · summary links that focus their field, including a C31 custom select (its combobox, not the hidden <select>).
// P24 extends it (inline validation on blur, loading state, double-submit prevention).
const ANNOUNCE_FROM = 0.9;   // share of maxlength from which the count is announced

export default function form(root) {
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

  const summary = root.querySelector('[data-error-summary]');
  if (summary) {
    summary.focus({ preventScroll: !!location.hash });
    summary.querySelectorAll('a[href^="#"]').forEach((link) => {
      link.addEventListener('click', (e) => {
        const field = document.getElementById(link.hash.slice(1));
        if (!field) return;
        e.preventDefault();
        const target = document.getElementById(`${field.id}-combo`) || field;
        const box = target.closest('.field') || target;
        if (window.lenis) window.lenis.scrollTo(box, { offset: -120, immediate: matchMedia('(prefers-reduced-motion: reduce)').matches });
        else box.scrollIntoView({ block: 'center' });
        target.focus({ preventScroll: true });
      });
    });
  }
}
