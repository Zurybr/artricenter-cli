# Roadmap Phase 3: SEO and Content Structure

## Scope
- Add search metadata and social metadata on key site pages.
- Improve semantic section structure and accessibility labels.
- Add explicit internal related-link blocks for crawl paths and content discovery.
- Add automated validation for SEO baseline checks.

## Updated Pages
- `quienes-somos.html`
- `especialidades.html`
- `tratamiento-medico-integral.html`
- `club-vida-y-salud.html`
- `contactanos.html`

## SEO Baseline Implemented
- `<meta name="description">` per page.
- `<meta name="robots" content="index, follow">` per page.
- `<link rel="canonical">` per page.
- Open Graph essentials (`og:locale`, `og:type`, `og:site_name`, `og:title`, `og:description`, `og:url`).
- Twitter card essentials (`twitter:card`, `twitter:title`, `twitter:description`).
- JSON-LD `MedicalClinic` entity added on `quienes-somos.html`.

## Content Structure and Internal Linking
- Added `aria-labelledby` references for content sections across all updated pages.
- Added `Enlaces relacionados` sections with crawlable internal links to complementary pages.
- Added shared styles for related-link blocks in `static/css/index.css`.

## Verification
Commands executed:

```bash
node tests/seo-structure.test.js
node --check static/js/nav-config.js
node --check static/js/navbar.js
```

Outcome:
- SEO structure test passed for all core pages.
- JavaScript syntax checks passed.

## Notes
- Workspace is not a Git repository, so changes were not committed in this heartbeat.
