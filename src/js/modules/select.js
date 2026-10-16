// Custom select (C31): enhances the native <select> inside `[data-module="select"]` into an accessible select-only
// combobox (WAI-ARIA APG pattern). A focusable role="combobox" shows the value; a role="listbox" popup lists the
// options: <optgroup>s become labelled groups, `data-icon` on an <option>/<optgroup> adds a Phosphor icon (light,
// from the sprite in `data-sprite`), `data-colour` on an <optgroup> tints its icons with the category colour.
// The native <select> stays in the form (visually hidden) and keeps the value, so submission, reset, validation and
// back/forward restore work unchanged; without JS the native control is what people use.
// The popup lives in the top layer (Popover API), so the hero panel's clipping and transforms never cut it off;
// browsers without popover get it appended to <body> with position: fixed. It opens below the field, or above when
// there is more room there, and follows the field while the page scrolls.
// Keyboard: ↓ ↑ Enter Space open · ↓ ↑ Home End PageUp PageDown move · Enter Space Tab Alt+↑ choose · Esc closes
// without a change · letters jump to the next matching option (type-ahead).
const SVG_NS = 'http://www.w3.org/2000/svg';
const POPOVER = typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;
const GAP = 6;        // px between the field and the popup
const EDGE = 8;       // px kept free at the viewport edges
const MAX_H = 360;    // px, the popup scrolls beyond this
const MIN_W = 280;    // px, wide enough for the longest option with its icon and check
const PAGE = 10;      // options moved by PageUp / PageDown
let uid = 0;

export default function initSelect(root) {
  const select = root.querySelector('select');
  if (!select || 'selectReady' in root.dataset) return;
  root.dataset.selectReady = '';

  const id = select.id || `select-${++uid}`;
  select.id = id;
  const label = document.querySelector(`label[for="${id}"]`);
  if (label && !label.id) label.id = `${id}-label`;
  const anchor = root.closest('[data-select-anchor]') || root;
  const sprite = root.dataset.sprite || '';

  const icon = (name, weight = 'light', cls = '') => {
    if (!name || !sprite) return null;
    const svg = document.createElementNS(SVG_NS, 'svg');
    svg.setAttribute('class', `icon ${cls}`.trim());
    svg.setAttribute('width', '18');
    svg.setAttribute('height', '18');
    svg.setAttribute('aria-hidden', 'true');
    svg.setAttribute('focusable', 'false');
    const use = document.createElementNS(SVG_NS, 'use');
    use.setAttribute('href', `${sprite}#${name}-${weight}`);
    svg.append(use);
    return svg;
  };

  /* ---------- Combobox (the visible control) ---------- */
  const combo = document.createElement('div');
  combo.id = `${id}-combo`;
  combo.className = `${select.className} select__trigger`.trim();
  combo.tabIndex = select.disabled ? -1 : 0;
  combo.setAttribute('role', 'combobox');
  combo.setAttribute('aria-haspopup', 'listbox');
  combo.setAttribute('aria-expanded', 'false');
  combo.setAttribute('aria-controls', `${id}-list`);
  if (label) combo.setAttribute('aria-labelledby', label.id);
  else if (select.getAttribute('aria-label')) combo.setAttribute('aria-label', select.getAttribute('aria-label'));
  if (select.disabled) combo.setAttribute('aria-disabled', 'true');
  if (select.required) combo.setAttribute('aria-required', 'true');
  // server-side errors and hints (P23 forms) belong to the control people actually use
  ['aria-invalid', 'aria-describedby'].forEach((attr) => {
    if (select.hasAttribute(attr)) combo.setAttribute(attr, select.getAttribute(attr));
  });
  const valueEl = document.createElement('span');
  valueEl.className = 'select__value';
  combo.append(valueEl);

  /* ---------- Listbox (the popup) ---------- */
  const list = document.createElement('div');
  list.id = `${id}-list`;
  list.className = 'select__list';
  list.tabIndex = -1;
  list.setAttribute('role', 'listbox');
  list.setAttribute('data-lenis-prevent', '');   // wheel scrolls the list, not the page (Lenis)
  if (label) list.setAttribute('aria-labelledby', label.id);
  if (POPOVER) list.popover = 'manual';
  else list.classList.add('select__list--fixed');

  const options = [];   // same order as select.options, so the indexes match select.selectedIndex
  const addOption = (opt, parent, groupColour) => {
    const el = document.createElement('div');
    const i = options.length;
    el.id = `${id}-opt-${i}`;
    el.className = 'select__option';
    el.dataset.index = String(i);
    el.setAttribute('role', 'option');
    el.setAttribute('aria-selected', 'false');
    if (opt.disabled) el.setAttribute('aria-disabled', 'true');
    const text = document.createElement('span');
    text.className = 'select__text';
    text.textContent = opt.textContent.trim();
    el.append(...[icon(opt.dataset.icon), text, icon('check', 'regular', 'select__check')].filter(Boolean));
    if (groupColour) el.dataset.colour = groupColour;
    options.push({ el, opt, text: text.textContent.toLowerCase() });
    parent.append(el);
  };
  [...select.children].forEach((child, g) => {
    if (child.tagName !== 'OPTGROUP') {
      addOption(child, list);
      return;
    }
    const group = document.createElement('div');
    group.className = 'select__group';
    group.setAttribute('role', 'group');
    if (child.dataset.colour) group.dataset.colour = child.dataset.colour;
    const head = document.createElement('div');
    head.id = `${id}-group-${g}`;
    head.className = 'select__group-label';
    head.setAttribute('role', 'presentation');
    const headText = document.createElement('span');
    headText.textContent = child.label;
    head.append(...[icon(child.dataset.icon), headText].filter(Boolean));
    group.setAttribute('aria-labelledby', head.id);
    group.append(head);
    [...child.children].forEach((opt) => addOption(opt, group, child.dataset.colour));
    list.append(group);
  });

  // The native control stays for the form, out of sight and out of the tab order
  select.classList.add('select__native');
  select.tabIndex = -1;
  select.setAttribute('aria-hidden', 'true');
  select.before(combo);
  if (POPOVER) combo.after(list);
  else document.body.append(list);
  root.classList.add('is-enhanced');

  /* ---------- State ---------- */
  let open = false;
  let active = -1;
  const enabled = (i) => i >= 0 && i < options.length && !options[i].opt.disabled;

  function sync() {
    const i = select.selectedIndex;
    valueEl.textContent = options[i]?.opt.textContent.trim() ?? '';
    options.forEach(({ el }, j) => el.setAttribute('aria-selected', String(j === i)));
    root.classList.toggle('has-value', select.value !== '');
  }

  function setActive(i, scroll = true) {
    if (!enabled(i)) return;
    options[active]?.el.classList.remove('is-active');
    active = i;
    const { el } = options[i];
    el.classList.add('is-active');
    combo.setAttribute('aria-activedescendant', el.id);
    if (scroll) el.scrollIntoView({ block: 'nearest' });
  }

  /** The index `count` enabled options away from `from` in direction `dir`, stopping at the ends. */
  function step(from, dir, count = 1) {
    let i = from;
    let last = from;
    for (let moved = 0; moved < count;) {
      i += dir;
      if (i < 0 || i >= options.length) break;
      if (enabled(i)) {
        last = i;
        moved += 1;
      }
    }
    return last;
  }

  function choose(i) {
    if (!enabled(i) || select.selectedIndex === i) return;
    select.selectedIndex = i;
    sync();
    select.dispatchEvent(new Event('input', { bubbles: true }));
    select.dispatchEvent(new Event('change', { bubbles: true }));
  }

  function place() {
    const r = anchor.getBoundingClientRect();
    const vw = document.documentElement.clientWidth;
    const vh = window.innerHeight;
    if (r.bottom < 0 || r.top > vh) {
      hide();
      return;
    }
    const keep = list.scrollTop;   // re-measuring below resets it
    const width = Math.min(Math.max(r.width, MIN_W), vw - EDGE * 2);
    list.style.width = `${width}px`;
    list.style.left = `${Math.min(Math.max(r.left, EDGE), vw - width - EDGE)}px`;
    list.style.maxHeight = 'none';
    const natural = Math.min(list.scrollHeight + 2, MAX_H);
    const below = vh - r.bottom - GAP - EDGE;
    const above = r.top - GAP - EDGE;
    const up = below < natural && above > below;
    list.style.maxHeight = `${Math.max(120, Math.min(MAX_H, up ? above : below))}px`;
    list.style.top = up ? 'auto' : `${r.bottom + GAP}px`;
    list.style.bottom = up ? `${vh - r.top + GAP}px` : 'auto';
    list.dataset.side = up ? 'top' : 'bottom';
    list.scrollTop = keep;
  }

  let frame = 0;
  const onViewport = (e) => {
    if (e?.target === list || frame) return;
    frame = requestAnimationFrame(() => {
      frame = 0;
      if (open) place();
    });
  };
  const onOutside = (e) => {
    if (!combo.contains(e.target) && !list.contains(e.target) && !label?.contains(e.target)) hide();
  };

  function show() {
    if (open || select.disabled) return;
    open = true;
    combo.setAttribute('aria-expanded', 'true');
    root.classList.add('is-open');
    if (POPOVER) list.showPopover();
    else list.classList.add('is-open');
    place();
    setActive(enabled(select.selectedIndex) ? select.selectedIndex : step(-1, 1));
    window.addEventListener('scroll', onViewport, { passive: true, capture: true });
    window.addEventListener('resize', onViewport, { passive: true });
    document.addEventListener('pointerdown', onOutside, true);
  }

  function hide({ focus = false } = {}) {
    if (!open) return;
    open = false;
    typed = '';
    combo.setAttribute('aria-expanded', 'false');
    combo.removeAttribute('aria-activedescendant');
    root.classList.remove('is-open');
    if (POPOVER) {
      if (list.matches(':popover-open')) list.hidePopover();
    } else {
      list.classList.remove('is-open');
    }
    window.removeEventListener('scroll', onViewport, { capture: true });
    window.removeEventListener('resize', onViewport);
    document.removeEventListener('pointerdown', onOutside, true);
    if (focus) combo.focus();
  }

  /* ---------- Type-ahead ---------- */
  let typed = '';
  let typedAt = 0;
  const typing = () => typed !== '' && Date.now() - typedAt < 600;
  function typeahead(ch) {
    typed = typing() ? typed + ch.toLowerCase() : ch.toLowerCase();
    typedAt = Date.now();
    const repeat = [...typed].every((c) => c === typed[0]);   // "sss" cycles through the S options
    const query = repeat ? typed[0] : typed;
    const from = active < 0 ? 0 : active + (typed.length === 1 || repeat ? 1 : 0);
    for (let k = 0; k < options.length; k += 1) {
      const i = (from + k) % options.length;
      if (enabled(i) && options[i].text.startsWith(query)) {
        setActive(i);
        return;
      }
    }
  }

  /* ---------- Events ---------- */
  combo.addEventListener('keydown', (e) => {
    if (select.disabled) return;
    const { key, altKey, ctrlKey, metaKey } = e;
    const printable = key.length === 1 && !ctrlKey && !metaKey && !altKey && (key !== ' ' || typing());

    if (!open) {
      if (['ArrowDown', 'ArrowUp', 'Enter', ' ', 'Home', 'End'].includes(key) || printable) {
        e.preventDefault();
        show();
        if (key === 'Home') setActive(step(-1, 1));
        else if (key === 'End') setActive(step(options.length, -1));
        else if (printable) typeahead(key);
      }
      return;
    }

    switch (key) {
      case 'ArrowDown':
        e.preventDefault();
        setActive(step(active, 1));
        break;
      case 'ArrowUp':
        e.preventDefault();
        if (altKey) {
          choose(active);
          hide();
        } else {
          setActive(step(active, -1));
        }
        break;
      case 'PageDown':
        e.preventDefault();
        setActive(step(active, 1, PAGE));
        break;
      case 'PageUp':
        e.preventDefault();
        setActive(step(active, -1, PAGE));
        break;
      case 'Home':
        e.preventDefault();
        setActive(step(-1, 1));
        break;
      case 'End':
        e.preventDefault();
        setActive(step(options.length, -1));
        break;
      case 'Enter':
        e.preventDefault();
        choose(active);
        hide();
        break;
      case 'Escape':
        e.preventDefault();
        hide();
        break;
      case 'Tab':
        choose(active);
        hide();
        break;
      default:
        if (printable) {
          e.preventDefault();
          typeahead(key);
        } else if (key === ' ') {
          e.preventDefault();
          choose(active);
          hide();
        }
    }
  });

  combo.addEventListener('click', () => (open ? hide() : show()));
  combo.addEventListener('blur', () => hide());
  label?.addEventListener('click', (e) => {
    e.preventDefault();             // the label points at the hidden native control
    combo.focus();
    if (!open) show();
  });

  list.addEventListener('pointerdown', (e) => e.preventDefault());   // keep focus on the combobox
  list.addEventListener('pointermove', (e) => {
    const el = e.target.closest('[role="option"]');
    if (el && Number(el.dataset.index) !== active) setActive(Number(el.dataset.index), false);
  });
  list.addEventListener('click', (e) => {
    const el = e.target.closest('[role="option"]');
    if (!el || !enabled(Number(el.dataset.index))) return;
    choose(Number(el.dataset.index));
    hide({ focus: true });
  });

  // Keep the visible value in step with the native control (programmatic changes, form reset, bfcache restore)
  select.addEventListener('change', sync);
  select.form?.addEventListener('reset', () => setTimeout(sync));
  window.addEventListener('pageshow', sync);
  select.addEventListener('invalid', () => combo.focus());

  sync();
}
