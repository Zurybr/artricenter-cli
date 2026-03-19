# Coding Conventions

**Analysis Date:** 2026-03-19

## Naming Patterns

**Files:**
- `.astro` files for Astro components (e.g., `Header.astro`, `Navigation.astro`)
- `.ts` files for TypeScript configuration and utilities (e.g., `navigation.ts`)
- `__tests__/` directory for test files (e.g., `navigation.test.ts`)

**Functions:**
- LowerCamelCase for JavaScript/TypeScript functions (e.g., `validateNavItems`, `isActive`, `isExternal`)
- LowerCamelCase for Astro component logic functions

**Variables:**
- LowerCamelCase for variables (e.g., `currentPage`, `navItems`, `variantClasses`)
- LowerCamelCase for destructured props from Astro.props

**Types/Interfaces:**
- PascalCase for TypeScript interfaces (e.g., `Props`, `NavItem`, `CtaConfig`, `NavConfig`)
- Descriptive names with clear semantic meaning

## Code Style

**Formatting:**
- **Editor:** No specific linter configured, but consistent formatting observed
- **File Structure:** Astro component pattern with frontmatter script section
- **Indentation:** Spaces consistently used
- **Line Length:** Generally well-formatted with reasonable line lengths

**Astro Component Pattern:**
```astro
---
interface Props {
  requiredProp: string;
  optionalProp?: string;
}

const { requiredProp, optionalProp = 'default' } = Astro.props;
---

<section class="container">
  <h1>{requiredProp}</h1>
  {optionalProp && <p>{optionalProp}</p>}
</section>
```

**TypeScript Interface Usage:**
```typescript
export interface NavItem {
  label: string;
  page: string;
  hash?: string;
  children?: NavItem[];
}

export function validateNavItems(items: NavItem[]): void {
  // Implementation
}
```

## Import Organization

**Order:**
1. Relative imports using `@/` alias (e.g., `@/config/navigation`)
2. Built-in imports (e.g., `new Date()`)
3. Third-party imports (minimal usage observed)

**Import Patterns:**
```typescript
// Relative imports with @/ alias
import Header from '@/components/Header.astro';
import { navConfig } from '@/config/navigation';

// Astro-specific imports
import Layout from '@/layouts/Layout.astro';

// Standard imports
import { defineConfig } from 'vitest/config';
```

**Path Aliases:**
- `@/` mapped to `src/` directory (implied by import patterns)

## Error Handling

**Patterns:**
- Validation functions throw descriptive errors (e.g., `validateNavItems`)
- Error messages include problematic data (e.g., `Invalid nav item: ${JSON.stringify(item)}`)
- Optional properties marked with `?` in interfaces for nullable fields

**Error Handling Example:**
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

## Logging

**Framework:** Console logging used for debugging
**Patterns:** Minimal logging in production code,主要集中在客户端脚本中

**JavaScript Console Patterns:**
```javascript
// Client-side script for mobile menu
console.log('Menu toggle functionality');
```

## Comments

**When to Comment:**
- Complex client-side JavaScript functionality
- Section headers in HTML markup for visual organization
- Accessibility features (aria-labels)

**Astro/TSDoc Pattern:**
```typescript
interface Props {
  id?: string;
  title: string;
  content: string;
  variant?: 'default' | 'highlight' | 'compact';
}
```

**HTML Comments:**
```html
<!-- Desktop Navigation -->
<!-- Mobile Header -->
<!-- Sticky Buttons -->
```

## Function Design

**Size:** Functions are generally small and focused
**Parameters:** Objects used for multiple related parameters (e.g., `Astro.props`)
**Return Values:** Void for validation functions, direct values for simple functions

**Function Pattern Examples:**
```typescript
// Simple utility function
function isActive(page: string, currentPage: string): boolean {
  return page === currentPage;
}

// Validation function with side effects
export function validateNavItems(items: NavItem[]): void {
  // Throws error on invalid data
}

// Component property destructuring
const { items, currentPage } = Astro.props;
```

## Module Design

**Exports:**
- Named exports for interfaces and utility functions
- Default exports for configuration objects
- Co-location of related types and functions

**Export Pattern:**
```typescript
// Configuration export
export const navItems: NavItem[] = [...];

// Utility export
export function validateNavItems(items: NavItem[]): void;

// Type exports
export interface NavItem {
  label: string;
  page: string;
  hash?: string;
  children?: NavItem[];
}
```

**Barrel Files:** Not used; direct imports from individual files

---

*Convention analysis: 2026-03-19*