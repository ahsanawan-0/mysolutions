// Article body helpers (data-module="article" on the .prose body, sections/insights/article.php, P26):
//  - a table wider than its frame becomes a focusable, labelled scroll region (keyboard users can scroll it; WCAG
//    2.1.1), and stops being one when it fits again;
//  - lazy images in the body refresh ScrollTrigger once they load, so the contents scrollspy and the progress bar
//    keep measuring the right positions.
import { refreshOnLazyImages } from '../anim/_util.js';

export default function article(body) {
  const tables = [...body.querySelectorAll('.prose-table')];
  const label = (wrap) => {
    const heads = [...wrap.querySelectorAll('th')].map((th) => th.textContent.trim()).filter(Boolean);
    return heads.length ? `Table: ${heads.join(', ')}` : 'Table';
  };
  const check = () => tables.forEach((wrap) => {
    if (wrap.scrollWidth > wrap.clientWidth + 1) {
      wrap.setAttribute('tabindex', '0');
      wrap.setAttribute('role', 'region');
      wrap.setAttribute('aria-label', label(wrap));
    } else {
      ['tabindex', 'role', 'aria-label'].forEach((name) => wrap.removeAttribute(name));
    }
  });
  if (tables.length) {
    check();
    new ResizeObserver(check).observe(body);
  }
  refreshOnLazyImages(body);
}
