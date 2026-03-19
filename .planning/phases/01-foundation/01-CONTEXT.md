# Phase 1: Foundation - Context

**Gathered:** 2026-03-19
**Status:** Ready for planning

<domain>
## Phase Boundary

Establish Docker development environment and structure plugin providing site-wide layout (header, footer, navigation), global CSS foundation with Tailwind-to-pure-CSS conversion, and WordPress hooks for plugin integration. This is the infrastructure phase — all subsequent plugins depend on this foundation.

</domain>

<decisions>
## Implementation Decisions

### Docker Environment
- **WordPress version**: 6.9.4 with PHP 8.2 (Apache variant) — current stable with official PHP 8.2 support
- **Database**: MySQL 8.0 (official mysql image) — matches production requirements
- **WP-CLI included**: As separate container service for efficient WordPress management
- **Volume mounting**: Plugin directories mounted for hot-reload development
- **Local URL**: http://localhost:8080 (port 8080 to avoid conflicts)
- **Persistent data**: MySQL data volume for database persistence across container restarts

### Plugin Structure
- **Single structure plugin**: `artricenter-structure` — contains header, footer, navigation, global CSS, and hooks
- **Rationale**: All components depend on each other; splitting would create artificial dependencies
- **Plugin header**: Standard WordPress plugin header with description, version, author
- **File organization**:
  - `artricenter-structure.php` — main plugin file
  - `includes/class-header.php` — header component
  - `includes/class-footer.php` — footer component
  - `includes/class-navigation.php` — navigation component
  - `includes/class-hooks.php` — hook registration
  - `assets/css/` — converted CSS files
  - `assets/js/` — JavaScript (mobile menu)

### CSS Conversion Strategy
- **Namespace prefix**: `.artricenter-` on ALL CSS classes to prevent conflicts with WordPress themes and plugins
- **Conversion approach**: Manual conversion from Tailwind utilities to semantic CSS
  - Example: `flex items-center justify-between` → `.artricenter-header { display: flex; align-items: center; justify-content: space-between; }`
  - Example: `bg-blue-600 text-white px-4 py-3` → `.artricenter-btn-primary { background-color: #2563eb; color: white; padding: 1rem 1.5rem; }`
- **CSS variables**: Define design tokens (colors, spacing, fonts) as CSS variables for consistency
- **Responsive breakpoints**: Convert Tailwind breakpoints to media queries (`@media (min-width: 1024px)`)
- **Smooth scroll**: Preserve `scroll-behavior: smooth` from existing global.css
- **File structure**:
  - `assets/css/main.css` — main stylesheet with all converted styles
  - `assets/css/variables.css` — CSS variables for design tokens
  - Enqueue via `wp_enqueue_style` in WordPress

### Header Component
- **Desktop header**: Logo left, horizontal navigation right (hidden on mobile, visible on lg+ breakpoint)
- **Mobile header**: Hamburger menu left, "Artricenter" text centered, spacer right (14px height)
- **Mobile navigation**: Full-screen overlay slide-in from right with backdrop blur
- **Logo**: `/assets/logo.png` from current site (will need to be copied to WordPress)
- **Sticky behavior**: Header sticks to top with `position: sticky; top: 0; z-index: 50`
- **WordPress integration**: Use template tag `artricenter_get_header()` in theme, not automatic injection

### Footer Component
- **Three sucursales cards**: La Raza (blue), Atizapán (green), Viaducto (orange) — matching current site colors
- **Card structure**: Icon, name, address, phone, Google Maps button
- **Info section**: Horarios (Martes-Sábado 8:00-17:00), Contacto (phone), Síguenos (social links)
- **Responsive**: 3-column grid on desktop, stacked on mobile
- **WordPress integration**: Use template tag `artricenter_get_footer()` in theme

### Navigation
- **Desktop**: Horizontal menu with dropdown for children (if any)
- **Mobile**: Full-screen overlay with slide-in animation, submenu accordions
- **Current nav items**: Inicio, Especialidades, Tratamiento, Club Vida y Salud, Contacto
- **External links**: Blog link opens in new tab
- **Active state**: Highlight current page in navigation
- **WordPress menus**: Register custom navigation location via `register_nav_menu()`

### WordPress Hooks
- **Hook registration**: Register custom hooks for other plugins to inject content
  - `do_action('artricenter_before_content')` — before main content area
  - `do_action('artricenter_after_content')` — after main content area
- **Priority**: Run hooks on `wp` hook with priority 10
- **Documentation**: Add PHPDoc comments for hook usage

### JavaScript (Mobile Menu)
- **Mobile menu toggle**: Hamburger button opens overlay, close button/backdrop closes it
- **Submenu accordions**: Tap to expand/collapse submenus in mobile menu
- **Accessibility**:
  - ARIA attributes (aria-expanded, aria-label)
  - Escape key closes menu
  - Trap focus when menu is open (basic implementation)
- **Enqueue**: `wp_enqueue_script` with dependency on jQuery (WordPress includes jQuery by default)

### Claude's Discretion
- Exact CSS specificity and selector organization (use BEM or utility-first within namespace)
- Mobile menu animation timing and easing functions
- Header/footer HTML markup details (semantic HTML5 tags, ARIA roles)
- WordPress template hierarchy integration (which template files to modify)

</decisions>

<specifics>
## Specific Ideas

**Reference implementations:**
- Mobile menu should match current Astro site behavior: slide-in from right, backdrop blur, smooth animations
- Footer card colors: La Raza = blue-600 (#2563eb), Atizapán = green-600 (#16a34a), Viaducto = orange-600 (#ea580c)
- Header sticky: `position: sticky; top: 0; z-index: 50; box-shadow: 0 1px 3px rgba(0,0,0,0.1)`

**From current Astro site:**
- Smooth scroll: `html { scroll-behavior: smooth; scroll-padding-top: 5rem; }`
- Mobile overlay: Right-aligned slide-in panel with `max-width: 28rem` (448px)
- Mobile menu button: Left-aligned on mobile with `-ml-3` offset

**Navigation config (from src/config/navigation.ts):**
- Inicio (/)
- Especialidades (/especialidades)
- Tratamiento Médico Integral (/tratamiento-medico-integral)
- Club Vida y Salud (/club-vida-y-salud)
- Contacto (/contactanos)

</specifics>

<code_context>
## Existing Code Insights

### Reusable Assets
- **Header component** (`src/components/Header.astro`): Full implementation with mobile overlay menu, desktop navigation, sticky behavior
  - Mobile menu JavaScript: toggle logic, submenu accordions, accessibility features (aria attributes, escape key)
  - Desktop: Logo + horizontal nav
  - Mobile: Hamburger menu + centered "Artricenter" text + slide-in overlay
- **Footer component** (`src/components/Footer.astro`): 3 sucursales cards with color-coded icons, addresses, phone, Google Maps links
- **Navigation config** (`src/config/navigation.ts`): Centralized navigation structure with labels, pages, children, external links
- **Global CSS** (`src/styles/global.css`): Smooth scroll behavior with scroll-padding-top

### Established Patterns
- **Component-based architecture**: Astro uses separate components for Header, Footer, Navigation — WordPress will mirror this with separate PHP classes
- **Configuration-driven navigation**: Navigation items defined in config file — WordPress should use `register_nav_menu()` and wp_nav_menu() for admin-manageable menus
- **Mobile-first responsive**: Tailwind mobile-first breakpoints (default mobile, `lg:` for desktop) — convert to CSS media queries
- **Progressive enhancement**: JavaScript enhances mobile menu but basic functionality works without JS (keyboard navigation)

### Integration Points
- **WordPress theme templates**: Header/footer will be called via template tags in theme's `header.php` and `footer.php`
- **WordPress hooks system**: Use `wp_head`, `wp_footer`, `wp_enqueue_scripts` for proper integration
- **WordPress menu system**: Register nav menu location, use `wp_nav_menu()` to output navigation
- **Plugin activation hook**: Flush rewrite rules on activation, register default menu on first activation

</code_context>

<deferred>
## Deferred Ideas

None — discussion stayed within phase scope.

</deferred>

---

*Phase: 01-foundation*
*Context gathered: 2026-03-19*
