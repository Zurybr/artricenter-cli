# Feature Landscape

**Domain:** WordPress static site migration (medical clinic website)
**Researched:** 2026-03-19
**Confidence:** MEDIUM (Web search unavailable, relying on codebase analysis and WordPress migration best practices)

## Table Stakes

Features users expect in WordPress migration projects. Missing = migration feels incomplete or broken.

| Feature | Why Expected | Complexity | Notes |
|---------|--------------|------------|-------|
| **Responsive design preservation** | Current Astro site is fully responsive; WordPress version must match mobile experience | High | Requires converting Tailwind breakpoints to CSS media queries, testing on multiple devices |
| **Content structure migration** | All pages (index, especialidades, tratamiento, club-vida, contacto) must exist with same content | Medium | Manual content entry is acceptable, but structure must support all current sections |
| **Navigation preservation** | Multi-level navigation with dropdowns is core to current UX | Medium | Mobile overlay menu + desktop navigation must be replicated |
| **SEO metadata transfer** | Current site has Open Graph, Twitter cards, structured data; WordPress must maintain | Low | Standard WordPress SEO capabilities, but need to replicate MedicalClinic schema |
| **Image optimization** | Current site uses optimized .avif images; WordPress must handle efficiently | Medium | WordPress media library with proper alt text and responsive images |
| **Sticky buttons (WhatsApp, Dra. Edith)** | Prominent CTAs are critical for business (contact conversion) | Medium | Custom implementation needed, not standard WordPress feature |
| **Contact form functionality** | Current form submits to WhatsApp; WordPress version must work | Medium | Can use contact form plugins or custom implementation |
| **Footer with location cards** | 3 sucursales with addresses, phone, Google Maps links | Low | Static footer component, data can be hardcoded or dynamic |
| **Professional header with logo** | Brand identity element on all pages | Low | Standard WordPress theming capability |
| **Smooth scroll navigation** | Current site uses in-page anchors (e.g., #artricenter, #nuestra-historia) | Low | Simple JS implementation needed |
| **Performance optimization** | Static site is fast; WordPress must not feel sluggish | Medium | Caching, minification, optimized queries needed |

## Differentiators

Features that set this migration apart from typical WordPress sites. Not expected, but valued.

| Feature | Value Proposition | Complexity | Notes |
|---------|-------------------|------------|-------|
| **Modular plugin architecture** | Each major feature in separate plugin = easier maintenance, updates, debugging | High | Non-standard approach; most migrations use single theme, but modularity pays off long-term |
| **Custom Post Types (CPTs)** | CPT for doctores, especialidades, sucursales enables non-technical content management | High | Must register CPTs, create admin UI, build custom templates |
| **Doctor profiles with social links** | Grid layout with doctor photos, specialties, social media (Facebook, Twitter, LinkedIn) | Medium | Custom ACF fields or CPT with meta boxes |
| **Testimonial carousel** | Patient testimonials with photos, star ratings, verified patient badges | Medium | Custom JS carousel or integration with slider plugin |
| **Animated UI elements** | Subtle animations (pulse effects, hover transitions, gradient borders) create premium feel | Medium | CSS animations, transitions, keyframes |
| **Blog integration (external)** | Links to external WordPress blog at artricenter.com.mx/blog/ | Low | Simple outbound link, not full blog implementation |
| **Multi-sucursal management** | Different color schemes per location (La Raza=blue, Atizapán=green, Viaducto=orange) | Medium | Custom logic for location-specific styling |
| **Sticky WhatsApp button with badge** | Always-visible contact with notification badge ("1") increases conversion | Low | Custom CSS positioning, simple HTML |
| **Dra. Edith personalized button** | Direct WhatsApp contact to specific doctor with photo and animation | Medium | Custom animated element, creates personal connection |
| **PAIPER program content** | Structured treatment information with numbered steps | Low | Static content, but needs careful layout replication |
| **Mission/Vision/Values cards** | Three-card layout with icons, gradient backgrounds, hover effects | Medium | Custom CSS for card styling and responsiveness |
| **Spanish-language medical content** | All content in Spanish, medical terminology (enfermedades reumáticas) | Low | Content translation not needed, but WordPress admin must support Spanish characters |

## Anti-Features

Features to explicitly NOT build.

| Anti-Feature | Why Avoid | What to Do Instead |
|--------------|-----------|-------------------|
| **Tailwind CSS in WordPress** | Project constraint: convert to pure CSS for better compatibility and performance | Convert existing Tailwind classes to semantic CSS with BEM or utility classes |
| **Automated content migration** | Out of scope; manual entry ensures content quality review | Manual content entry by staff after plugins are functional |
| **E-commerce functionality** | Not needed for medical clinic (no online payments/product sales) | Focus on contact forms and WhatsApp for appointments |
| **Blog within main site** | External blog already exists at artricenter.com.mx/blog/ | Link to external blog, don't build blog features |
| **Real-time appointment booking** | Out of scope initially; forms are sufficient | Simple contact form that sends to WhatsApp is adequate |
| **Multi-language support** | All content is Spanish-only; i18n adds unnecessary complexity | Single-language WordPress installation |
| **User authentication/portal** | Not needed for public-facing clinic site | No login functionality required |
| **Social media feed integration** | Current site has static social links only; feeds add maintenance burden | Keep static outbound links only |
| **Advanced caching/CDN** | Overkill for single-site WordPress installation | Standard WordPress caching plugins sufficient |
| **Custom WordPress theme from scratch** | Time-consuming; can build with child theme or custom page templates | Use starter theme or build custom page templates within plugin structure |

## Feature Dependencies

```
Docker local environment → All development and testing
    ↓
Responsive header/navigation → All page layouts
    ↓
Custom Post Types (doctores, especialidades) → Index page content
    ↓
Footer component → All pages
    ↓
Sticky buttons (WhatsApp, Dra. Edith) → All pages (conversion)
    ↓
Contact form → Contact page functionality
    ↓
Individual page templates → Content migration
```

**Critical path:**
1. Header/Footer structure must exist before any page content
2. CPTs must be registered before doctor/especialidad content can be added
3. Responsive design must be verified early (mobile traffic is significant)
4. Contact forms must work before site goes live (business requirement)

**Independent features:**
- Blog link (outbound only)
- Social media links
- Testimonial carousel (can be added after launch)
- Animations/polish (can be iterated on)

## MVP Recommendation

**Phase 1 - Foundation (Must Launch)**
1. Docker WordPress environment
2. Header with responsive navigation (mobile overlay + desktop)
3. Footer with 3 sucursales, contact info, social links
4. Basic page templates (blank, with header/footer)
5. Homepage structure (Artricenter, Historia, Médicos, Misión/Visión/Valores sections)

**Phase 2 - Core Content (Must Launch)**
1. Custom Post Type: Doctores (with photos, specialties, social links)
2. Custom Post Type: Especialidades (artrosis, artritis, fibromialgia, etc.)
3. Custom Post Type: Sucursales (La Raza, Atizapán, Viaducto, Zaragoza)
4. Pages: Especialidades, Tratamiento Médico Integral, Club Vida y Salud, Contacto
5. Contact form with WhatsApp integration

**Phase 3 - Polish (Important but can iterate)**
1. Sticky buttons (WhatsApp, Dra. Edith)
2. Responsive design refinements (mobile testing, breakpoint adjustments)
3. Animations and hover effects
4. Testimonial carousel
5. Doctor profile cards with social links
6. SEO metadata (Open Graph, schema markup)

**Defer:**
- Advanced animations (can add post-launch)
- Additional form types (beyond contact form)
- Blog integration (link only is sufficient initially)

## Complexity Notes

**High complexity items requiring dedicated research:**
1. **Tailwind to CSS conversion** - Need to map all utility classes to semantic CSS
2. **Mobile navigation overlay** - JavaScript interactivity, accessibility considerations
3. **Custom Post Type registration and templates** - WordPress developer expertise needed
4. **WhatsApp form integration** - Dynamic message construction, URL encoding
5. **Sticky button positioning** - Mobile vs desktop layouts, z-index management

**Medium complexity (standard WordPress development):**
1. Footer with location cards
2. Doctor grid layouts
3. Responsive image handling
4. Page section layouts
5. Basic contact forms

**Low complexity (straightforward implementation):**
1. Static content pages
2. Social media links
3. Blog outbound link
4. Smooth scroll anchors
5. Basic SEO metadata

## Migration-Specific Considerations

**From static (Astro) to WordPress:**
- **Component architecture** → WordPress template hierarchy or plugin-based templates
- **Build-time optimization** → WordPress runtime optimization (caching, minification)
- **Static assets** → WordPress media library management
- **Navigation config** → WordPress menus (wp_nav_menu) or custom registration
- **Global CSS** → WordPress enqueue system (wp_enqueue_style)
- **Client-side JavaScript** → WordPress wp_enqueue_script, proper dependency management

**Content management shift:**
- **Developer edits code** → **Non-technical staff updates content via WordPress admin**
- **Git-based version control** → **WordPress revisions (limited)**
- **Deploy builds** → **Plugin updates via wp-admin**
- **Component reusability** → **WordPress template parts or shortcodes**

## Sources

**Confidence: MEDIUM**
- Web search tools were unavailable during research
- Findings based on:
  - Detailed codebase analysis of existing Astro site
  - WordPress migration best practices (training data)
  - Common WordPress plugin capabilities
  - Medical clinic website requirements

**Limitations:**
- Could not verify current WordPress plugin ecosystem trends (2026)
- Could not compare with similar migration projects
- Specific plugin recommendations unavailable

**Recommended validation:**
- Research WordPress CPT plugins (Custom Post Type UI, CPTOM)
- Verify contact form plugins (Contact Form 7, WPForms) for WhatsApp integration
- Investigate WordPress page builder alternatives (Elementor, Divi) vs custom development
- Confirm WordPress 6.x+ capabilities for medical clinic websites
