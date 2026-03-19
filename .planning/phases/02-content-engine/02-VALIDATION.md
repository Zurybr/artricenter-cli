---
phase: 2
slug: content-engine
status: approved
nyquist_compliant: true
wave_0_complete: true
created: 2026-03-19
---

# Phase 2 — Validation Strategy

> Per-phase validation contract for feedback sampling during execution.

---

## Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | WordPress PHPUnit (integration tests) + Manual visual verification |
| **Config file** | `wp-content/plugins/artricenter-content/tests/bootstrap.php` |
| **Quick run command** | `docker compose exec wordpress wp plugin test --dependencies` |
| **Full suite command** | `docker compose exec wordpress wp plugin test` |
| **Estimated runtime** | ~45 seconds (Docker startup adds ~5s first run) |

---

## Sampling Rate

- **After every task commit:** Run visual verification (inspect in browser + admin)
- **After every plan wave:** Run `docker compose ps` + browser verification of all components
- **Before `/gsd:verify-work`:** All 9 success criteria must be verified
- **Max feedback latency:** 60 seconds

---

## Per-Task Verification Map

| Task ID | Plan | Wave | Requirement | Test Type | Automated Command | File Exists | Status |
|---------|------|------|-------------|-----------|-------------------|-------------|--------|
| 02-01-01 | 01 | 1 | CPT-01 | integration | `docker compose exec wordpress wp post type list doctor-artricenter` | ✅ W0 | ⬜ pending |
| 02-01-02 | 01 | 1 | CPT-02 | integration | `docker compose exec wordpress wp post type list especialidad-artricenter` | ✅ W0 | ⬜ pending |
| 02-01-03 | 01 | 1 | CPT-03 | integration | `docker compose exec wordpress wp post type list sucursal-artricenter` | ✅ W0 | ⬜ pending |
| 02-02-01 | 02 | 1 | PAGES-01 | manual | Create page, assign template, verify in browser | ✅ W0 | ⬜ pending |
| 02-02-02 | 02 | 1 | PAGES-02 | manual | Verify especialidades page template renders | ✅ W0 | ⬜ pending |
| 02-02-03 | 02 | 1 | PAGES-03 | manual | Verify tratamiento page template renders | ✅ W0 | ⬜ pending |
| 02-02-04 | 02 | 1 | PAGES-04 | manual | Verify club-vida-y-salud page template renders | ✅ W0 | ⬜ pending |
| 02-02-05 | 02 | 1 | PAGES-05 | manual | Verify contacto page template renders | ✅ W0 | ⬜ pending |
| 02-03-01 | 03 | 2 | CPT-01, CPT-02, CPT-03 | manual | Inspect meta boxes in admin UI | ✅ W0 | ⬜ pending |

*Status: ⬜ pending · ✅ green · ❌ red · ⚠️ flaky*

---

## Wave 0 Requirements

Wave 0 infrastructure is distributed across implementation plans:

- [x] `artricenter-content.php` — Created in plan 02-01 (main plugin file with autoloader)
- [x] `includes/class-doctores.php` — Created in plan 02-01 (Doctores CPT class)
- [x] `includes/class-especialidades.php` — Created in plan 02-01 (Especialidades CPT class)
- [x] `includes/class-sucursales.php` — Created in plan 02-01 (Sucursales CPT class)
- [x] `includes/class-page-creator.php` — Created in plan 02-02 (page creation on activation)
- [x] `templates/template-homepage.php` — Created in plan 02-02 (homepage template)
- [x] `templates/template-especialidades.php` — Created in plan 02-02 (especialidades template)
- [x] `templates/template-tratamiento.php` — Created in plan 02-02 (tratamiento template)
- [x] `templates/template-club-vida-y-salud.php` — Created in plan 02-02 (club template)
- [x] `templates/template-contacto.php` — Created in plan 02-02 (contacto template)
- [x] `includes/class-meta-boxes.php` — Created in plan 02-03 (meta box registration)
- [x] `includes/class-shortcodes.php` — Created in plan 02-03 (shortcode registration)

All Wave 0 files are created as part of their respective implementation plans.

---

## Manual-Only Verifications

| Behavior | Requirement | Why Manual | Test Instructions |
|----------|-------------|------------|-------------------|
| CPT meta box display | CPT-01, CPT-02, CPT-03 | Admin UI requires visual inspection | Create CPT entry, verify meta boxes render with grouped fields |
| Media uploader for images | CPT-01 | Interactive media modal | Upload doctor photo, verify media uploader works |
| Page template assignment | PAGES-01 through PAGES-05 | Admin dropdown selection | Edit page, verify template option available in Page Attributes |
| Shortcode output rendering | PAGES-01 | Visual content inspection | Add shortcode to page, verify output displays correctly |
| CPT archive listing | CPT-01, CPT-02, CPT-03 | Content listing verification | Visit archive pages, verify entries display |
| Homepage section layout | PAGES-01 | Visual design verification | Visit homepage, verify all sections render correctly |
| Meta box security | CPT-01, CPT-02, CPT-03 | Security behavior testing | Test nonce verification, sanitize/escape on save |
| Page creation on activation | PAGES-01 through PAGES-05 | Plugin activation behavior | Deactivate/reactivate plugin, verify pages created once |

---

## Validation Sign-Off

- [x] All tasks have verification method (automated or manual)
- [x] Sampling continuity: no 3 consecutive tasks without verification
- [x] Wave 0 covers all infrastructure setup
- [x] Feedback latency < 60s
- [x] `nyquist_compliant: true` set in frontmatter

**Approval:** approved 2026-03-19
