# Navbar Static Shadcn/Radix-Like Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a mobile-first, 3-breakpoint navbar system (desktop/tablet/mobile) with cross-page+anchor navigation, scroll-managed dropdowns, and two floating CTAs (Dra. Edith + WhatsApp) across static HTML pages.

**Architecture:** Use one global CSS (`static/css/index.css`) and two JS files (`static/js/nav-config.js`, `static/js/navbar.js`). `nav-config.js` is the single source of truth for labels/routes/anchors; `navbar.js` renders interactions and smooth scrolling behavior for same-page and cross-page submenu clicks.

**Tech Stack:** Static HTML, CSS, vanilla JavaScript (no framework)

---

## Parallel Execution Strategy (Subagents)

- **Lane A (Core nav logic):** `static/js/nav-config.js`, `static/js/navbar.js`
- **Lane B (Global styles):** `static/css/index.css`
- **Lane C (Page markup + anchors):** all `*.html`

Run A + B in parallel first, then C (depends on nav selectors and class names), then final integration.

---

### Task 1: Define contracts and scaffold shared assets

**Files:**
- Create: `static/js/nav-config.js`
- Create: `static/js/navbar.js`
- Create: `static/css/index.css`

- [ ] **Step 1: Write the nav configuration contract**

```js
// static/js/nav-config.js
window.ARTRICENTER_NAV = {
  items: [
    {
      label: 'Quienes Somos',
      page: 'quienes-somos.html',
      children: [
        { label: 'Artricenter', page: 'quienes-somos.html', hash: '#artricenter' },
        { label: 'Nuestra Historia', page: 'quienes-somos.html', hash: '#nuestra-historia' },
        { label: 'Nuestros Medicos', page: 'quienes-somos.html', hash: '#nuestros-medicos' },
        { label: 'Mision | Vision | Valores', page: 'quienes-somos.html', hash: '#mision-vision-valores' }
      ]
    },
    // ...especialidades, tratamiento, club vida y salud, contactanos
  ],
  ctas: {
    edith: { label: 'Pregúntale a la Dra. Edith', href: '#edith' },
    whatsapp: {
      label: 'WhatsApp',
      href: 'https://web.whatsapp.com/send?phone=525559890607&text=https%3A%2F%2Fartricenter.com.mx%2F%0D%0A%0D%0A%0D%0A%0D%0A%C2%A1Quiero%20agendar%20una%20consulta%20de%20valoraci%C3%B3n%20sin%20costo!'
    }
  }
};
```

- [ ] **Step 2: Build JS scaffold with exported/init entrypoints**

```js
// static/js/navbar.js
(function () {
  function initNavbar() { /* render + listeners */ }
  document.addEventListener('DOMContentLoaded', initNavbar);
})();
```

- [ ] **Step 3: Build CSS scaffold sections in one file**

```css
/* static/css/index.css */
/* 1) tokens 2) layout 3) navbar 4) dropdown 5) mobile/tablet 6) floating-cta 7) animations */
```

- [ ] **Step 4: Commit scaffold**

```bash
git add static/js/nav-config.js static/js/navbar.js static/css/index.css
git commit -m "feat(nav): scaffold shared config, logic, and global styles"
```

---

### Task 2: Implement navbar interactions and navigation behavior

**Files:**
- Modify: `static/js/navbar.js`
- Modify: `static/js/nav-config.js`

- [ ] **Step 1: Write failing behavior checklist (manual TDD-lite)**

Create a temporary checklist at top of `static/js/navbar.js` comments:
- Parent click routes to page root.
- Submenu click on same page smooth-scrolls to anchor.
- Submenu click on other page routes with `#hash` and scrolls after load.
- Dropdown closes on outside click and `Escape`.

- [ ] **Step 2: Implement desktop/tablet/mobile interaction model**

Implement:
- Desktop: dropdown panel per top-level item with children.
- Tablet: compact nav + touch-friendly dropdown triggers.
- Mobile: drawer + expandable groups.

- [ ] **Step 3: Implement cross-page + same-page anchor resolver**

```js
function navigateTo(page, hash) {
  const current = location.pathname.split('/').pop() || 'quienes-somos.html';
  if (current === page && hash) {
    scrollToHash(hash);
    history.replaceState(null, '', hash);
    return;
  }
  location.href = hash ? `${page}${hash}` : page;
}
```

- [ ] **Step 4: Implement sticky offset smooth scroll**

```js
function scrollToHash(hash) {
  const el = document.querySelector(hash);
  if (!el) return;
  const header = document.querySelector('[data-site-header]');
  const offset = (header?.offsetHeight || 0) + 12;
  const y = el.getBoundingClientRect().top + window.scrollY - offset;
  window.scrollTo({ top: y, behavior: 'smooth' });
}
```

- [ ] **Step 5: Run smoke verification in browser**

Run:
```bash
python3 -m http.server 5500
```
Expected: local site serves and navigation interactions execute without JS errors.

- [ ] **Step 6: Commit interaction logic**

```bash
git add static/js/nav-config.js static/js/navbar.js
git commit -m "feat(nav): add dropdown, mobile drawer, and cross-page anchor routing"
```

---

### Task 3: Implement single global CSS with 3 breakpoints and shadcn/radix-like feel

**Files:**
- Modify: `static/css/index.css`

- [ ] **Step 1: Implement design tokens + base styles**

Add variables for brand blue/green/orange, spacing scale, radii, shadows, transition timing.

- [ ] **Step 2: Implement sticky navbar + blur + states**

Include:
- sticky top behavior
- translucent background with blur on scroll
- active/hover/focus states

- [ ] **Step 3: Implement dropdown panel styles and internal scroll area**

Use max-height + `overflow-y: auto` for submenu region and subtle scrollbar styling.

- [ ] **Step 4: Implement responsive rules**

```css
/* mobile first */
@media (min-width: 768px) { /* tablet */ }
@media (min-width: 1024px) { /* desktop */ }
```

- [ ] **Step 5: Implement floating CTAs**

Rules:
- Desktop/tablet: Edith bottom-left, WhatsApp bottom-right.
- Mobile: vertical stack with clear labels and spacing.
- Edith: orange with pulse/hover creative effect.

- [ ] **Step 6: Commit styles**

```bash
git add static/css/index.css
git commit -m "feat(ui): style responsive navbar, dropdown scroll areas, and floating CTAs"
```

---

### Task 4: Update static pages to consume shared navbar and anchor IDs

**Files:**
- Create/Modify: `quienes-somos.html`
- Create/Modify: `especialidades.html`
- Create/Modify: `tratamiento-medico-integral.html`
- Create/Modify: `club-vida-y-salud.html`
- Create/Modify: `contactanos.html`

- [ ] **Step 1: Add shared header mount and assets to each page**

Add in all pages:

```html
<link rel="stylesheet" href="static/css/index.css" />
<header data-site-header>
  <div id="site-navbar"></div>
</header>
<script src="static/js/nav-config.js"></script>
<script src="static/js/navbar.js"></script>
```

- [ ] **Step 2: Add canonical anchor IDs required by submenu links**

Examples:
- `#artricenter`, `#nuestra-historia`, `#nuestros-medicos`, `#mision-vision-valores`
- `#artrosis-osteoartrosis`, `#artritis-reumatoide`, etc.
- `#diagnostico`, `#paiper`
- `#testimonios`, `#blog`, `#contactanos`

- [ ] **Step 3: Ensure `Club Vida y Salud` has no submenu rendering path**

Verify config item has no `children` and routes directly to `club-vida-y-salud.html`.

- [ ] **Step 4: Keep visual similarity with references (minimal UX changes)**

Only modernize interaction layer; do not re-theme whole pages.

- [ ] **Step 5: Commit page wiring**

```bash
git add quienes-somos.html especialidades.html tratamiento-medico-integral.html club-vida-y-salud.html contactanos.html
git commit -m "feat(pages): wire shared navbar system and section anchors across static pages"
```

---

### Task 5: Final integration QA and polish

**Files:**
- Modify (if needed): `static/css/index.css`, `static/js/navbar.js`, `*.html`

- [ ] **Step 1: End-to-end nav verification matrix**

Check all breakpoints:
- Mobile `<768`
- Tablet `768-1023`
- Desktop `>=1024`

Scenarios:
- Parent navigation from each page.
- Submenu cross-page -> section scroll.
- Submenu same-page -> smooth scroll.
- Dropdown close via outside click + `Esc`.

- [ ] **Step 2: Floating CTA verification**

Confirm:
- Edith visible and animated, orange, sticky.
- WhatsApp button opens exact URL.
- On mobile, CTAs are stacked and easy to identify.

- [ ] **Step 3: JS console health check**

Expected: no uncaught runtime errors during primary nav flows.

- [ ] **Step 4: Final commit**

```bash
git add static/css/index.css static/js/navbar.js static/js/nav-config.js quienes-somos.html especialidades.html tratamiento-medico-integral.html club-vida-y-salud.html contactanos.html
git commit -m "fix(nav): finalize responsive UX polish and navigation reliability"
```

---

## Done Criteria

- Navbar information architecture exactly matches approved tree.
- `Pregúntale a la Dra. Edith` removed from navbar links and available as floating CTA.
- WhatsApp floating CTA uses exact provided URL.
- Submenu click behavior supports same-page and cross-page anchor scrolling.
- Dropdowns are scroll-managed and look/feel close to shadcn/radix patterns.
- UX works in mobile/tablet/desktop with requested CTA placement behavior.
