# Project Research Summary

**Project:** Artricenter WordPress Migration
**Domain:** WordPress Plugin Development (Static Site to WordPress Migration)
**Researched:** 2026-03-19
**Confidence:** HIGH

## Executive Summary

This is a WordPress migration project converting a static Astro medical clinic website to a modular WordPress plugin architecture. The current site is a fully-featured static site for Artricenter (a rheumatology clinic with multiple locations) built with Astro, Tailwind CSS, and vanilla JavaScript. The migration requires converting Tailwind CSS to pure CSS, implementing Custom Post Types for doctors/specialties/locations, and creating interactive features (contact forms, sticky buttons) while maintaining visual parity and responsiveness.

The recommended approach is a **modular plugin architecture** where each major feature (structure, doctors, specialties, locations, forms) lives in a separate WordPress plugin. This enables independent maintenance, updates, and debugging. Development should happen in a Docker-based local WordPress environment (WordPress 6.9.4 with PHP 8.2 and MySQL 8.0), with manual deployment via wp-admin ZIP upload (no SSH access available). The build sequence must follow dependency order: structure plugin first (provides hooks/styles), then content CPTs, then interactive features.

**Key risks and mitigation:** (1) CSS namespace collisions during Tailwind-to-CSS conversion — mitigate with strict CSS prefixing strategy and multi-theme testing; (2) Custom Post Type permalink conflicts — mitigate with unique rewrite slugs and proper rewrite rule flushing; (3) Form security vulnerabilities — mitigate with nonce verification, input sanitization, and output escaping; (4) wp-admin deployment failures — mitigate with proper ZIP creation process and staging environment testing.

## Key Findings

### Recommended Stack

**WordPress 6.9.4 with PHP 8.2** — Current stable release with official PHP 8.2 support, providing best stability/feature balance. **Docker Compose** for local development — official WordPress Docker image (wordpress:6.9.4-php8.2-apache) with MySQL 8.0, enabling volume-mounted plugin development with hot-reload. **Pure CSS** (not Tailwind) — project constraint requiring conversion of existing Tailwind utilities to semantic CSS with namespacing (`.artricenter-` prefix) to avoid conflicts with themes/plugins.

**Supporting tools:** PHPUnit 9.x/10.x with WordPress polyfills for testing; PHP CodeSniffer with WordPress Coding Standards for linting; WP-CLI for efficient WordPress management; Query Monitor plugin for debugging queries/hooks/performance.

### Expected Features

**Must have (table stakes):**
- Responsive design preservation — current site is fully responsive; WordPress version must match mobile experience
- Content structure migration — all pages (index, especialidades, tratamiento, club-vida, contacto) with same content
- Navigation preservation — multi-level navigation with dropdowns, mobile overlay menu
- Custom Post Types — doctores, especialidades, sucursales enabling non-technical content management
- Contact form functionality — submits to WhatsApp, working form validation
- Footer with location cards — 3 sucursales with addresses, phone, Google Maps links
- Sticky buttons (WhatsApp, Dra. Edith) — prominent CTAs critical for business conversion

**Should have (competitive):**
- Modular plugin architecture — each major feature in separate plugin for easier maintenance
- Doctor profiles with social links — grid layout with photos, specialties, social media
- Testimonial carousel — patient testimonials with photos, star ratings, verified badges
- Animated UI elements — subtle animations (pulse effects, hover transitions, gradient borders)
- Multi-sucursal color schemes — different colors per location (La Raza=blue, Atizapán=green, Viaducto=orange)

**Defer (v2+):**
- Automated content migration — out of scope; manual entry ensures quality
- Real-time appointment booking — forms sufficient initially
- Blog integration — external blog exists, link only needed
- Advanced caching/CDN — overkill for single-site installation

### Architecture Approach

**Hook-based modular plugin architecture** where functionality is injected through WordPress actions and filters. Structure plugin (artricenter-structure) provides foundation — site-wide layout, navigation, global styles, and hooks (`artricenter_before_content`, `artricenter_after_content`) that other plugins use. Content plugins (artricenter-doctors, artricenter-specialties, artricenter-locations) each own their CPT registration, meta boxes, and custom templates. Interactive plugins (artricenter-forms) handle user input, AJAX handlers, and sticky buttons. All plugins communicate via hooks/filters (not direct calls), maintaining loose coupling.

**Major components:**
1. **Structure Plugin** — site-wide layout (header, footer, navigation), global CSS, hook registration for other plugins
2. **CPT Plugins** — Custom Post Type definitions (doctors, specialties, locations), meta boxes, template overrides
3. **Forms Plugin** — contact forms, AJAX submission, sticky buttons (WhatsApp, Dra. Edith), admin settings
4. **Theme Layer** — visual presentation, PHP templates that call plugin template tags and shortcodes

### Critical Pitfalls

1. **CSS Namespace Collisions** — Tailwind utility classes (`.flex`, `.text-center`, `.p-4`) conflict with WordPress theme styles. Mitigation: Prefix ALL CSS with `.artricenter-` namespace, wrap output in container div with unique ID, test with multiple themes.

2. **Custom Post Type Permalink Conflicts** — CPT slugs conflict with page slugs causing 404 errors. Mitigation: Use unique rewrite slugs (e.g., `doctor-artricenter`), register CPTs on `init` hook with priority 0, flush rewrite rules in activation hook, test with both pretty/default permalinks.

3. **Form Security Vulnerabilities** — Missing nonce verification, input sanitization, output escaping leads to XSS/header injection. Mitigation: Always include `wp_nonce_field()`, sanitize all input with `sanitize_text_field()`, escape all output with `esc_html()`, use `wp_mail()` instead of `mail()`, implement rate limiting with Transients API.

4. **Plugin Activation Dependencies** — Plugins activate but fail silently when required features missing. Mitigation: Implement activation checks with `register_activation_hook()`, verify theme supports (post-thumbnails), check rewrite rules flush, add admin notices for missing dependencies.

5. **wp-admin Deployment Failures** — Plugin works in Docker but fails in production due to file permissions, missing files in ZIP, path differences. Mitigation: Create deployment checklist, use `plugin_dir_path()` and `plugins_url()` instead of absolute paths, test deployment in staging environment.

## Implications for Roadmap

Based on research, suggested phase structure:

### Phase 1: Foundation & Docker Setup
**Rationale:** Must establish development environment and foundation plugin first — all other plugins depend on structure plugin's hooks, styles, and navigation. Addresses pitfall of CSS namespace collisions by establishing naming conventions early.

**Delivers:** Docker WordPress environment (WordPress 6.9.4, PHP 8.2, MySQL 8.0), structure plugin with header/footer/navigation, CSS namespace convention (`.artricenter-` prefix), basic page templates (blank with header/footer).

**Addresses:** Responsive design preservation, navigation preservation, professional header with logo, footer with location cards, smooth scroll navigation.

**Avoids:** CSS namespace collisions, plugin activation dependency issues.

### Phase 2: Content CPTs (Doctors, Specialties, Locations)
**Rationale:** Independent content plugins that can be built in parallel once structure plugin exists. These are core to the CMS functionality — enabling non-technical staff to manage doctors, specialties, and locations. Addresses CPT permalink conflicts with unique rewrite slugs.

**Delivers:** Three Custom Post Types (doctores, especialidades, sucursales), meta boxes for custom fields, custom templates (single-{cpt}.php, archive-{cpt}.php), admin interface for content management.

**Addresses:** Custom Post Types for non-technical content management, doctor profiles with social links, multi-sucursal management, PAIPER program content, Mission/Vision/Values cards.

**Uses:** WordPress CPT APIs (`register_post_type()`), custom meta boxes, template hierarchy overrides.

**Implements:** Single-responsibility plugin pattern, template hierarchy override pattern.

### Phase 3: Interactive Features & Forms
**Rationale:** Depends on structure plugin for placement and CPT plugins for data (e.g., contact form for specific doctor). Critical for business conversion (WhatsApp contact). Addresses form security vulnerabilities with proper nonce/sanitization/escaping.

**Delivers:** Contact form with WhatsApp integration, AJAX form submission, sticky buttons (WhatsApp, Dra. Edith), form validation, admin settings page for configuration.

**Addresses:** Contact form functionality, sticky buttons (WhatsApp, Dra. Edith), form security, conversion optimization.

**Avoids:** Form security vulnerabilities, XSS attacks, header injection.

**Implements:** Hook-based integration pattern, AJAX handler pattern, WordPress Settings API.

### Phase 4: Visual Polish & Animations
**Rationale:** Deferred after core functionality works. Animations and polish can iterate post-launch without affecting business operations. Less critical than functional requirements.

**Delivers:** Animated UI elements (pulse effects, hover transitions, gradient borders), testimonial carousel, responsive design refinements, CSS optimizations, multi-sucursal color schemes.

**Addresses:** Animated UI elements, testimonial carousel, responsive design refinements, multi-sucursal color schemes.

**Uses:** CSS animations, transitions, keyframes, custom JavaScript for carousels.

### Phase 5: Content Migration & SEO
**Rationale:** Manual content entry after plugins are functional. Staff reviews and enters content ensuring quality. SEO metadata transfer requires templates to be working first.

**Delivers:** Manual content entry for all pages (index, especialidades, tratamiento, club-vida, contacto), SEO metadata (Open Graph, Twitter cards, structured data), image optimization, sitemap generation.

**Addresses:** Content structure migration, SEO metadata transfer, image optimization, performance optimization.

**Uses:** WordPress SEO capabilities, Media Library, structured data (MedicalClinic schema).

### Phase 6: Testing & Deployment
**Rationale:** Final testing across browsers, devices, and themes. wp-admin deployment with proper ZIP creation and staging environment testing. Addresses wp-admin deployment failures.

**Delivers:** Cross-browser testing (Chrome, Firefox, Safari, Edge), mobile device testing, multi-theme compatibility testing, deployment runbook, staging environment verification, production deployment via wp-admin.

**Addresses:** wp-admin deployment pitfalls, cross-browser compatibility, mobile UX issues, asset loading problems.

**Avoids:** wp-admin deployment failures, asset loading issues, mobile UX pitfalls.

### Phase Ordering Rationale

**Dependency-driven order:** Structure plugin must exist first because it provides hooks and styles all other plugins use. Content CPTs come next because they're independent of each other but need structure plugin's foundation. Interactive features depend on both structure (for placement) and CPTs (for data). Visual polish defers because it doesn't block launch. Content migration happens last to avoid re-work if templates change. Testing/deployment is final phase.

**Grouping based on architecture:** Phase 1 establishes the architectural foundation (structure plugin). Phase 2 builds content layer (CPT plugins). Phase 3 adds interaction layer (forms plugin). Phase 4 adds polish layer (animations). Phase 5 populates content (manual entry). Phase 6 validates and deploys (testing).

**Pitfall avoidance:** CSS namespace collisions addressed in Phase 1 by establishing naming conventions before writing CSS. CPT permalink conflicts addressed in Phase 2 with unique rewrite slugs and testing. Form security addressed in Phase 3 with secure form template. wp-admin deployment failures addressed in Phase 6 with staging testing and deployment runbook.

### Research Flags

**Phases likely needing deeper research during planning:**

- **Phase 2 (Content CPTs):** Tailwind-to-CSS conversion complexity — need to map all utility classes to semantic CSS. Research specific Tailwind classes used in current Astro site to ensure exact visual parity.

- **Phase 3 (Interactive Features):** WhatsApp form integration — dynamic message construction, URL encoding, phone number formatting. May need to research WhatsApp URL API specifics.

- **Phase 4 (Visual Polish):** Testimonial carousel implementation — research WordPress carousel plugins vs custom JavaScript implementation, accessibility considerations.

**Phases with standard patterns (skip research-phase):**

- **Phase 1 (Foundation):** Docker WordPress setup is well-documented. Structure plugin follows standard WordPress plugin patterns. Official documentation is comprehensive.

- **Phase 5 (Content Migration):** Manual WordPress content entry is standard practice. SEO plugins (Yoast, RankMath) have established patterns for metadata.

- **Phase 6 (Testing & Deployment):** WordPress testing and deployment are standard practices. wp-admin deployment is straightforward.

## Confidence Assessment

| Area | Confidence | Notes |
|------|------------|-------|
| Stack | HIGH | Official WordPress documentation, Docker Hub official images, verified version compatibility (WordPress 6.9.4 + PHP 8.2). |
| Features | MEDIUM | Based on detailed codebase analysis of existing Astro site. Web search unavailable during research — specific plugin recommendations for CPTs/forms not verified against 2026 ecosystem. |
| Architecture | HIGH | Official WordPress Plugin Handbook documentation. Hook-based architecture is well-established pattern. Modular plugin approach is WordPress best practice. |
| Pitfalls | MEDIUM | WordPress security best practices are HIGH confidence (official docs). Tailwind-to-CSS conversion pitfalls are MEDIUM (specific migration patterns not well-documented, inferred from utility-first CSS principles). |

**Overall confidence:** HIGH

Stack and architecture research is grounded in official WordPress documentation. Feature research is solid based on existing codebase analysis. Pitfall research is comprehensive with HIGH confidence on security/WordPress-specific issues, MEDIUM on CSS migration specifics. No critical gaps that would block roadmap creation.

### Gaps to Address

**Low-priority gaps (handle during implementation):**

- **Tailwind-to-CSS conversion specifics:** Exact utility class mapping needs to be done during implementation. Can reference current Astro site's `tailwind.config.mjs` and component usage. Mitigation: Create CSS conversion checklist, test visual parity in browser dev tools.

- **Contact form plugin selection:** Need to verify Contact Form 7 or WPForms capabilities for WhatsApp integration during Phase 3 planning. Mitigation: Test plugin options in Docker environment, fallback to custom implementation if needed.

- **Production hosting environment specifics:** Need to check actual production WordPress configuration (PHP version, memory limits, upload limits) during Phase 6 planning. Mitigation: Ask hosting provider for specifications, test in staging environment.

**No critical gaps:** All foundational questions answered. Stack, architecture, and core pitfalls are well-researched. Remaining gaps are implementation details that can be resolved during phase planning.

## Sources

### Primary (HIGH confidence)
- **Docker Hub Official WordPress Image** — Verified current WordPress versions (6.9.4), PHP variants (8.2, 8.3, 8.4, 8.5), official Docker Compose setup example. https://hub.docker.com/_/wordpress
- **WordPress Plugin Developer Handbook** — Official plugin development documentation covering security, hooks, APIs, and best practices. https://developer.wordpress.org/plugins/
- **WordPress Core PHPUnit Testing Documentation** — Official guide for PHPUnit testing with WordPress, including setup workflows and polyfill requirements. https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/
- **Plugin Basics – WordPress Developer Resources** — Official documentation on plugin structure, hooks, activation/deactivation. (Last updated: December 14, 2023). https://developer.wordpress.org/plugins/plugin-basics/
- **Hooks – Plugin Handbook** — Official documentation on actions and filters. (Last updated: December 14, 2023). https://developer.wordpress.org/plugins/hooks/
- **Shortcodes – Plugin Handbook** — Official documentation on shortcode development. (Last updated: December 14, 2023). https://developer.wordpress.org/plugins/shortcodes/
- **Metadata – Plugin Handbook** — Official documentation on post metadata and custom fields. (Last updated: December 14, 2023). https://developer.wordpress.org/plugins/metadata/

### Secondary (MEDIUM confidence)
- **Current Artricenter Codebase Analysis** — Detailed analysis of existing Astro site structure, components, and features. HIGH confidence for existing implementation, MEDIUM for WordPress migration patterns (inferred from best practices).
- **WordPress CPT Development Patterns** — Based on established WordPress Custom Post Type registration patterns and rewrite rule handling.
- **WordPress Security Best Practices** — Based on established patterns for nonces, sanitization, escaping, and AJAX security.
- **Tailwind CSS to Pure CSS Conversion** — Based on utility-first CSS framework migration principles. Specific WordPress-Tailwind integration patterns not well-documented.

### Tertiary (LOW confidence)
- **Contact Form Plugin Capabilities (2026)** — Could not verify current plugin ecosystem trends. Specific plugin recommendations (Contact Form 7, WPForms) need validation during Phase 3 planning.
- **WordPress Page Builder vs Custom Development** — Could not research 2026 landscape for Elementor, Divi, etc. Custom development approach recommended based on project requirements.
- **Medical Clinic Website Examples** — Could not search for similar WordPress migrations. Feature set based on existing Astro site requirements and general medical clinic needs.

---
*Research completed: 2026-03-19*
*Ready for roadmap: yes*
