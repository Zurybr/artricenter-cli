# Navbar Shadcn/Radix-Like Redesign Spec

## Goal

Create a static multi-page navigation system that feels similar to Radix/shadcn UX, keeps the existing site identity, improves mobile-first behavior, and adds two high-visibility floating CTAs (Dra. Edith and WhatsApp) without changing the core site content model.

## Scope

### In scope

- Replace the current navbar interaction model across static pages.
- Keep visual style close to current references, with small UX upgrades only.
- Remove `Pregúntale a la Dra. Edith` from navbar links.
- Add a floating sticky orange `Pregúntale a la Dra. Edith` button.
- Add a floating sticky `WhatsApp` button with the provided URL.
- Support cross-page navigation with section anchors and smooth scroll.
- Support three responsive modes: mobile, tablet, desktop.
- Implement with static `HTML + CSS + JS`.

### Out of scope

- Backend/CMS integration.
- Rewriting all page content structure beyond required anchor ids.
- Framework migration.

## Information Architecture

Navbar structure (authoritative):

1. `Quienes Somos`
   - `Artricenter`
   - `Nuestra Historia`
   - `Nuestros Medicos`
   - `Mision | Vision | Valores`
2. `Especialidades`
   - `Artrosis / Osteoartrosis`
   - `Artritis Reumatoide`
   - `Fibromialgia`
   - `Espondilitis Anquilosante`
   - `Reumatismos de Partes Blandas`
3. `Tratamiento Medico Integral`
   - `Diagnostico`
   - `Paiper`
4. `Club Vida y Salud` (no submenu)
5. `Contactanos`
   - `Testimonios`
   - `Blog`
   - `Contactanos`

Target static pages:

- `quienes-somos.html`
- `especialidades.html`
- `tratamiento-medico-integral.html`
- `club-vida-y-salud.html`
- `contactanos.html`

## UX Behavior Requirements

### General

- Navbar is sticky and remains discoverable during scroll.
- Visual language should resemble shadcn/radix patterns (clean spacing, rounded panels, subtle shadows, structured focus states), while staying visually close to the provided references.
- Dropdown panels are scroll-managed (internal scroll region pattern).

### Cross-page + anchor navigation

- Clicking a parent item navigates to its page root.
- Clicking a submenu item:
  - If on the same page: smooth-scroll to target section id.
  - If on a different page: navigate to `page.html#section-id`, then apply smooth-scroll with sticky-header offset after load.

### Responsive modes

- `Mobile` (`< 768px`):
  - Menu opens as a drawer/sheet.
  - Submenus shown in expandable groups.
  - Floating buttons stacked vertically for clear identification and thumb reach.
- `Tablet` (`768px-1023px`):
  - Compact top nav with touch-friendly dropdown triggers.
  - Floating buttons fixed at bottom corners: Edith left, WhatsApp right.
- `Desktop` (`>= 1024px`):
  - Full horizontal nav with dropdown panels.
  - Floating buttons fixed at bottom corners: Edith left, WhatsApp right.

### Floating CTAs

1. `Pregúntale a la Dra. Edith`
   - Sticky/floating, orange, animated, with creative hover treatment.
   - Removed from navbar and submenu links.
2. `WhatsApp`
   - Sticky/floating button.
   - URL must be exactly:

`https://web.whatsapp.com/send?phone=525559890607&text=https%3A%2F%2Fartricenter.com.mx%2F%0D%0A%0D%0A%0D%0A%0D%0A%C2%A1Quiero%20agendar%20una%20consulta%20de%20valoraci%C3%B3n%20sin%20costo!`

## Technical Design

## File structure

- `static/css/index.css` (single global stylesheet)
- `static/js/nav-config.js` (single source of truth for nav tree + routes + section ids)
- `static/js/navbar.js` (rendering + interactions + responsive behavior + routing/scroll logic)
- `*.html` static pages using shared navbar container and shared assets

### Data model (`nav-config.js`)

Each top-level item defines:

- display label
- target page path
- optional submenu array of `{ label, page, hash }`

This enables uniform rendering in desktop/tablet/mobile without duplicating menu logic.

### Interaction engine (`navbar.js`)

- Render top-level and nested items from config.
- Handle open/close state for dropdowns and mobile groups.
- Apply accessibility semantics (`aria-expanded`, keyboard escape, focus visibility).
- Resolve route + hash behavior consistently across same-page and cross-page cases.
- Apply sticky-header offset when scrolling to anchors.

### Styling (`index.css`)

Single CSS file includes:

- Design tokens (spacing, colors, border radius, shadows, z-index)
- Header and nav layout rules
- Dropdown panel and scroll-region styles
- Mobile drawer and tablet adjustments
- CTA floating buttons and hover/animation effects
- Motion transitions (blur/fade/slide) with restrained intensity

## Accessibility and Quality

- Keyboard support for menu controls and close behavior.
- Sufficient color contrast and visible focus rings.
- Touch-friendly target sizes for mobile/tablet.
- Respect reduced motion preference where practical.

## Acceptance Criteria

- Navbar matches required information architecture exactly.
- `Pregúntale a la Dra. Edith` is absent from navbar and present as floating orange CTA.
- WhatsApp floating CTA opens the provided URL.
- Parent items route to correct pages.
- Subitems route and smooth-scroll correctly from same page and from other pages.
- Dropdowns feel shadcn/radix-like and use internal scroll pattern.
- Behavior is verified in mobile, tablet, and desktop breakpoints.

## Risks and Mitigations

- Inconsistent anchor ids across pages -> define and verify canonical ids during page assembly.
- Sticky offset mismatch -> centralize offset calculation in JS helper.
- Over-animated UI -> keep animation subtle and short; prioritize clarity.

## Definition of Done

- All five pages share one nav system and one global CSS.
- Navigation and floating CTAs work across target breakpoints.
- UX remains close to references with only controlled, small improvements.
- No runtime JS errors in core navigation flows.
