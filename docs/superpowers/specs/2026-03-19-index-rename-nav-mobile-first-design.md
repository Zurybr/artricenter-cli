# Design: Homepage Rename & Mobile-First Navigation Enhancement

**Date:** 2026-03-19
**Project:** Artricenter Medical Clinic Website
**Status:** Approved

## Overview

Rename `quienes-somos.html` to `index.html` to establish a proper homepage at the root URL, and enhance the navigation with a mobile-first approach featuring modern shadcn/Radix UI-style interactions.

## Goals

1. **SEO & UX:** Establish `/` as the proper homepage for better SEO and cleaner URLs
2. **Mobile-First:** Design navigation for mobile first, then enhance for desktop
3. **Modern Interactions:** Implement smooth hover transitions, animated underlines, and backdrop blur effects
4. **Accessibility:** Maintain WCAG 2.1 AA compliance with keyboard navigation and screen reader support

## Current State

- 5 HTML pages with custom JavaScript navigation
- Click-triggered dropdowns with instant appearance
- Basic mobile drawer with fade-in animation
- Brand logo links to `quienes-somos.html`
- No visual indicator for active section

## Proposed Changes

### 1. File Renaming & Reference Updates

**Rename:**
- `quienes-somos.html` → `index.html`

**Update references in:**
- `static/js/nav-config.js`: 5 occurrences
- `static/js/navbar.js`: 3 occurrences (default page fallbacks + brand link)
- `contactanos.html`: 1 link
- SEO metadata within the renamed file

**Impact:**
- Homepage accessible at `/` instead of `/quienes-somos.html`
- Better SEO (search engines prefer index.html)
- Cleaner, more professional URLs
- All hash fragments remain unchanged

---

### 2. Mobile-First Navigation Architecture

#### Base: Mobile Styles (< 768px)

**Hamburger Menu:**
- 48×48px touch target (WCAG compliant)
- Three-line icon
- Positioned on right side of navbar
- Opens full-screen slide-in drawer

**Mobile Drawer:**
- Full viewport height minus header
- Slides in from right with backdrop blur
- Smooth 300ms cubic-bezier animation
- Backdrop overlay with dimmed background
- Staggered cascading animation for menu items
- Easy close: tap outside, swipe right, or tap X button

**Mobile Spacing:**
- Menu items: 60px height with clear separation
- Submenu items: 52px height, slightly indented
- Safe area support for iOS devices
- 44px minimum touch targets throughout

#### Enhancement: Desktop Styles (≥ 768px)

**Full Navigation:**
- Horizontal menu replaces hamburger
- Hover-triggered dropdowns with smooth fade+slide
- Radix-style animated underline for active section
- CTA button: "Agendar valoración"
- Reduced spacing (12px gap vs mobile's generous spacing)

---

### 3. Navigation Interactions

#### Hover-Triggered Dropdowns (Desktop)

**Behavior:**
- Mouse hover opens dropdown smoothly
- 200ms ease-out transition
- Opacity: 0 → 1
- Transform: translateY(-8px) → translateY(0)
- Click still works for accessibility
- Touch devices fall back to click (no hover)

#### Active Underline Indicator

**Implementation:**
- `::after` pseudo-element on nav links
- Animated width transition: 0 → 100%
- Color: `#124a8e` (brand blue)
- Height: 2px
- 200ms ease-out transition
- Shows on current page's top-level item

#### Mobile Drawer Animation

**Enhancements:**
- Slide-in from right: `translateX(100%)` → `translateX(0)`
- Backdrop blur: `backdrop-filter: blur(8px)`
- Staggered menu item animations (cascading delay)
- 300ms cubic-bezier easing for natural feel

---

### 4. CSS Design System

#### New Variables

```css
--nav-dropdown-transition: 200ms ease-out;
--nav-drawer-transition: 300ms cubic-bezier(0.4, 0, 0.2, 1);
--nav-underline-height: 2px;
--nav-underline-color: var(--brand-blue);
--nav-touch-target: 44px;
--nav-backdrop-blur: 8px;
```

#### Key Animation Classes

```css
/* Dropdown */
.site-navbar__dropdown {
  opacity: 0;
  transform: translateY(-8px);
  transition: opacity var(--nav-dropdown-transition),
              transform var(--nav-dropdown-transition);
}

.site-navbar__item.is-open > .site-navbar__dropdown {
  opacity: 1;
  transform: translateY(0);
}

/* Mobile drawer */
.site-navbar__drawer {
  transform: translateX(100%);
  transition: transform var(--nav-drawer-transition);
}

.site-navbar.mobile-menu-open .site-navbar__drawer {
  transform: translateX(0);
}

/* Active underline */
.site-navbar__link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: var(--nav-underline-height);
  background: var(--nav-underline-color);
  transition: width 200ms ease-out;
}

.site-navbar__link.is-current::after {
  width: 100%;
}
```

---

### 5. JavaScript Enhancements

#### Touch Device Detection

```javascript
function isTouchDevice() {
  return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}
```

#### Hover Triggers (Desktop)

```javascript
function bindHoverTriggers(navRoot) {
  if (isTouchDevice()) {
    return; // Skip on touch devices
  }

  navRoot.querySelectorAll('[data-dropdown-item="true"]').forEach(function(item) {
    item.addEventListener('mouseenter', function() {
      closeAllDropdowns(navRoot);
      toggleDropdown(item, true);
    });

    item.addEventListener('mouseleave', function() {
      toggleDropdown(item, false);
    });
  });
}
```

#### File Reference Updates

**`navbar.js`:**
- Line 11: `"quienes-somos.html"` → `"index.html"`
- Line 78: `"quienes-somos.html"` → `"index.html"`
- Line 243: `brand.href = "quienes-somos.html"` → `brand.href = "index.html"`

**`nav-config.js`:**
- All 5 occurrences of `"quienes-somos.html"` → `"index.html"`

---

### 6. Accessibility (WCAG 2.1 AA)

- **Keyboard:** Tab through nav items, Enter/Space to activate
- **ARIA:** `aria-expanded`, `aria-controls`, `aria-label` maintained
- **Focus:** Visible focus rings on all interactive elements
- **Screen Reader:** Proper semantics (`<nav>`, `<button>`, `<a>`)
- **Touch:** 44px minimum touch targets
- **Reduced Motion:** Respect `prefers-reduced-motion` preference

---

### 7. Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile Safari (iOS 14+)
- Chrome Mobile (Android 10+)

---

### 8. Testing Checklist

- [ ] File rename: `quienes-somos.html` → `index.html`
- [ ] All references updated correctly
- [ ] Mobile: Hamburger opens/closes drawer smoothly
- [ ] Mobile: Backdrop blur effect visible
- [ ] Mobile: Close on tap outside works
- [ ] Desktop: Hover triggers dropdowns
- [ ] Desktop: Click still works (accessibility fallback)
- [ ] Active underline shows on correct page
- [ ] Touch devices fall back to click (no hover)
- [ ] Keyboard navigation works (Tab, Enter, Escape)
- [ ] Cross-browser testing complete
- [ ] Reduced motion respected

---

## Implementation Order

1. Rename `quienes-somos.html` to `index.html`
2. Update all file references (JS config, navbar JS, contactanos HTML)
3. Add CSS variables and animation classes
4. Implement hover triggers in navbar.js
5. Add mobile drawer staggered animations
6. Test accessibility features
7. Cross-browser testing

---

## Files Modified

1. `quienes-somos.html` → `index.html` (renamed)
2. `static/js/nav-config.js` (5 refs updated)
3. `static/js/navbar.js` (3 refs + hover logic)
4. `static/css/index.css` (animations, mobile-first styles)
5. `contactanos.html` (1 link updated)

---

## Success Criteria

- Homepage loads at `/` without errors
- All navigation links work correctly
- Mobile drawer slides smoothly with backdrop blur
- Desktop dropdowns open on hover with smooth animations
- Active underline indicator shows on current page
- Keyboard navigation works throughout
- Touch devices fall back to click interactions
- All browsers render correctly
- WCAG 2.1 AA accessibility maintained
