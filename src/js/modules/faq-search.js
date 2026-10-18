// /faq/ search + topics (data-module="faq-search", templates/sections/faq/browser.php, P27).
//  - Filters as you type (after a short pause): a question matches when every word of the search appears in its
//    question or answer, ignoring case and accents (the same rule as faq_matches() in PHP, which serves ?q= without
//    JS). Non-matching questions and empty topics are hidden, the topic chips show the new counts, matched words are
//    marked in the questions and the count is announced by the role="status" line. ?q= follows in the URL.
//  - Enter (form submit) applies at once without reloading; Esc clears; "Show all questions" clears too.
//  - Topic chips: the topic crossing 30 % of the viewport marks its chip aria-current="true".
import { ScrollTrigger } from '../core/motion.js';

const fold = (s) => s.toLowerCase().normalize('NFD').replace(/\p{Mn}+/gu, '');
const DELAY = 250;

export default function faqSearch(root) {
  const form = root.querySelector('[data-faq-form]');
  const input = root.querySelector('[data-faq-input]');
  const status = root.querySelector('[data-faq-status]');
  const empty = root.querySelector('[data-faq-empty]');
  const groups = [...root.querySelectorAll('[data-faq-group]')];
  const chips = new Map([...root.querySelectorAll('[data-faq-chip]')].map((li) => [li.dataset.faqChip, li]));
  if (!form || !input || !groups.length) return;

  const items = groups.flatMap((g) => [...g.querySelectorAll('.accordion__item')].map((d) => {
    const q = d.querySelector('.accordion__q');
    return { d, slug: g.dataset.faqGroup, q, question: q.textContent, text: fold(d.textContent), marked: false };
  }));
  const fill = (template, vars) => Object.entries(vars).reduce((s, [k, v]) => s.split(`{${k}}`).join(String(v)), template || '');

  const unmark = (it) => {
    if (it.marked) { it.q.textContent = it.question; it.marked = false; }
  };
  const mark = (it, words) => {
    const text = it.question;
    const f = fold(text);
    if (!words.length || f.length !== text.length) return unmark(it);   // folding changed the length: no safe ranges
    const on = new Array(text.length).fill(false);
    words.forEach((w) => {
      for (let i = f.indexOf(w); i !== -1; i = f.indexOf(w, i + w.length)) on.fill(true, i, i + w.length);
    });
    if (!on.includes(true)) return unmark(it);
    const frag = document.createDocumentFragment();
    for (let i = 0; i < text.length;) {
      let j = i;
      while (j < text.length && on[j] === on[i]) j++;
      if (on[i]) {
        const m = document.createElement('mark');
        m.textContent = text.slice(i, j);
        frag.append(m);
      } else {
        frag.append(text.slice(i, j));
      }
      i = j;
    }
    it.q.replaceChildren(frag);
    it.marked = true;
  };

  let last = null;
  const apply = (raw) => {
    const query = raw.trim().slice(0, 80);
    if (query === last) return;
    last = query;
    const words = fold(query).split(/\s+/).filter(Boolean);
    const counts = {};
    let n = 0;
    items.forEach((it) => {
      const match = words.every((w) => it.text.includes(w));
      it.d.hidden = !match;
      if (match) {
        n++;
        counts[it.slug] = (counts[it.slug] || 0) + 1;
        mark(it, words);
      } else {
        unmark(it);
      }
    });
    groups.forEach((g) => { g.hidden = !counts[g.dataset.faqGroup]; });
    chips.forEach((li, slug) => {
      li.hidden = !counts[slug];
      const count = li.querySelector('[data-faq-count]');
      if (count) count.textContent = counts[slug] || 0;
    });
    if (empty) empty.hidden = n > 0;

    const d = root.dataset;
    status.textContent = !query
      ? fill(d.statusAll, { n: items.length, groups: groups.length })
      : n
        ? fill(d.statusMatch, { n, questions: n === 1 ? d.statusOne : d.statusMany, q: query })
        : fill(d.statusNone, { q: query });

    const url = new URL(location.href);
    if (query) url.searchParams.set('q', query); else url.searchParams.delete('q');
    history.replaceState(history.state, '', url);
    ScrollTrigger.refresh();
  };

  let timer = 0;
  input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(() => apply(input.value), DELAY);
  });
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    clearTimeout(timer);
    apply(input.value);
  });
  input.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape' || !input.value) return;
    e.preventDefault();
    input.value = '';
    clearTimeout(timer);
    apply('');
  });
  root.querySelector('[data-faq-clear]')?.addEventListener('click', (e) => {
    e.preventDefault();
    input.value = '';
    apply('');
    input.focus();
  });
  last = input.value.trim().slice(0, 80);   // the server already filtered ?q=; only mark the matched words
  if (last) items.forEach((it) => { if (!it.d.hidden) mark(it, fold(last).split(/\s+/).filter(Boolean)); });

  // Topic scrollspy
  let current = null;
  const setCurrent = (slug) => {
    const link = slug ? chips.get(slug)?.querySelector('a') : null;
    if (link === current) return;
    current?.removeAttribute('aria-current');
    current = link ?? null;
    current?.setAttribute('aria-current', 'true');
  };
  groups.forEach((g, i) => {
    ScrollTrigger.create({
      trigger: g,
      start: 'top 30%',
      end: 'bottom 30%',
      onToggle: (self) => { if (self.isActive) setCurrent(g.dataset.faqGroup); },
      onLeaveBack: () => { if (i === groups.findIndex((x) => !x.hidden)) setCurrent(null); },
    });
  });
}
