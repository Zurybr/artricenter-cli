---
phase: 1
slug: foundation
status: draft
nyquist_compliant: false
wave_0_complete: false
created: 2026-03-19
---

# Phase 1 — Validation Strategy

> Per-phase validation contract for feedback sampling during execution.

---

## Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | WordPress PHPUnit (integration tests) + Manual visual verification |
| **Config file** | `wp-content/plugins/artricenter-structure/tests/bootstrap.php` |
| **Quick run command** | `docker compose exec wordpress wp plugin test --dependencies` |
| **Full suite command** | `docker compose exec wordpress wp plugin test` |
| **Estimated runtime** | ~30 seconds (Docker startup adds ~5s first run) |

---

## Sampling Rate

- **After every task commit:** Run visual verification (inspect in browser)
- **After every plan wave:** Run `docker compose ps` + browser verification of all components
- **Before `/gsd:verify-work`:** All 5 success criteria must be verified
- **Max feedback latency:** 60 seconds

---

## Per-Task Verification Map

| Task ID | Plan | Wave | Requirement | Test Type | Automated Command | File Exists | Status |
|---------|------|------|-------------|-----------|-------------------|-------------|--------|
| 01-01-01 | 01 | 1 | DOCKER-01 | integration | `docker compose ps` | ✅ W0 | ⬜ pending |
| 01-01-02 | 01 | 1 | DOCKER-02 | integration | `curl -f http://localhost:8080/` | ✅ W0 | ⬜ pending |
| 01-01-03 | 01 | 1 | DOCKER-03 | integration | `docker compose exec wp --version` | ✅ W0 | ⬜ pending |
| 01-02-01 | 01 | 1 | STRUCT-01 | visual | Browser inspect + template tag test | ✅ W0 | ⬜ pending |
| 01-03-01 | 01 | 1 | STRUCT-02 | visual | Browser inspect footer cards | ✅ W0 | ⬜ pending |
| 01-04-01 | 01 | 1 | STRUCT-03 | manual | Inspect CSS with DevTools | ✅ W0 | ⬜ pending |
| 01-04-02 | 01 | 1 | STRUCT-04 | manual | Test anchor links in browser | ✅ W0 | ⬜ pending |
| 01-05-01 | 01 | 1 | STRUCT-05 | integration | `do_action()` test in WP test suite | ✅ W0 | ⬜ pending |

*Status: ⬜ pending · ✅ green · ❌ red · ⚠️ flaky*

---

## Wave 0 Requirements

- [ ] `docker-compose.yml` — Docker environment configuration
- [ ] `wp-content/plugins/artricenter-structure/artricenter-structure.php` — Main plugin file with header
- [ ] `wp-content/plugins/artricenter-structure/includes/class-header.php` — Header component class
- [ ] `wp-content/plugins/artricenter-structure/includes/class-footer.php` — Footer component class
- [ ] `wp-content/plugins/artricenter-structure/assets/css/main.css` — Converted CSS file
- [ ] `wp-content/plugins/artricenter-structure/tests/bootstrap.php` — WP test bootstrap

---

## Manual-Only Verifications

| Behavior | Requirement | Why Manual | Test Instructions |
|----------|-------------|------------|-------------------|
| Docker environment startup | DOCKER-01, DOCKER-02 | Docker requires manual first-time setup | Run `docker compose up -d`, verify http://localhost:8080 loads WordPress |
| Visual component rendering | STRUCT-01, STRUCT-02 | Visual design requires human judgment | Open site in browser, verify header matches Astro site layout |
| CSS namespace correctness | STRUCT-03 | Visual inspection needed to verify no conflicts | Use DevTools to inspect all CSS classes have `.artricenter-` prefix |
| Smooth scroll behavior | STRUCT-04 | Interactive behavior requires manual testing | Click anchor links (e.g., `#artricenter`), verify smooth scroll with 5rem padding |
| Mobile menu interaction | STRUCT-01 | Touch gestures and animations need manual testing | On mobile viewport, tap hamburger menu, verify slide-in animation works |
| Footer card links | STRUCT-02 | External Google Maps links need manual verification | Click "Ver en Google Maps" buttons, verify they open correct location |
| Hook registration | STRUCT-05 | WordPress hooks fire in context, need integration test | Use `add_action('artricenter_before_content', function() { echo 'test'; });` in theme, verify output appears |

---

## Validation Sign-Off

- [ ] All tasks have verification method (automated or manual)
- [ ] Sampling continuity: no 3 consecutive tasks without verification
- [ ] Wave 0 covers all infrastructure setup
- [ ] Feedback latency < 60s
- [ ] `nyquist_compliant: true` set in frontmatter

**Approval:** pending
