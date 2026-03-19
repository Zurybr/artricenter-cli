# Codebase Concerns

**Analysis Date:** 2026-03-19

## Tech Debt

**Large component files:**
- Issue: Large Astro files with mixed concerns (content + presentation + logic)
- Files: `src/pages/index.astro` (330 lines), `src/pages/especialidades.astro` (313 lines), `src/pages/club-vida-y-salud.astro` (226 lines)
- Impact: Harder to maintain, test, and reason about individual components
- Fix approach: Break down into smaller, focused components with single responsibilities

**Hardcoded content in components:**
- Issue: Content mixed with presentation logic throughout Astro pages
- Files: `src/pages/index.astro`, `src/pages/especialidades.astro`, `src/pages/club-vida-y-salud.astro`
- Impact: Content updates require editing component files, difficult for non-technical users
- Fix approach: Extract content to data/configuration files, use headless CMS or content layer

**Asset management inconsistency:**
- Issue: Mixed image formats (AVIF, JPG, PNG) without optimization strategy
- Files: `assets/` directory containing 14 image files (604K total)
- Impact: Performance implications, inconsistent loading, no responsive images
- Fix approach: Implement responsive images with srcset, modern format conversion, lazy loading

## Known Bugs

**Broken social media links:**
- Symptoms: Multiple placeholder `href="#"` links in doctor profiles
- Files: `src/pages/index.astro` (lines 159, 162, 165, 189, 192, 195, 219, 222, 225)
- Trigger: Social media links not configured
- Workaround: Links don't function, navigation fails

**Missing responsive images:**
- Symptoms: Images not optimized for different screen sizes
- Files: All pages using `<img>` tags without responsive attributes
- Trigger: Accessing site on different devices
- Workaround: Manual image selection by browser, poor performance on mobile

## Security Considerations

**Open redirects in navigation:**
- Risk: Potential for malicious redirection if navigation config compromised
- Files: `src/config/navigation.ts`
- Current mitigation: Validation function `validateNavItems`
- Recommendations: Input sanitization, whitelist approach for external links

**External resource loading:**
- Risk: Loading external resources (WhatsApp, Facebook, Twitter) without integrity checks
- Files: `src/layouts/Layout.astro`, `src/components/Footer.astro`
- Current mitigation: Using `rel="noopener noreferrer"` on external links
- Recommendations: Subresource Integrity (SRI) for external scripts, CSP headers

## Performance Bottlenecks

**Large images:**
- Problem: Unoptimized large image files (e.g., `dredit.jpg` 145KB, `rodill_mano_espalda.avif` 33KB)
- Files: Multiple image files in `assets/` directory
- Cause: No image optimization pipeline, no lazy loading
- Improvement path: Implement image optimization, lazy loading, responsive images

**No code splitting:**
- Problem: All JavaScript loaded at once
- Files: `src/layouts/Layout.astro` (client-side script in lines 144-155)
- Cause: Minimal client-side JS but no optimization
- Improvement path: Implement code splitting, defer non-critical JavaScript

## Fragile Areas

**Navigation configuration:**
- Files: `src/config/navigation.ts`
- Why fragile: Manual configuration, no data validation beyond basic checks
- Safe modification: Use the `validateNavItems` function before changes
- Test coverage: Basic validation exists but limited edge case testing

**Sticky buttons component:**
- Files: `src/layouts/Layout.astro` (lines 67-142)
- Why fragile: Complex CSS, inline styles, multiple responsive breakpoints
- Safe modification: Extract to separate component, use CSS variables for theming
- Test coverage: No visual testing for responsive behavior

## Scaling Limits

**Content management:**
- Current capacity: Manual editing of Astro files
- Limit: Scalability issues with large content volume
- Scaling path: Implement headless CMS or content layer

**Performance:**
- Current capacity: Basic static site performance
- Limit: Large images and unoptimized assets
- Scaling path: Image optimization, caching strategy, CDN integration

## Dependencies at Risk

**Astro framework:**
- Risk: Version 6.0.7, early adoption of new version
- Impact: Potential breaking changes, limited community support for specific version
- Migration plan: Monitor for updates, test compatibility before upgrading

**Tailwind CSS v4:**
- Risk: Early adoption of Tailwind CSS v4 (beta)
- Impact: Potential API changes, limited documentation and community resources
- Migration plan: Consider pinning to stable version if issues arise

## Missing Critical Features

**Accessibility improvements:**
- Problem: Basic accessibility but missing advanced features
- Blocks: Full compliance with WCAG 2.1 AA, keyboard navigation for complex components
- Priority: High for medical website

**SEO optimization:**
- Problem: Basic meta tags but missing advanced SEO features
- Blocks: Schema markup expansion, structured data for services and locations
- Priority: Medium for local SEO

**Analytics and monitoring:**
- Problem: No user analytics or error tracking
- Blocks: Understanding user behavior, identifying issues
- Priority: Medium for business insights

## Test Coverage Gaps

**Component testing:**
- What's not tested: Visual rendering, responsive behavior, user interactions
- Files: `src/components/*.astro`, all Astro pages
- Risk: UI regressions not caught during development
- Priority: High for visual consistency

**Integration testing:**
- What's not tested: Navigation flow, form submissions, external link functionality
- Files: Page navigation, contact forms, social media links
- Risk: Broken user journeys not detected
- Priority: Medium for user experience

**Performance testing:**
- What's not tested: Load times, mobile performance, image optimization
- Files: Asset loading, JavaScript execution
- Risk: Poor user experience due to performance issues
- Priority: High for medical website credibility

---

*Concerns audit: 2026-03-19*