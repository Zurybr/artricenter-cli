# Roadmap: Artricenter WordPress Migration

## Overview

Migrate the existing Artricenter static site (Astro + Tailwind CSS) to WordPress using a modular plugin architecture. The journey begins with establishing a Docker development environment and foundation structure plugin, then builds content management capabilities through Custom Post Types, adds interactive features (contact forms, sticky buttons), applies visual polish matching the current site design, implements SEO metadata, and concludes with production deployment via wp-admin.

## Phases

**Phase Numbering:**
- Integer phases (1, 2, 3): Planned milestone work
- Decimal phases (2.1, 2.2): Urgent insertions (marked with INSERTED)

Decimal phases appear between their surrounding integers in numeric order.

- [x] **Phase 1: Foundation** - Docker environment and structure plugin with header, footer, navigation, and global CSS
- [ ] **Phase 2: Content Engine** - Custom Post Types and content pages for doctors, specialties, and locations
- [ ] **Phase 3: Interactive Features** - Contact forms with WhatsApp integration and sticky buttons
- [ ] **Phase 4: Visual Polish** - Visual design parity and responsive refinements
- [ ] **Phase 5: SEO & Deployment** - Metadata implementation and production deployment

## Phase Details

### Phase 1: Foundation ✅
**Goal**: Establish Docker development environment and structure plugin providing site-wide layout, navigation, and global CSS foundation
**Depends on**: Nothing (first phase)
**Requirements**: DOCKER-01, DOCKER-02, DOCKER-03, STRUCT-01, STRUCT-02, STRUCT-03, STRUCT-04, STRUCT-05
**Success Criteria** (what must be TRUE):
  1. Developer can run WordPress 6.9.4 locally at http://localhost with hot-reload for plugin development
  2. Site displays header with logo and responsive navigation (mobile overlay + desktop dropdowns) on all pages
  3. Site displays footer with 3 sucursales cards (La Raza, Atizapán, Viaducto) including addresses, phone, and Google Maps links
  4. All pages use global CSS with `.artricenter-` namespace prefix to avoid conflicts with WordPress themes
  5. Smooth scroll navigation works for in-page anchors (e.g., #artricenter, #nuestra-historia)

**Plans**: 5 plans in 3 waves

Plans:
- [x] 01-01: Set up Docker Compose environment with WordPress 6.9.4, PHP 8.2, MySQL 8.0, and WP-CLI
- [x] 01-02: Create structure plugin with header component (logo, responsive navigation)
- [x] 01-03: Create structure plugin with footer component (3 sucursales cards with addresses and maps)
- [x] 01-04: Convert Tailwind CSS to pure CSS with `.artricenter-` namespace prefix and implement smooth scroll navigation
- [x] 01-05: Register WordPress hooks (`artricenter_before_content`, `artricenter_after_content`) for plugin integration

### Phase 2: Content Engine
**Goal**: Enable content management through Custom Post Types for doctors, specialties, and locations with custom templates
**Depends on**: Phase 1
**Requirements**: CPT-01, CPT-02, CPT-03, CPT-04, PAGES-01, PAGES-02, PAGES-03, PAGES-04, PAGES-05
**Success Criteria** (what must be TRUE):
  1. WordPress admin can create and manage Doctores entries with name, specialty, photo, social media links, and location
  2. WordPress admin can create and manage Especialidades entries with name, description, and icon/image
  3. WordPress admin can create and manage Sucursales entries with name, address, phone, Google Maps link, and color scheme
  4. Homepage displays Artricenter intro, Nuestra Historia, Nuestros Médicos (3 doctors grid), and Misión/Visión/Valores sections
  5. Especialidades, Tratamiento Médico Integral, Club de Vida y Salud, and Contacto pages display content correctly
**Plans**: 7 plans in 3 waves

Plans:
- [ ] 02-01: Create Doctores Custom Post Type with meta boxes for name, specialty, photo, social links, and location
- [ ] 02-02: Create Especialidades Custom Post Type with meta boxes for name, description, and icon/image
- [ ] 02-03: Create Sucursales Custom Post Type with meta boxes for name, address, phone, maps link, and color scheme
- [ ] 02-04: Create homepage template with Artricenter, Nuestra Historia, Nuestros Médicos, and Misión/Visión/Valores sections
- [ ] 02-05: Create Especialidades, Tratamiento Médico Integral, Club de Vida y Salud, and Contacto page templates with shortcode integration
- [ ] 02-06: Recreate missing shortcodes for doctors grid, mission cards, and specialties list
- [ ] 02-07: Update REQUIREMENTS.md to mark CPT-03 and CPT-04 as complete


### Phase 3: Interactive Features
**Goal**: Implement contact forms with WhatsApp integration and sticky conversion buttons
**Depends on**: Phase 2
**Requirements**: FORM-01, FORM-02, FORM-03, STICKY-01, STICKY-02, STICKY-03
**Success Criteria** (what must be TRUE):
  1. User can submit contact form and WhatsApp opens with message pre-populated with form data
  2. Contact form validates required fields and displays inline errors when submission fails
  3. Contact form uses nonce verification, input sanitization, and output escaping to prevent XSS attacks
  4. Sticky WhatsApp button displays fixed at bottom-right on desktop/tablet with green background and WhatsApp icon
  5. Sticky Dra. Edith button displays fixed at bottom-left on desktop/tablet with doctor photo, name, and "Pregúntale a la Dra. Edith" text
**Plans**: TBD

Plans:
- [ ] 03-01: Create contact form with nonce verification, input sanitization, and output escaping
- [ ] 03-02: Implement inline form validation with error messages for required/invalid fields
- [ ] 03-03: Integrate WhatsApp API to pre-populate message with form data on submission
- [ ] 03-04: Create sticky WhatsApp button component fixed at bottom-right (desktop/tablet only)
- [ ] 03-05: Create sticky Dra. Edith button component fixed at bottom-left (desktop/tablet only)

### Phase 4: Visual Polish
**Goal**: Achieve visual parity with current Astro site and ensure responsive design across all breakpoints
**Depends on**: Phase 3
**Requirements**: VISUAL-01, VISUAL-02, VISUAL-03, VISUAL-04, VISUAL-05
**Success Criteria** (what must be TRUE):
  1. Site maintains visual parity with current Astro site (colors, typography, spacing, layout) across all pages
  2. Responsive design works correctly on mobile, tablet, and desktop breakpoints matching current site
  3. Doctor profile cards display as 3-column grid on desktop and single column on mobile with hover effects
  4. Mission/Vision/Values cards display as 3-column grid with gradient backgrounds and icon circles
  5. Section titles use blue color with animated dot separators matching current design
**Plans**: TBD

Plans:
- [ ] 04-01: Audit current Astro site design and create visual parity checklist (colors, typography, spacing)
- [ ] 04-02: Implement responsive breakpoints matching current site (mobile, tablet, desktop)
- [ ] 04-03: Style doctor profile cards as 3-column grid (desktop) and single column (mobile) with hover effects
- [ ] 04-04: Style Mission/Vision/Values cards as 3-column grid with gradient backgrounds and icon circles
- [ ] 04-05: Implement section title styling with blue color and animated dot separators

### Phase 5: SEO & Deployment
**Goal**: Implement SEO metadata and deploy plugins to production WordPress via wp-admin
**Depends on**: Phase 4
**Requirements**: SEO-01, SEO-02, SEO-03, SEO-04, DEPLOY-01, DEPLOY-02, DEPLOY-03
**Success Criteria** (what must be TRUE):
  1. All pages include Open Graph meta tags (og:title, og:description, og:image, og:url) for social media sharing
  2. All pages include Twitter card meta tags for Twitter sharing
  3. Homepage includes MedicalClinic structured data (schema.org) for search engines
  4. All images include alt text for accessibility
  5. Plugins deploy to production WordPress via wp-admin ZIP upload without PHP fatal errors or warnings
**Plans**: TBD

Plans:
- [ ] 05-01: Implement Open Graph meta tags on all pages for social media sharing
- [ ] 05-02: Implement Twitter card meta tags on all pages for Twitter sharing
- [ ] 05-03: Add MedicalClinic structured data (schema.org) to homepage
- [ ] 05-04: Audit all images and add alt text for accessibility
- [ ] 05-05: Package plugins as ZIP files with correct directory structure for wp-admin upload
- [ ] 05-06: Deploy plugins to staging WordPress environment and verify no PHP errors
- [ ] 05-07: Deploy plugins to production WordPress environment via wp-admin

## Progress

**Execution Order:**
Phases execute in numeric order: 1 → 2 → 3 → 4 → 5

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 1. Foundation | 5/5 | Complete | 2026-03-19 |
| 2. Content Engine | 0/5 | Ready to execute | - |
| 3. Interactive Features | 0/5 | Not started | - |
| 4. Visual Polish | 0/5 | Not started | - |
| 5. SEO & Deployment | 0/7 | Not started | - |
