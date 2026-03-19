# External Integrations

**Analysis Date:** 2026-03-19

## APIs & External Services

**Communication:**
- WhatsApp Business API - Used for direct contact via WhatsApp buttons
  - Implementation: Phone number integration in links (525559890607)
  - Link format: `https://wa.me/525559890607?text=...`
  - Contact: Dra. Edith direct messaging

**Maps:**
- Google Maps - Used for clinic location integration
  - Implementation: Embedded maps via `<iframe>` in contact section
  - Location: Unknown from code analysis (requires `public/` content)

## Data Storage

**Databases:**
- None detected - Static site, no database integration

**File Storage:**
- Local filesystem - Static assets in `/public/` and `/assets/` directories
  - Images: `/assets/dredit.jpg` (doctor image)
  - Static files: CSS, JS, media files

**Caching:**
- CDN recommended - Static assets benefit from CDN caching
- Browser caching - Assets served with cache headers

## Authentication & Identity

**Auth Provider:**
- None detected - Public website, no authentication required
- Form handling: No contact forms detected

## Monitoring & Observability

**Error Tracking:**
- None detected - No error tracking service integration

**Logs:**
- None detected - Static site, no server logs

## CI/CD & Deployment

**Hosting:**
- Unknown - Not specified in configuration
- Compatible with: Vercel, Netlify, GitHub Pages, any static hosting

**CI Pipeline:**
- None detected - No CI/CD configuration found

## Environment Configuration

**Required env vars:**
- None detected - No environment variables required

**Secrets location:**
- None detected - No secret management needed

## Webhooks & Callbacks

**Incoming:**
- None detected - No webhook endpoints

**Outgoing:**
- WhatsApp API - Outgoing links for contact
  - URL: `https://wa.me/525559890607`
  - Purpose: Patient communication

## External Services Integration

**Social Media:**
- WhatsApp - Direct patient communication
- Facebook Open Graph - Social sharing metadata
  - Implementation: Meta tags in `/src/layouts/Layout.astro`
  - Tags: og:locale, og:type, og:site_name, og:title, og:description

**Analytics:**
- None detected - No analytics service integration
- Recommendation: Google Analytics or similar could be added

**SEO:**
- Structured Data - Schema.org MedicalClinic markup
  - Implementation: JSON-LD in `/src/layouts/Layout.astro`
  - Purpose: Search engine optimization

**Contact Forms:**
- None detected - Only WhatsApp integration for contact
- Recommendation: Formspree, Formstack, or custom form handling

---

*Integration audit: 2026-03-19*
```