# Astro Migration Design - Artricenter

**Date:** 2026-03-19
**Status:** Approved
**Author:** Claude Sonnet

## Overview

Complete migration of Artricenter website from static HTML + JavaScript navigation to Astro with component-based architecture using Tailwind CSS.

## Problem Statement

Current site has malformed navigation elements and poor visual presentation. Navigation is generated client-side via JavaScript, leading to:
- Malformed DOM elements
- Poor accessibility
- Difficult maintenance
- No component reusability

## Solution

Migrate to Astro with:
- Server-side rendering for navigation (no JS malformation issues)
- Component-based architecture
- Tailwind CSS for styling
- Type-safe configuration

## Architecture

### Project Structure

```
src/
├── layouts/
│   └── Layout.astro           # Base layout with HTML structure
├── components/
│   ├── Header.astro           # Header with logo and CTA container
│   ├── Navigation.astro       # Desktop + mobile menu with dropdowns
│   ├── Footer.astro           # Site footer
│   ├── PageSection.astro      # Generic section component
│   └── ContentSection.astro   # Reusable content sections
├── pages/
│   ├── index.astro            # Main page (Quienes Somos)
│   ├── especialidades.astro
│   ├── tratamiento-medico-integral.astro
│   ├── club-vida-y-salud.astro
│   └── contactanos.astro
├── config/
│   └── navigation.ts          # Type-safe navigation configuration
└── styles/
    └── global.css             # Minimal global styles
```

### Design Philosophy

Each component has:
- **Single responsibility:** One clear purpose
- **Well-defined interface:** Props for data, outputs for rendering
- **Independence:** Can be understood and tested in isolation
- **Reusability:** Used across multiple pages

## Components

### Navigation.astro

Main navigation component with server-side rendering.

**Props:**
```typescript
interface NavigationProps {
  items: NavItem[];
  currentPage: string;
}
```

**Features:**
- Desktop menu with hover-based dropdowns
- Mobile drawer with toggle
- Server-side rendering (no JS for basic functionality)
- Tailwind CSS inline styles
- Minimal JavaScript for mobile toggle only

**Styling:** Each component has its own Tailwind classes

### Header.astro

Site header with branding and navigation.

**Props:**
```typescript
interface HeaderProps {
  navItems: NavItem[];
  currentPage: string;
}
```

**Features:**
- Logo with home link
- Renders Navigation.astro
- Container for floating CTAs

### PageSection.astro

Generic component for content sections.

**Props:**
```typescript
interface PageSectionProps {
  id: string;
  title: string;
  content: string;
  variant?: 'default' | 'highlight' | 'compact';
}
```

**Features:**
- Configurable variants
- Accessibility: ARIA labels, heading hierarchy
- Consistent spacing and typography

### Configuration

**Type-safe navigation config:**
```typescript
interface NavItem {
  label: string;
  page: string;
  hash?: string;
  children?: NavItem[];
}

interface NavConfig {
  items: NavItem[];
  ctas: {
    edith: { label: string; href: string };
    whatsapp: { label: string; href: string };
  };
}
```

## Data Flow

**Top-down architecture:**

1. Config centralization: `navigation.ts` contains all navigation data
2. Pages read config: Each page imports navigation config
3. Props flow down: Page → Layout → Header → Navigation
4. Server-side rendering: Everything renders at build time

**Example:**
```typescript
// src/pages/index.astro
---
import Layout from '@/layouts/Layout.astro';
import { navItems } from '@/config/navigation';
---
<Layout title="Quienes Somos" navItems={navItems}>
  <Content content={quienesSomosContent} />
</Layout>

// src/layouts/Layout.astro
---
const { title, navItems } = Astro.props;
---
<Header navItems={navItems} currentPage="index.astro" />
<slot />
<Footer />
```

## Styling Strategy

**Tailwind CSS approach:**
- **Component-specific styles:** Each component has Tailwind classes inline
- **Minimal global CSS:** Only for reset and CSS variables
- **Utility classes:** Flexbox, grid, spacing, colors, typography
- **Responsive design:** `md:`, `lg:` prefixes within components
- **Independent components:** Each defines its own appearance

**No component libraries:** Pure Tailwind utilities for maximum flexibility and performance.

## Error Handling

### Build-time Errors
- TypeScript type checking for navigation config
- Props validation in components
- Astro build errors for invalid routes/imports

### Runtime Errors
- Graceful degradation if JavaScript fails
- Navigation works without JS (static links)
- Fallbacks for images/media

### Validation
```typescript
export function validateNavItems(items: NavItem[]): void {
  items.forEach(item => {
    if (!item.label || !item.page) {
      throw new Error(`Invalid nav item: ${JSON.stringify(item)}`);
    }
    if (item.children) {
      validateNavItems(item.children);
    }
  });
}
```

### User Experience
- 404 page for non-existent routes
- Clear error messages in development
- Production: Fallback pages without crashes

## SEO Considerations

- Server-side rendering = perfect SEO
- Meta tags per page
- Semantic HTML structure
- Proper heading hierarchy
- Structured data (Schema.org)

## Migration Strategy

**Phase 1:** Setup Astro project
- Initialize Astro with Tailwind
- Create base structure
- Configure TypeScript

**Phase 2:** Core components
- Build Layout.astro
- Build Navigation.astro
- Build Header.astro
- Build Footer.astro

**Phase 3:** Content components
- Build PageSection.astro
- Build ContentSection.astro

**Phase 4:** Page migration
- Migrate index.astro (Quienes Somos)
- Migrate remaining pages

**Phase 5:** Polish
- Testing
- Accessibility audit
- Performance optimization

## Success Criteria

- [ ] Navigation renders correctly without JavaScript
- [ ] All components reusable across pages
- [ ] Tailwind styles applied consistently
- [ ] Type-safe configuration
- [ ] No malformed DOM elements
- [ ] Mobile responsive
- [ ] Accessibility standards met
- [ ] SEO maintained or improved

## Estimated Timeline

6-8 hours total:
- Phase 1: 1 hour
- Phase 2: 2-3 hours
- Phase 3: 1-2 hours
- Phase 4: 2 hours
- Phase 5: 1 hour
