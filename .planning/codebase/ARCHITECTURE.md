# Architecture

**Analysis Date:** 2026-03-19

## Pattern Overview

**Overall:** Component-Based Static Site with Server-Side Rendering

**Key Characteristics:**
- Astro framework with TypeScript
- Component-based architecture using .astro components
- Static site generation with dynamic server-side rendering
- Content-focused design with medical specialty focus
- Responsive design with Tailwind CSS

## Layers

**Layout Layer:**
- Purpose: Provides page template structure and global elements
- Location: `/home/zurybr/workspaces/artricenter/src/layouts/Layout.astro`
- Contains: HTML structure, SEO metadata, navigation, global styles
- Depends on: Components, navigation config, styles
- Used by: All page components

**Page Layer:**
- Purpose: Defines individual pages with content sections
- Location: `/home/zurybr/workspaces/artricenter/src/pages/` (e.g., index.astro, especialidades.astro)
- Contains: Page-specific content and section layouts
- Depends on: Layout, content sections, navigation config
- Used by: Astro router for serving pages

**Component Layer:**
- Purpose: Reusable UI components for consistent design
- Location: `/home/zurybr/workspaces/artricenter/src/components/`
- Contains: Header, Footer, Navigation, ContentSection, PageSection
- Depends on: Astro framework, Tailwind CSS
- Used by: Layout and page components

**Configuration Layer:**
- Purpose: Centralized configuration and data management
- Location: `/home/zurybr/workspaces/artricenter/src/config/`
- Contains: Navigation configuration, validation logic
- Depends on: TypeScript types and validation
- Used by: Layout and components for navigation

**Styles Layer:**
- Purpose: Global and component-specific styling
- Location: `/home/zurybr/workspaces/artricenter/src/styles/global.css`
- Contains: Tailwind CSS imports, global overrides
- Depends on: Tailwind CSS framework
- Used by: All components for styling

## Data Flow

**Page Rendering:**

1. User requests page via URL
2. Astro router maps URL to corresponding .astro page
3. Page component imports required layout and components
4. Layout component applies SEO metadata and wraps page content
5. Components render their HTML structure with Tailwind classes
6. Astro generates static HTML with minimal JavaScript

**Navigation Flow:**

1. Navigation config loaded from `/src/config/navigation.ts`
2. Layout component consumes nav config for navigation menu
3. Navigation component renders menu items and CTAs
4. Hash-based navigation enables smooth scrolling to sections

**State Management:**
- Client-side: Minimal JavaScript for smooth scrolling and interactive elements
- Server-side: Static content with metadata for SEO
- No complex state management required for static content site

## Key Abstractions

**Layout Component:**
- Purpose: Provides consistent page structure across all pages
- Examples: `/src/layouts/Layout.astro`
- Pattern: Template with slot-based content injection

**Content Section:**
- Purpose: Reusable section wrapper with consistent styling
- Examples: `/src/components/ContentSection.astro`
- Pattern: Section with id, title, and content slot

**Navigation Configuration:**
- Purpose: Centralized navigation structure and validation
- Examples: `/src/config/navigation.ts`
- Pattern: TypeScript interfaces with validation functions

**Page Component:**
- Purpose: Individual page definition with specific content
- Examples: `/src/pages/index.astro`, `/src/pages/especialidades.astro`
- Pattern: Frontmatter with layout import and content JSX

## Entry Points

**Main Entry Point:**
- Location: `/src/pages/index.astro`
- Triggers: Root page URL (/)
- Responsibilities: Main landing page with clinic information

**Secondary Entry Points:**
- Location: `/src/pages/especialidades.astro`, `/src/pages/tratamiento-medico-integral.astro`, `/src/pages/club-vida-y-salud.astro`, `/src/pages/contactanos.astro`
- Triggers: Route-based page requests
- Responsibilities: Content-specific pages for clinic services

**Layout Entry Point:**
- Location: `/src/layouts/Layout.astro`
- Triggers: All page components
- Responsibilities: Global HTML structure, SEO, navigation

## Error Handling

**Strategy:** Graceful degradation with validation

**Patterns:**
- Navigation validation in `/src/config/navigation.ts` validates required properties
- Static generation fails fast for invalid navigation structure
- Minimal error handling on client side for static content

## Cross-Cutting Concerns

**SEO:** Comprehensive SEO implementation in Layout component with Open Graph, Twitter cards, and structured data

**Responsive Design:** Tailwind CSS responsive utilities with mobile-first approach

**Accessibility:** Semantic HTML, aria labels, keyboard navigation support

**Performance:** Static site generation, minimal JavaScript, optimized assets

---

*Architecture analysis: 2026-03-19*
*