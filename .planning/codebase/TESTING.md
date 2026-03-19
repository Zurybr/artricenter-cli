# Testing Patterns

**Analysis Date:** 2026-03-19

## Test Framework

**Runner:**
- **Framework:** Vitest
- **Version:** 4.1.0
- **Config:** `[vitest.config.ts](vitest.config.ts:1)`

**Assertion Library:**
- **Built-in:** Vitest's `expect` for test assertions
- **Testing utilities:** Vitest's global functions enabled

**Run Commands:**
```bash
npm test              # Run all tests
npm run test          # Run all tests (via package.json script)
# No watch mode command detected
# No coverage command detected
```

## Test File Organization

**Location:**
- **Primary:** `src/config/__tests__/` directory
- **Pattern:** Co-located with source files in `__tests__` subdirectories

**Naming:**
- **Pattern:** `[filename].test.ts` (e.g., `navigation.test.ts`)
- **Consistent:** All test files follow this naming convention

**Structure:**
```
src/
└── config/
    ├── navigation.ts           # Source file
    └── __tests__/
        └── navigation.test.ts  # Test file
```

## Test Structure

**Suite Organization:**
```typescript
describe('Navigation Config', () => {
  it('should have valid structure', () => {
    expect(() => validateNavItems(navItems)).not.toThrow();
  });

  it('should have required properties for each item', () => {
    navItems.forEach(item => {
      expect(item).toHaveProperty('label');
      expect(item).toHaveProperty('page');
      expect(item.label).toBeTruthy();
      expect(item.page).toBeTruthy();
    });
  });

  it('should have valid children structure', () => {
    navItems.forEach(item => {
      if (item.children) {
        item.children.forEach(child => {
          expect(child).toHaveProperty('label');
          expect(child).toHaveProperty('page');
        });
      }
    });
  });
});
```

**Patterns:**
- **Setup:** Direct import of test data from source modules
- **Teardown:** Not needed for current test patterns
- **Assertion:** Jest-like matchers (`expect`, `toHaveProperty`, `toBeTruthy`)

## Mocking

**Framework:** Vitest provides mocking capabilities, minimal mocking observed

**Patterns:**
```typescript
import { describe, it, expect } from 'vitest';
import { navItems, validateNavItems } from '../navigation';

describe('Navigation Config', () => {
  // Direct function testing
  it('should have valid structure', () => {
    expect(() => validateNavItems(navItems)).not.toThrow();
  });

  // Data structure validation
  it('should have required properties for each item', () => {
    navItems.forEach(item => {
      expect(item).toHaveProperty('label');
      expect(item).toHaveProperty('page');
    });
  });
});
```

**What to Mock:**
- External API calls (not present in current codebase)
- Date/time functions (minimal usage)
- Browser APIs (client-side JavaScript)

**What NOT to Mock:**
- Configuration data (imported directly)
- Utility functions (tested directly)
- Component logic (minimal client-side JS)

## Fixtures and Factories

**Test Data:**
```typescript
export const navItems: NavItem[] = [
  {
    label: "Quienes Somos",
    page: "/",
    children: [
      { label: "Artricenter", page: "/", hash: "#artricenter" },
      { label: "Nuestra Historia", page: "/", hash: "#nuestra-historia" }
    ]
  }
];
```

**Location:**
- **Source:** Test data imported from `src/config/navigation.ts`
- **Pattern:** Shared between implementation and tests
- **Maintenance:** Data updated in single location

## Coverage

**Requirements:** Not explicitly enforced or configured
**View Coverage:** No coverage command detected in package.json
**Target:** Unknown, likely minimal coverage requirements

**Coverage Gaps:**
- **Astro components:** No test coverage for visual components
- **Client-side JavaScript:** No tests for interactive functionality
- **Integration tests:** No end-to-end testing detected

## Test Types

**Unit Tests:**
- **Scope:** Configuration validation and data structure testing
- **Approach:** Direct function calls with assertions
- **Coverage:** Limited to configuration files only

**Integration Tests:**
- **Framework:** Not implemented
- **Scope:** No component or page integration tests
- **Missing:** Testing of Astro components, navigation flows, user interactions

**E2E Tests:**
- **Framework:** Not used
- **Status:** No end-to-end testing detected

## Common Patterns

**Async Testing:**
```typescript
// Not currently used - no async patterns detected
```

**Error Testing:**
```typescript
it('should validate navigation items', () => {
  expect(() => validateNavItems(invalidItems)).toThrow();
});
```

**Data Structure Testing:**
```typescript
it('should have required properties', () => {
  navItems.forEach(item => {
    expect(item).toHaveProperty('label');
    expect(item).toHaveProperty('page');
  });
});
```

## Testing Strategy Recommendations

**Current State:**
- **Coverage:** Minimal, only configuration validation
- **Focus:** Data structure validation
- **Missing:** Component, integration, and e2e testing

**Recommended Improvements:**
1. **Component Testing:** Add tests for Astro components
2. **Integration Testing:** Test navigation functionality
3. **Client-side Testing:** Test JavaScript interactions
4. **Coverage Tooling:** Configure coverage reporting
5. **E2E Testing:** Add testing framework for user flows

---

*Testing analysis: 2026-03-19*