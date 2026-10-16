'use strict';

(function () {

  /* ------------------------------------------------------------------ */
  /*  MOBILE NAVIGATION                                                  */
  /* ------------------------------------------------------------------ */
  const mobileToggle = document.getElementById('mobile-menu-toggle');
  const siteNav      = document.getElementById('site-nav');

  if (mobileToggle && siteNav) {
    mobileToggle.addEventListener('click', function () {
      const isOpen = siteNav.classList.toggle('is-open');
      mobileToggle.setAttribute('aria-expanded', String(isOpen));
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && siteNav.classList.contains('is-open')) {
        siteNav.classList.remove('is-open');
        mobileToggle.setAttribute('aria-expanded', 'false');
        mobileToggle.focus();
        document.body.style.overflow = '';
      }
    });
  }

  /* ------------------------------------------------------------------ */
  /*  SEARCH OVERLAY                                                     */
  /* ------------------------------------------------------------------ */
  const searchToggle  = document.getElementById('search-toggle');
  const searchOverlay = document.getElementById('header-search');
  const searchInput   = document.getElementById('header-search-input');

  if (searchToggle && searchOverlay) {
    searchToggle.addEventListener('click', function () {
      const isOpen = searchOverlay.classList.toggle('is-open');
      searchToggle.setAttribute('aria-expanded', String(isOpen));
      if (isOpen && searchInput) {
        setTimeout(() => searchInput.focus(), 80);
      }
    });

    searchInput && searchInput.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        searchOverlay.classList.remove('is-open');
        searchToggle.setAttribute('aria-expanded', 'false');
        searchToggle.focus();
      }
    });
  }

  /* ------------------------------------------------------------------ */
  /*  READING PROGRESS BAR                                               */
  /* ------------------------------------------------------------------ */
  const progress = document.getElementById('reading-progress');
  if (progress) {
    window.addEventListener('scroll', function () {
      const scrollTop  = window.scrollY;
      const docHeight  = document.documentElement.scrollHeight - window.innerHeight;
      const pct        = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
      progress.style.width = Math.min(pct, 100).toFixed(1) + '%';
    }, { passive: true });
  }

  /* ------------------------------------------------------------------ */
  /*  TABLE OF CONTENTS — auto-generated from article headings          */
  /* ------------------------------------------------------------------ */
  const tocList = document.getElementById('toc-list');
  const toc     = document.getElementById('toc');
  const article = document.querySelector('.article-body');

  if (tocList && article) {
    const headings = article.querySelectorAll('h2, h3');
    if (headings.length < 2) {
      if (toc) toc.style.display = 'none';
    } else {
      headings.forEach(function (h, i) {
        if (!h.id) {
          h.id = 'section-' + i + '-' + h.textContent.trim()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
        }
        const li = document.createElement('li');
        li.style.paddingLeft = h.tagName === 'H3' ? '1em' : '0';
        const a = document.createElement('a');
        a.href        = '#' + h.id;
        a.textContent = h.textContent;
        li.appendChild(a);
        tocList.appendChild(li);
      });

      // Active section highlight on scroll
      const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          const id  = entry.target.id;
          const link = tocList.querySelector('a[href="#' + id + '"]');
          if (!link) return;
          if (entry.isIntersecting) {
            tocList.querySelectorAll('a').forEach(a => a.classList.remove('is-active'));
            link.classList.add('is-active');
          }
        });
      }, { rootMargin: '-20% 0px -70% 0px' });

      headings.forEach(h => observer.observe(h));
    }
  }

  /* ------------------------------------------------------------------ */
  /*  COPY BUTTONS — for <pre> blocks                                    */
  /* ------------------------------------------------------------------ */
  document.querySelectorAll('pre').forEach(function (pre) {
    const wrapper = document.createElement('div');
    wrapper.className = 'code-block';
    pre.parentNode.insertBefore(wrapper, pre);
    wrapper.appendChild(pre);

    const btn = document.createElement('button');
    btn.className   = 'copy-btn';
    btn.textContent = 'Copy';
    btn.setAttribute('aria-label', 'Copy code to clipboard');
    wrapper.appendChild(btn);

    btn.addEventListener('click', function () {
      const code = pre.querySelector('code') || pre;
      navigator.clipboard.writeText(code.textContent).then(function () {
        btn.textContent = 'Copied!';
        btn.classList.add('is-copied');
        setTimeout(function () {
          btn.textContent = 'Copy';
          btn.classList.remove('is-copied');
        }, 2000);
      }).catch(function () {
        btn.textContent = 'Error';
        setTimeout(() => btn.textContent = 'Copy', 2000);
      });
    });
  });

  /* ------------------------------------------------------------------ */
  /*  INTERACTIVE CHECKLIST — localStorage persistence                  */
  /* ------------------------------------------------------------------ */
  const checklists = document.querySelectorAll('.checklist');
  checklists.forEach(function (list) {
    const pageKey = 'gal_checklist_' + window.location.pathname;
    const items   = list.querySelectorAll('li');

    // Restore state
    const saved = JSON.parse(localStorage.getItem(pageKey) || '{}');
    items.forEach(function (li, i) {
      if (saved[i]) li.classList.add('is-checked');
    });

    list.addEventListener('click', function (e) {
      const check = e.target.closest('.checklist__check');
      if (!check) return;
      const li  = check.closest('li');
      const idx = Array.from(items).indexOf(li);
      li.classList.toggle('is-checked');

      const state = {};
      items.forEach((item, i) => { state[i] = item.classList.contains('is-checked'); });
      localStorage.setItem(pageKey, JSON.stringify(state));
    });
  });

  /* ------------------------------------------------------------------ */
  /*  TUTORIAL FILTER BAR                                                */
  /* ------------------------------------------------------------------ */
  const filterBtns = document.querySelectorAll('.filter-btn');
  const tutGrid    = document.getElementById('tutorial-grid');
  const emptyState = document.getElementById('tutorials-empty');

  if (filterBtns.length && tutGrid) {
    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const filter = btn.getAttribute('data-filter');

        filterBtns.forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');
        btn.setAttribute('aria-pressed', 'true');

        const cards = tutGrid.querySelectorAll('[data-category]');
        let visible = 0;

        cards.forEach(function (card) {
          const cat = card.getAttribute('data-category');
          if (filter === 'all' || cat === filter) {
            card.style.display = '';
            visible++;
          } else {
            card.style.display = 'none';
          }
        });

        if (emptyState) {
          emptyState.style.display = visible === 0 ? 'block' : 'none';
        }
      });
    });
  }

  /* ------------------------------------------------------------------ */
  /*  NO-JS REMOVAL                                                      */
  /* ------------------------------------------------------------------ */
  document.documentElement.classList.remove('no-js');
  document.documentElement.classList.add('js');

})();
