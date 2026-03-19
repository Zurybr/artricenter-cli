# Requirements: Artricenter WordPress Migration

**Defined:** 2026-03-19
**Core Value:** Replicar completamente el sitio actual de Artricenter en WordPress mediante plugins modulares que permitan mantenimiento de contenido por staff administrativo sin necesidad de desarrolladores, preservando la identidad visual y todas las funcionalidades actuales.

## v1 Requirements

Requirements for initial release. Each maps to roadmap phases.

### Docker & Development Environment

- [ ] **DOCKER-01**: User can run WordPress 6.9.4 + PHP 8.2 + MySQL 8.0 locally using Docker Compose
- [ ] **DOCKER-02**: User can access WordPress site at http://localhost with hot-reload for plugin development
- [ ] **DOCKER-03**: Docker environment includes WP-CLI for efficient WordPress management

### Site Structure

- [x] **STRUCT-01**: Plugin provides header component with logo and responsive navigation (mobile overlay + desktop)
- [ ] **STRUCT-02**: Plugin provides footer component with 3 sucursales cards (La Raza, Atizapán, Viaducto) including addresses, phone, and Google Maps links
- [ ] **STRUCT-03**: Plugin provides global CSS converted from Tailwind with `.artricenter-` namespace prefix to avoid conflicts
- [ ] **STRUCT-04**: Plugin provides smooth scroll navigation for in-page anchors (e.g., #artricenter, #nuestra-historia)
- [ ] **STRUCT-05**: Plugin registers WordPress hooks for other plugins to inject content (`artricenter_before_content`, `artricenter_after_content`)

### Content Pages

- [ ] **PAGES-01**: Homepage displays sections: Artricenter (intro), Nuestra Historia, Nuestros Médicos (3 doctors grid), Misión/Visión/Valores cards
- [ ] **PAGES-02**: Especialidades page lists medical specialties (artrosis, artritis reumatoide, fibromialgia, espondilitis anquilosante, reumatismos de partes blandas)
- [ ] **PAGES-03**: Tratamiento Médico Integral page describes PAIPER program with structured treatment steps
- [ ] **PAGES-04**: Club de Vida y Salud page displays membership program information
- [ ] **PAGES-05**: Contacto page displays contact form and clinic information

### Custom Post Types

- [ ] **CPT-01**: WordPress admin can manage Doctores Custom Post Type with fields: name, specialty, photo, social media links (Facebook, Twitter, LinkedIn), location/sucursal
- [ ] **CPT-02**: WordPress admin can manage Especialidades Custom Post Type with fields: name, description, icon/image
- [ ] **CPT-03**: WordPress admin can manage Sucursales Custom Post Type with fields: name, address, phone, Google Maps link, color scheme (blue/green/orange)
- [ ] **CPT-04**: Custom Post Types use unique rewrite slugs (e.g., `doctor-artricenter`) to avoid permalink conflicts with pages

### Interactive Features

- [ ] **FORM-01**: User can submit contact form via WhatsApp integration with message pre-populated with form data
- [ ] **FORM-02**: Contact form includes nonce verification, input sanitization, and output escaping to prevent XSS attacks
- [ ] **FORM-03**: User sees form validation errors inline when required fields are missing or invalid

### Sticky Elements

- [ ] **STICKY-01**: Sticky WhatsApp button displays fixed at bottom-right on all pages with green background and WhatsApp icon
- [ ] **STICKY-02**: Sticky Dra. Edith button displays fixed at bottom-left on all pages with doctor photo, name, and "Pregúntale a la Dra. Edith" text
- [ ] **STICKY-03**: Sticky buttons are hidden on mobile devices to avoid covering content (show only on desktop/tablet)

### Visual Design

- [ ] **VISUAL-01**: Site maintains visual parity with current Astro site (colors, typography, spacing, layout)
- [ ] **VISUAL-02**: Responsive design works on mobile, tablet, and desktop breakpoints matching current site
- [ ] **VISUAL-03**: Doctor profile cards display as 3-column grid on desktop, single column on mobile with hover effects
- [ ] **VISUAL-04**: Mission/Vision/Values cards display as 3-column grid with gradient backgrounds and icon circles
- [ ] **VISUAL-05**: Section titles use blue color with animated dot separators matching current design

### SEO & Metadata

- [ ] **SEO-01**: Pages include Open Graph meta tags for social media sharing (og:title, og:description, og:image, og:url)
- [ ] **SEO-02**: Pages include Twitter card meta tags for Twitter sharing
- [ ] **SEO-03**: Homepage includes MedicalClinic structured data (schema.org) for search engines
- [ ] **SEO-04**: Images include alt text for accessibility

### Deployment

- [ ] **DEPLOY-01**: Developer can package plugins as ZIP files for upload via wp-admin to production WordPress
- [ ] **DEPLOY-02**: Plugin ZIP includes all necessary files (PHP, CSS, JS) with correct directory structure
- [ ] **DEPLOY-03**: Plugin activation does not produce PHP fatal errors or warnings in production WordPress environment

## v2 Requirements

Deferred to future release. Tracked but not in current roadmap.

### Advanced Features

- **ANIM-01**: Testimonial carousel with patient photos, star ratings, and verified patient badges
- **ANIM-02**: Advanced CSS animations (pulse effects, gradient borders, hover transitions) beyond basic interactivity
- **ANIM-03**: Multi-sucursal color schemes applied dynamically based on content location

### Content Expansion

- **BLOG-01**: External blog integration at artricenter.com.mx/blog/ (currently only outbound link needed)
- **NEWS-01**: News/announcements section for clinic updates

### Booking System

- **BOOK-01**: Real-time appointment booking system with calendar integration
- **BOOK-02**: Patient portal for managing appointments and medical records

### Performance

- **PERF-01**: Advanced caching strategy with CDN integration
- **PERF-02**: Image lazy loading and WebP conversion for all images

## Out of Scope

Explicitly excluded. Documented to prevent scope creep.

| Feature | Reason |
|---------|--------|
| Automated content migration from Astro | Manual entry ensures content quality review and no data loss |
| Tailwind CSS in WordPress | Project constraint: convert to pure CSS for better compatibility and performance |
| E-commerce functionality | Medical clinic does not sell products online; appointments managed via forms/WhatsApp |
| Real-time appointment booking | Out of scope for v1; forms sufficient initially, defer to v2 |
| Multi-language support | All content is Spanish-only; i18n adds unnecessary complexity |
| User authentication/portal | Not needed for public-facing clinic site in v1 |
| Social media feed integration | Current site has static social links only; feeds add maintenance burden |
| Custom WordPress theme from scratch | Use starter theme or custom page templates within plugin structure |
| Blog within main site | External blog already exists at artricenter.com.mx/blog/ |

## Traceability

Which phases cover which requirements. Updated during roadmap creation.

| Requirement | Phase | Status |
|-------------|-------|--------|
| DOCKER-01 | Phase 1 | Pending |
| DOCKER-02 | Phase 1 | Pending |
| DOCKER-03 | Phase 1 | Pending |
| STRUCT-01 | Phase 1 | Complete |
| STRUCT-02 | Phase 1 | Pending |
| STRUCT-03 | Phase 1 | Pending |
| STRUCT-04 | Phase 1 | Pending |
| STRUCT-05 | Phase 1 | Pending |
| CPT-01 | Phase 2 | Pending |
| CPT-02 | Phase 2 | Pending |
| CPT-03 | Phase 2 | Pending |
| CPT-04 | Phase 2 | Pending |
| PAGES-01 | Phase 2 | Pending |
| PAGES-02 | Phase 2 | Pending |
| PAGES-03 | Phase 2 | Pending |
| PAGES-04 | Phase 2 | Pending |
| PAGES-05 | Phase 2 | Pending |
| FORM-01 | Phase 3 | Pending |
| FORM-02 | Phase 3 | Pending |
| FORM-03 | Phase 3 | Pending |
| STICKY-01 | Phase 3 | Pending |
| STICKY-02 | Phase 3 | Pending |
| STICKY-03 | Phase 3 | Pending |
| VISUAL-01 | Phase 4 | Pending |
| VISUAL-02 | Phase 4 | Pending |
| VISUAL-03 | Phase 4 | Pending |
| VISUAL-04 | Phase 4 | Pending |
| VISUAL-05 | Phase 4 | Pending |
| SEO-01 | Phase 5 | Pending |
| SEO-02 | Phase 5 | Pending |
| SEO-03 | Phase 5 | Pending |
| SEO-04 | Phase 5 | Pending |
| DEPLOY-01 | Phase 5 | Pending |
| DEPLOY-02 | Phase 5 | Pending |
| DEPLOY-03 | Phase 5 | Pending |

**Coverage:**
- v1 requirements: 35 total
- Mapped to phases: 35
- Unmapped: 0 ✓

---
*Requirements defined: 2026-03-19*
*Last updated: 2026-03-19 after roadmap creation*
