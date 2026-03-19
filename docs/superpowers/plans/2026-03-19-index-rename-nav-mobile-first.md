# Homepage Rename & Mobile-First Navigation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Enhance navigation with mobile-first design, hover-triggered dropdowns, animated underline indicator, backdrop blur, and smooth drawer animations.

**Note:** File renaming (`quienes-somos.html` → `index.html`) and reference updates are already complete. This plan focuses on adding new features only.

**Architecture:** Mobile-first CSS with progressive enhancement for desktop. Touch device detection in JavaScript to conditionally enable hover triggers while maintaining click-based accessibility. CSS transitions for all animations with fallbacks for reduced motion preferences.

**Tech Stack:** Vanilla JavaScript (ES5), CSS3 (custom properties, transitions, transforms), HTML5

---

## File Structure

**Files to create:**
- None (all modifications to existing files)

**Files to modify:**
1. `static/css/index.css` (add navigation animation variables, backdrop blur, staggered animations, underline indicator)
2. `static/js/navbar.js` (add touch detection and hover trigger logic)

**Current State Verification:**
- ✓ `index.html` exists (already renamed)
- ✓ `nav-config.js` references `index.html` (5 occurrences)
- ✓ `navbar.js` references `index.html` (lines 11, 79, 250)
- ✓ `contactanos.html` links to `index.html`
- ✓ Dropdown CSS has opacity and transform (line 193)
- ✓ Drawer CSS has opacity transition (line 270)
- ✗ Missing: CSS variables for nav transitions
- ✗ Missing: Backdrop blur on drawer
- ✗ Missing: Drawer slide-in transform
- ✗ Missing: Staggered menu item animations
- ✗ Missing: Active underline indicator
- ✗ Missing: Touch device detection
- ✗ Missing: Hover trigger logic

---

## Task 1: Add CSS Variables for Navigation Animations

**Files:**
- Modify: `static/css/index.css` (line 29, after `--focus-ring`)

- [ ] **Step 1: Add navigation animation variables**

Find line 29 (`--focus-ring: ...;`) and insert after it:

```css
  --nav-dropdown-transition: 200ms ease-out;
  --nav-drawer-transition: 300ms cubic-bezier(0.4, 0, 0.2, 1);
  --nav-underline-height: 2px;
  --nav-underline-color: var(--brand-blue);
  --nav-touch-target: 44px;
  --nav-backdrop-blur: 8px;
```

Expected: Variables added to `:root` selector (ending at line 35)

- [ ] **Step 2: Verify CSS syntax**

```bash
head -40 static/css/index.css | tail -15
```

Expected: See new variables in output

- [ ] **Step 3: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add CSS variables for navigation animations

- Add dropdown transition timing (200ms ease-out)
- Add drawer transition with cubic-bezier easing (300ms)
- Add underline indicator variables
- Add touch target and backdrop blur variables"
```

---

## Task 2: Update Dropdown Open State

**Files:**
- Modify: `static/css/index.css` (line 210)

- [ ] **Step 1: Update dropdown open state rule**

Find `.site-navbar__item.is-open .site-navbar__dropdown {` at line 210 and update:

```css
.site-navbar__item.is-open .site-navbar__dropdown {
  visibility: visible;
  opacity: 1;
  transform: translateY(0);
}
```

Expected: Open state now includes visibility, opacity, and transform reset

- [ ] **Step 2: Verify CSS**

```bash
sed -n '210,214p' static/css/index.css
```

Expected: See updated rule

- [ ] **Step 3: Commit**

```bash
git add static/css/index.css
git commit -m "feat: update dropdown open state for smooth animation

- Add visibility: visible to open state
- Reset transform for slide-in effect
- Dropdowns now smoothly fade and slide when opened"
```

---

## Task 3: Add Mobile Drawer Backdrop Blur and Transform

**Files:**
- Modify: `static/css/index.css` (line 270)

- [ ] **Step 1: Update drawer base styles**

Find `.site-navbar__drawer {` at line 270 and update the rule:

```css
.site-navbar__drawer {
  position: fixed;
  top: var(--nav-height);
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(10, 22, 40, 0.48);
  backdrop-filter: blur(var(--nav-backdrop-blur));
  -webkit-backdrop-filter: blur(var(--nav-backdrop-blur));
  opacity: 0;
  pointer-events: none;
  transform: translateX(100%);
  transition: transform var(--nav-drawer-transition),
              opacity var(--nav-drawer-transition);
}
```

Expected: Drawer now has backdrop-filter and transform

- [ ] **Step 2: Update drawer open state**

Find `.site-navbar.mobile-menu-open .site-navbar__drawer {` at line 282 and update:

```css
.site-navbar.mobile-menu-open .site-navbar__drawer {
  transform: translateX(0);
  opacity: 1;
  pointer-events: auto;
}
```

Expected: Open state resets transform

- [ ] **Step 3: Verify CSS**

```bash
sed -n '270,286p' static/css/index.css
```

Expected: See backdrop-filter and transform properties

- [ ] **Step 4: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add backdrop blur and slide-in to mobile drawer

- Add backdrop-filter with vendor prefix for Safari
- Add translateX transform for slide-in effect
- Smooth 300ms cubic-bezier transition
- Mobile drawer now slides from right with frosted glass effect"
```

---

## Task 4: Add Staggered Menu Item Animations

**Files:**
- Modify: `static/css/index.css` (line 296)

- [ ] **Step 1: Update mobile group styles**

Find `.site-navbar__mobile-group {` at line 296 and update:

```css
.site-navbar__mobile-group {
  border-bottom: 1px solid var(--line);
  padding: var(--space-2) 0;
  opacity: 0;
  transform: translateX(20px);
  animation: slideIn 0.3s ease-out forwards;
}
```

Expected: Mobile group has opacity, transform, and animation

- [ ] **Step 2: Add staggered animation delays**

Insert after the `.site-navbar__mobile-group` rule:

```css
.site-navbar__mobile-group:nth-child(1) { animation-delay: 0.05s; }
.site-navbar__mobile-group:nth-child(2) { animation-delay: 0.1s; }
.site-navbar__mobile-group:nth-child(3) { animation-delay: 0.15s; }
.site-navbar__mobile-group:nth-child(4) { animation-delay: 0.2s; }
.site-navbar__mobile-group:nth-child(5) { animation-delay: 0.25s; }
```

Expected: Each group gets incremental delay

- [ ] **Step 3: Add slideIn keyframes**

Find `@keyframes edith-pulse` at line ~492 and insert before it:

```css
@keyframes slideIn {
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
```

Expected: Keyframes added

- [ ] **Step 4: Verify CSS**

```bash
grep -A 5 "slideIn" static/css/index.css
```

Expected: See keyframes and nth-child rules

- [ ] **Step 5: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add staggered animations for mobile menu items

- Menu items cascade in with incremental delays
- Each item slides in from right with fade
- Creates smooth, polished mobile navigation experience"
```

---

## Task 5: Add Active Underline Indicator

**Files:**
- Modify: `static/css/index.css` (line ~156)

- [ ] **Step 1: Add position relative to nav link**

Find `.site-navbar__link {` at line ~156 and add `position: relative;`:

```css
.site-navbar__link {
  text-decoration: none;
  color: var(--text);
  font-size: 0.95rem;
  font-weight: 600;
  line-height: 1.1;
  border-radius: var(--radius-sm);
  padding: 0.6rem 0.75rem;
  transition: background var(--motion-fast) ease, color var(--motion-fast) ease;
  position: relative;
}
```

Expected: Link has position relative

- [ ] **Step 2: Add underline pseudo-element**

Insert after `.site-navbar__link` rule:

```css
.site-navbar__link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: var(--nav-underline-height);
  background: var(--nav-underline-color);
  transition: width var(--nav-dropdown-transition) ease-out;
}
```

Expected: Pseudo-element added

- [ ] **Step 3: Add current state for underline**

Find `.site-navbar__link.is-current {` at line ~169 and add the pseudo-element state:

```css
.site-navbar__link.is-current::after {
  width: 100%;
}
```

Expected: Current state expands underline

- [ ] **Step 4: Verify CSS**

```bash
grep -B 3 -A 6 "site-navbar__link::after" static/css/index.css
```

Expected: See pseudo-element styles

- [ ] **Step 5: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add animated underline indicator for active nav item

- Add ::after pseudo-element for underline
- Smooth width transition on current page
- Radix-style visual indicator for active section"
```

---

## Task 6: Add Touch Device Detection

**Files:**
- Modify: `static/js/navbar.js` (after line 6)

- [ ] **Step 1: Add touch device detection function**

Find `var MOBILE_MENU_OPEN_CLASS = "mobile-menu-open";` at line 6 and insert after:

```javascript
var MOBILE_MENU_OPEN_CLASS = "mobile-menu-open";

function isTouchDevice() {
  return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}
```

Expected: Function added at module level

- [ ] **Step 2: Verify JavaScript syntax**

```bash
node -c static/js/navbar.js
```

Expected: No syntax errors

- [ ] **Step 3: Commit**

```bash
git add static/js/navbar.js
git commit -m "feat: add touch device detection function

- Detect touch devices via ontouchstart or maxTouchPoints
- Will be used to conditionally enable hover triggers
- Prevents hover interactions on touch-only devices"
```

---

## Task 7: Add Hover Trigger Logic for Desktop

**Files:**
- Modify: `static/js/navbar.js` (before initNavbar at line ~505)

- [ ] **Step 1: Add bindHoverTriggers function**

Find `function initNavbar() {` at line ~505 and insert this function before it:

```javascript
function bindHoverTriggers(navRoot) {
  if (isTouchDevice()) {
    return;
  }

  navRoot.querySelectorAll('[data-dropdown-item="true"]').forEach(function(item) {
    var trigger = item.querySelector('[data-dropdown-trigger="true"]');
    if (!trigger) {
      return;
    }

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

Expected: Function added before initNavbar

- [ ] **Step 2: Call bindHoverTriggers in initNavbar**

Find `bindNavigation(navRoot);` inside initNavbar and add after it:

```javascript
  bindNavigation(navRoot);
  bindHoverTriggers(navRoot);
  bindStickyHeaderState();
```

Expected: Hover triggers now bound on desktop

- [ ] **Step 3: Verify JavaScript syntax**

```bash
node -c static/js/navbar.js
```

Expected: No syntax errors

- [ ] **Step 4: Commit**

```bash
git add static/js/navbar.js
git commit -m "feat: add hover-triggered dropdowns for desktop

- Add bindHoverTriggers function with mouseenter/mouseleave
- Skip on touch devices (click-only for mobile)
- Maintain click handlers for accessibility
- Dropdowns now open smoothly on hover (desktop only)"
```

---

## Task 8: Manual Testing and Verification

**Files:**
- None (testing only)

- [ ] **Step 1: Start local server**

```bash
python3 -m http.server 8000
```

Expected: Server starts on http://localhost:8000

- [ ] **Step 2: Test homepage loads**

Open: http://localhost:8000/
Expected: Homepage loads without errors, content visible

- [ ] **Step 3: Test mobile drawer animation**

1. Open DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Select mobile device (e.g., iPhone 12)
4. Click hamburger menu

Expected: Drawer slides in from right with backdrop blur, menu items cascade in with staggered animation

- [ ] **Step 4: Test desktop hover triggers**

1. Switch to desktop view in DevTools
2. Hover over "Conoce al equipo" nav item

Expected: Dropdown fades and slides in smoothly (200ms)

- [ ] **Step 5: Test active underline**

Navigate between pages and observe nav bar
Expected: Underline shows on current page's top-level item with smooth width animation

- [ ] **Step 6: Test keyboard navigation**

1. Press Tab to focus nav items
2. Press Enter on dropdown trigger
3. Press Escape to close

Expected: All keyboard interactions work, focus visible

- [ ] **Step 7: Test touch device fallback**

1. Use Chrome DevTools to emulate touch device
2. Try hovering over nav items

Expected: No hover triggers (click-only behavior)

- [ ] **Step 8: Test backdrop blur on mobile**

On mobile view, observe drawer background
Expected: Frosted glass blur effect visible behind drawer

- [ ] **Step 9: Test Safari compatibility**

If available, test in Safari for backdrop-filter
Expected: Backdrop blur works with -webkit- prefix

- [ ] **Step 10: Stop server**

Press Ctrl+C in terminal
Expected: Server stops

- [ ] **Step 11: Final commit**

```bash
git add -A
git commit -m "test: complete manual testing verification

All tests passed:
- Homepage loads at /
- Mobile drawer slides with backdrop blur and staggered animations
- Desktop hover triggers work smoothly
- Active underline indicator shows correctly
- Keyboard navigation functional
- Touch devices use click-only mode
- Backdrop blur works in Safari
- All CSS and JavaScript changes verified"
```

---

## Success Criteria Verification

After completing all tasks:

- [ ] Homepage accessible at `/` without 404 errors
- [ ] All navigation links work correctly across all pages
- [ ] Mobile drawer slides in from right with backdrop blur
- [ ] Desktop dropdowns open on hover with smooth animations
- [ ] Active underline indicator shows on current page
- [ ] Keyboard navigation works (Tab, Enter, Escape)
- [ ] Touch devices fall back to click interactions
- [ ] All browsers render correctly (Chrome, Firefox, Safari, Edge)
- [ ] WCAG 2.1 AA accessibility maintained
- [ ] No JavaScript errors in console
- [ ] All references to `quienes-somos.html` updated

---

## Rollback Plan

If issues arise:

```bash
# Reset to before changes
git log --oneline | head -1  # Note the commit hash
git reset --hard <commit-hash>

# Or rollback specific files
git checkout HEAD~1 -- filename
```
