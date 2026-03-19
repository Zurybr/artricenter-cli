# Homepage Rename & Mobile-First Navigation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rename `quienes-somos.html` to `index.html` and enhance navigation with mobile-first design, hover-triggered dropdowns, animated underline indicator, and smooth drawer animations.

**Architecture:** Mobile-first CSS with progressive enhancement for desktop. Touch device detection in JavaScript to conditionally enable hover triggers while maintaining click-based accessibility. CSS transitions for all animations with fallbacks for reduced motion preferences.

**Tech Stack:** Vanilla JavaScript (ES5), CSS3 (custom properties, transitions, transforms), HTML5

---

## File Structure

**Files to create:**
- None (all modifications to existing files)

**Files to modify:**
1. `quienes-somos.html` → `index.html` (rename + internal SEO metadata)
2. `static/js/nav-config.js` (update page references)
3. `static/js/navbar.js` (update references + add hover logic)
4. `static/css/index.css` (add navigation animation styles)
5. `contactanos.html` (update link reference)

---

## Task 1: Rename File and Update Internal References

**Files:**
- Rename: `quienes-somos.html` → `index.html`
- Modify: `index.html` (renamed file)

- [ ] **Step 1: Rename the file**

```bash
git mv quienes-somos.html index.html
```

Expected: File renamed successfully, no errors

- [ ] **Step 2: Update canonical URL in index.html**

```bash
sed -i 's|https://artricenter.com/quienes-somos.html|https://artricenter.com/|g' index.html
```

Expected: Line 12 updated to `<link rel="canonical" href="https://artricenter.com/" />`

- [ ] **Step 3: Update OG URL in index.html**

```bash
sed -i 's|https://artricenter.com/quienes-somos.html|https://artricenter.com/|g' index.html
```

Expected: Line 21 updated to `<meta property="og:url" content="https://artricenter.com/" />`

- [ ] **Step 4: Update JSON-LD URL in index.html**

```bash
sed -i 's|"url": "https://artricenter.com/quienes-somos.html"|"url": "https://artricenter.com/"|g' index.html
```

Expected: Line 34 updated with new URL

- [ ] **Step 5: Verify file loads in browser**

Open: `file:///home/zurybr/workspaces/artricenter/index.html`
Expected: Page loads without 404 errors, all content visible

- [ ] **Step 6: Commit**

```bash
git add index.html
git commit -m "feat: rename quienes-somos.html to index.html for proper homepage

- Update canonical URL to root
- Update Open Graph URL to root
- Update JSON-LD structured data URL
- Establish / as proper homepage for SEO"
```

---

## Task 2: Update Navigation Config References

**Files:**
- Modify: `static/js/nav-config.js`

- [ ] **Step 1: Update all page references in nav-config.js**

```bash
sed -i 's|"page": "quienes-somos.html"|"page": "index.html"|g' static/js/nav-config.js
```

Expected: All 5 occurrences updated (lines 17, 21, 26, 31, 36)

- [ ] **Step 2: Verify changes**

```bash
grep -n "index.html" static/js/nav-config.js
```

Expected output:
```
17:      page: "index.html",
21:          page: "index.html",
26:          page: "index.html",
31:          page: "index.html",
36:          page: "index.html",
```

- [ ] **Step 3: Test navigation config loads**

```bash
node -e "console.log(require('./static/js/nav-config.js'))"
```

Expected: No syntax errors, config object logged

- [ ] **Step 4: Commit**

```bash
git add static/js/nav-config.js
git commit -m "feat: update nav-config references to index.html

- Update all 5 page references from quienes-somos.html to index.html
- Navigation config now points to new homepage"
```

---

## Task 3: Update Navbar References

**Files:**
- Modify: `static/js/navbar.js`

- [ ] **Step 1: Update default page on line 11**

```bash
sed -i 's|return page || "quienes-somos.html";|return page || "index.html";|g' static/js/navbar.js
```

Expected: Line 11 updated

- [ ] **Step 2: Update default page on line 78**

```bash
sed -i 's|var targetPage = page || "quienes-somos.html";|var targetPage = page || "index.html";|g' static/js/navbar.js
```

Expected: Line 78 updated

- [ ] **Step 3: Update brand link on line 243**

```bash
sed -i 's|brand.href = "quienes-somos.html";|brand.href = "index.html";|g' static/js/navbar.js
```

Expected: Line 243 updated

- [ ] **Step 4: Verify no remaining references**

```bash
grep -n "quienes-somos" static/js/navbar.js
```

Expected: No matches found

- [ ] **Step 5: Test navbar script loads**

```bash
node -c static/js/navbar.js
```

Expected: No syntax errors

- [ ] **Step 6: Commit**

```bash
git add static/js/navbar.js
git commit -m "feat: update navbar.js references to index.html

- Update default page fallback on line 11
- Update default page on line 78
- Update brand logo link on line 243
- All references now point to index.html"
```

---

## Task 4: Add CSS Variables for Navigation Animations

**Files:**
- Modify: `static/css/index.css`

- [ ] **Step 1: Add navigation animation variables after line 26**

Find line 26 (`--focus-ring: ...`) and insert after it:

```css
  --nav-dropdown-transition: 200ms ease-out;
  --nav-drawer-transition: 300ms cubic-bezier(0.4, 0, 0.2, 1);
  --nav-underline-height: 2px;
  --nav-underline-color: var(--brand-blue);
  --nav-touch-target: 44px;
  --nav-backdrop-blur: 8px;
```

Expected: Variables added to `:root` selector

- [ ] **Step 2: Verify CSS syntax**

```bash
cat static/css/index.css | head -35
```

Expected: See new variables in output

- [ ] **Step 3: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add CSS variables for navigation animations

- Add dropdown transition timing
- Add drawer transition with cubic-bezier easing
- Add underline indicator variables
- Add touch target and backdrop blur variables"
```

---

## Task 5: Add Dropdown Animation CSS

**Files:**
- Modify: `static/css/index.css`

- [ ] **Step 1: Update dropdown base styles (line ~176)**

Find `.site-navbar__dropdown {` and update the rule:

```css
.site-navbar__dropdown {
  position: absolute;
  top: calc(100% + 0.45rem);
  right: 0;
  min-width: 260px;
  max-width: 320px;
  background: var(--surface);
  border: 1px solid var(--line);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-soft);
  padding: var(--space-3);
  opacity: 0;
  transform: translateY(-8px);
  transition: opacity var(--nav-dropdown-transition) ease-out,
              transform var(--nav-dropdown-transition) ease-out;
}
```

Expected: Dropdown now has opacity and transform with transition

- [ ] **Step 2: Add open state for dropdown**

Insert after `.site-navbar__dropdown` rule:

```css
.site-navbar__item.is-open > .site-navbar__dropdown {
  opacity: 1;
  transform: translateY(0);
}
```

Expected: New rule added for open state

- [ ] **Step 3: Verify CSS syntax**

```bash
cat static/css/index.css | grep -A 15 "site-navbar__dropdown"
```

Expected: See both rules with proper transitions

- [ ] **Step 4: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add smooth dropdown animations

- Add opacity and transform transitions to dropdown
- Add open state with full opacity and reset transform
- Dropdowns now fade and slide in smoothly"
```

---

## Task 6: Add Mobile Drawer Animation CSS

**Files:**
- Modify: `static/css/index.css`

- [ ] **Step 1: Update mobile drawer base styles (line ~243)**

Find `.site-navbar__drawer {` and update:

```css
.site-navbar__drawer {
  position: fixed;
  top: var(--nav-height);
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(10, 22, 40, 0.48);
  transform: translateX(100%);
  transition: transform var(--nav-drawer-transition),
              opacity var(--nav-drawer-transition);
}
```

Expected: Drawer has transform translateX and transition

- [ ] **Step 2: Update open state for drawer (line ~255)**

Find `.site-navbar.mobile-menu-open .site-navbar__drawer {` and update:

```css
.site-navbar.mobile-menu-open .site-navbar__drawer {
  transform: translateX(0);
  opacity: 1;
}
```

Expected: Open state resets transform

- [ ] **Step 3: Add backdrop blur to drawer (line ~249)**

Find `background: rgba(10, 22, 40, 0.48);` and add backdrop-filter after it:

```css
  background: rgba(10, 22, 40, 0.48);
  backdrop-filter: blur(var(--nav-backdrop-blur));
  -webkit-backdrop-filter: blur(var(--nav-backdrop-blur));
```

Expected: Backdrop blur added with vendor prefix

- [ ] **Step 4: Verify CSS syntax**

```bash
cat static/css/index.css | grep -A 10 "site-navbar__drawer"
```

Expected: See backdrop-filter and transform properties

- [ ] **Step 5: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add slide-in animation for mobile drawer

- Add translateX transform for slide-in effect
- Add backdrop blur for modern frosted glass effect
- Smooth 300ms cubic-bezier transition
- Mobile drawer now slides smoothly from right"
```

---

## Task 7: Add Staggered Menu Item Animations

**Files:**
- Modify: `static/css/index.css`

- [ ] **Step 1: Find mobile group styles (line ~269)**

Add these styles after `.site-navbar__mobile-group {`:

```css
.site-navbar__mobile-group {
  border-bottom: 1px solid var(--line);
  padding: var(--space-2) 0;
  opacity: 0;
  transform: translateX(20px);
  animation: slideIn 0.3s ease-out forwards;
}

.site-navbar__mobile-group:nth-child(1) { animation-delay: 0.05s; }
.site-navbar__mobile-group:nth-child(2) { animation-delay: 0.1s; }
.site-navbar__mobile-group:nth-child(3) { animation-delay: 0.15s; }
.site-navbar__mobile-group:nth-child(4) { animation-delay: 0.2s; }
.site-navbar__mobile-group:nth-child(5) { animation-delay: 0.25s; }
```

Expected: Each group gets staggered delay

- [ ] **Step 2: Add slideIn keyframes at end of file**

Add before the media queries:

```css
@keyframes slideIn {
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
```

Expected: Keyframes added

- [ ] **Step 3: Verify CSS syntax**

```bash
cat static/css/index.css | grep -A 20 "@keyframes slideIn"
```

Expected: See keyframes definition

- [ ] **Step 4: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add staggered animations for mobile menu items

- Menu items cascade in with incremental delays
- Each item slides in from right with fade
- Creates smooth, polished mobile navigation experience"
```

---

## Task 8: Add Active Underline Indicator

**Files:**
- Modify: `static/css/index.css`

- [ ] **Step 1: Add underline base styles (find .site-navbar__link around line 116)**

Add after `.site-navbar__link` rule:

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
```

Expected: Link has position relative, ::after pseudo-element added

- [ ] **Step 2: Update current state for underline (find .is-current around line 129)**

Update the rule:

```css
.site-navbar__link:hover,
.site-navbar__link:focus-visible,
.site-navbar__link.is-current {
  background: rgba(18, 74, 142, 0.11);
  color: var(--brand-blue);
  outline: none;
}

.site-navbar__link.is-current::after {
  width: 100%;
}
```

Expected: Current state now expands underline

- [ ] **Step 3: Verify CSS syntax**

```bash
cat static/css/index.css | grep -A 5 "site-navbar__link::after"
```

Expected: See pseudo-element styles

- [ ] **Step 4: Commit**

```bash
git add static/css/index.css
git commit -m "feat: add animated underline indicator for active nav item

- Add ::after pseudo-element for underline
- Smooth width transition on current page
- Radix-style visual indicator for active section"
```

---

## Task 9: Add Touch Device Detection to Navbar

**Files:**
- Modify: `static/js/navbar.js`

- [ ] **Step 1: Add touch device detection function (after line 7)**

Find `var MOBILE_MENU_OPEN_CLASS = "mobile-menu-open";` and insert after:

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

## Task 10: Add Hover Trigger Logic for Desktop

**Files:**
- Modify: `static/js/navbar.js`

- [ ] **Step 1: Add bindHoverTriggers function (before initNavbar around line 505)**

Insert this new function:

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

Expected: Function added

- [ ] **Step 2: Call bindHoverTriggers in initNavbar (find line ~513)**

Find `bindNavigation(navRoot);` and add after it:

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

## Task 11: Update Contactanos Link Reference

**Files:**
- Modify: `contactanos.html`

- [ ] **Step 1: Update quienes-somos link to index**

```bash
sed -i 's|href="quienes-somos.html"|href="index.html"|g' contactanos.html
```

Expected: Link updated on line 64

- [ ] **Step 2: Verify change**

```bash
grep -n "index.html" contactanos.html
```

Expected: Shows the updated link

- [ ] **Step 3: Test page loads**

Open: `file:///home/zurybr/workspaces/artricenter/contactanos.html`
Expected: Page loads, "Conoce al equipo medico" link points to index.html

- [ ] **Step 4: Commit**

```bash
git add contactanos.html
git commit -m "feat: update contactanos link to index.html

- Change 'Conoce al equipo medico' link from quienes-somos.html to index.html
- All internal links now point to new homepage"
```

---

## Task 12: Manual Testing and Verification

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

- [ ] **Step 3: Test mobile navigation**

1. Open DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Select mobile device (e.g., iPhone 12)
4. Click hamburger menu

Expected: Drawer slides in from right with backdrop blur

- [ ] **Step 4: Test desktop hover triggers**

1. Switch to desktop view in DevTools
2. Hover over "Conoce al equipo" nav item

Expected: Dropdown fades and slides in smoothly

- [ ] **Step 5: Test active underline**

Navigate between pages and observe nav bar
Expected: Underline shows on current page's top-level item

- [ ] **Step 6: Test keyboard navigation**

1. Press Tab to focus nav items
2. Press Enter on dropdown trigger
3. Press Escape to close

Expected: All keyboard interactions work

- [ ] **Step 7: Test touch device fallback**

1. Use Chrome DevTools to emulate touch device
2. Try hovering over nav items

Expected: No hover triggers (click-only behavior)

- [ ] **Step 8: Stop server**

Press Ctrl+C in terminal
Expected: Server stops

- [ ] **Step 9: Final commit**

```bash
git add -A
git commit -m "test: complete manual testing verification

All tests passed:
- Homepage loads at /
- Mobile drawer slides smoothly with backdrop blur
- Desktop hover triggers work
- Active underline indicator shows correctly
- Keyboard navigation functional
- Touch devices use click-only mode
- All links reference index.html correctly"
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
