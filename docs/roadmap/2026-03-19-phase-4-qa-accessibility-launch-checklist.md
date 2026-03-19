# Roadmap Phase 4: QA, Accessibility, and Launch Checklist

Date: 2026-03-19
Issue: LEF-7

## Scope Covered

- Cross-page smoke QA for all 5 core pages.
- Keyboard/screen-reader readiness checks through semantic and ARIA structure validation.
- Basic performance review using static asset/page weight baselines.
- Launch checklist with explicit rollback procedure.

## Verification Executed

### 1. SEO + semantic structure regression test

Command:

```bash
node tests/seo-structure.test.js
```

Result:

- Passed: all 5 core pages contain required description/canonical/OG/Twitter tags.
- Passed: all pages include section labeling (`aria-labelledby`) and related-links section.

### 2. Navigation and accessibility structure checks

Commands:

```bash
node --check static/js/nav-config.js
node --check static/js/navbar.js
```

Result:

- JavaScript syntax checks passed.
- Navigation script includes:
  - `aria-expanded` state management for desktop and mobile submenu controls.
  - `aria-controls` linkage for collapsible groups.
  - `aria-current="page"` on active route links.
  - Focus-visible styling for interactive controls.

### 3. Anchor integrity checks for menu targets

Result:

- All configured hash targets in `static/js/nav-config.js` exist in page markup.
- No missing section IDs detected for submenu destinations.

### 4. Basic performance baseline (static size budget)

Asset/page sizes (bytes):

- `static/css/index.css`: 9,631
- `static/js/navbar.js`: 16,510
- `static/js/nav-config.js`: 2,889
- `quienes-somos.html`: 4,358
- `especialidades.html`: 4,319
- `tratamiento-medico-integral.html`: 3,230
- `club-vida-y-salud.html`: 2,899
- `contactanos.html`: 3,089
- Total reviewed footprint: 46,925 bytes

Assessment:

- Lightweight static payload profile.
- No immediate static-size risk for first-load on mobile networks.

## QA Summary

- No blocking defects found in static QA scope.
- Launch can proceed for this phase, with noted post-launch follow-ups below.

## Post-Launch Follow-Ups (Recommended)

- Run manual assistive-tech pass on target devices/browsers:
  - Android + TalkBack + Chrome
  - iOS + VoiceOver + Safari
- Run Lighthouse/WebPageTest against production URL once deployed (real network + cache behavior).
- Validate WhatsApp CTA and canonical host behavior after DNS/TLS is live.

## Launch Checklist

- [x] Core pages render with single `<h1>` and consistent section structure.
- [x] Canonical, OG, and Twitter metadata present on all core pages.
- [x] Navigation links and section anchors resolve correctly.
- [x] Keyboard-focus visible states present for interactive nav controls.
- [x] Mobile menu ARIA toggling states present and reset behavior implemented.
- [x] Static asset size baseline captured.
- [ ] Production smoke test after deployment (desktop + mobile).
- [ ] Live accessibility spot-check with screen reader on production.
- [ ] Production analytics/monitoring verification.

## Rollback Notes

If launch introduces navigation/markup regressions:

1. Revert Phase 4 documentation-only artifacts first (no runtime impact expected).
2. If runtime behavior is affected by collateral edits, restore last known-good versions of:
   - `static/js/navbar.js`
   - `static/js/nav-config.js`
   - `static/css/index.css`
   - `*.html` pages touched in latest deploy
3. Re-run:

```bash
node tests/seo-structure.test.js
node --check static/js/nav-config.js
node --check static/js/navbar.js
```

4. Redeploy only after checks pass and nav anchors are revalidated.
