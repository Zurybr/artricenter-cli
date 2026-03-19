# Architecture Research

**Domain:** WordPress Plugin Development (Modular Architecture)
**Researched:** 2026-03-19
**Confidence:** HIGH

## Standard Architecture

### System Overview

WordPress plugins follow a **hook-based architecture** where functionality is injected into WordPress execution through actions and filters. For Artricenter's modular migration, this means:

```
┌─────────────────────────────────────────────────────────────┐
│                    WordPress Core                            │
│  (Request Handling, Database, REST API, Admin UI)           │
└────────────┬────────────────────────────────────────────────┘
             │
             │ Hooks System (Actions & Filters)
             │
┌────────────┴────────────────────────────────────────────────┐
│                  Plugin Layer                                │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │ Structure    │  │ Content      │  │ Interactive  │       │
│  │ Plugin       │  │ Plugins      │  │ Plugins      │       │
│  │              │  │              │  │              │       │
│  │ • Header     │  │ • CPTs       │  │ • Forms      │       │
│  │ • Footer     │  │ • Doctors    │  │ • Shortcodes │       │
│  │ • Nav        │  │ • Locations  │  │ • AJAX       │       │
│  │ • Styles     │  │              │  │              │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
└────────────┬────────────────────────────────────────────────┘
             │
             │ Template Tags, Functions, Shortcodes
             │
┌────────────┴────────────────────────────────────────────────┐
│                   Theme Layer                                │
│  (Templates - header.php, footer.php, single.php, etc.)     │
└─────────────────────────────────────────────────────────────┘
```

### Component Responsibilities

| Component | Responsibility | Typical Implementation |
|-----------|----------------|------------------------|
| **Structure Plugin** | Site-wide layout, navigation, global styles | Hooks into `wp_head`, `wp_footer`, registers nav menus, enqueues CSS |
| **CPT Plugins** | Content type definitions and management | `register_post_type()`, custom meta boxes, template overrides |
| **Form Plugins** | User interaction, data submission | Shortcodes for display, AJAX handlers, admin settings pages |
| **Theme** | Visual presentation, templates | PHP template files, template tags, CSS (minimal logic) |
| **WordPress Core** | Request handling, database, authentication | Built-in APIs (Options, Metadata, Transients, HTTP) |

## Recommended Project Structure

For Artricenter's modular WordPress migration, the recommended plugin structure:

```
artricenter-plugins/
├── artricenter-structure/           # Site structure & layout
│   ├── artricenter-structure.php    # Main plugin file (header comment)
│   ├── includes/
│   │   ├── class-header.php         # Header rendering logic
│   │   ├── class-footer.php         # Footer rendering logic
│   │   ├── class-navigation.php     # Navigation menu handling
│   │   └── class-enqueue.php        # CSS/JS asset loading
│   ├── assets/
│   │   ├── css/
│   │   │   └── header.css           # Header-specific styles
│   │   └── js/
│   │       └── mobile-menu.js       # Mobile navigation JS
│   └── templates/
│       └── header-custom.php        # Custom header template
│
├── artricenter-doctors/             # Custom Post Type: Doctors
│   ├── artricenter-doctors.php
│   ├── includes/
│   │   ├── class-cpt-doctors.php    # CPT registration
│   │   ├── class-meta-boxes.php     # Doctor metadata fields
│   │   └── class-template-loader.php # Custom template overrides
│   ├── templates/
│   │   ├── single-doctor.php        # Single doctor view
│   │   └── archive-doctor.php       # Doctor listing
│   └── assets/
│       └── css/
│           └── doctor-card.css      # Doctor card styling
│
├── artricenter-specialties/         # Custom Post Type: Specialties
│   ├── artricenter-specialties.php
│   ├── includes/
│   │   ├── class-cpt-specialties.php
│   │   └── class-shortcodes.php      # [specialty_grid] shortcode
│   └── templates/
│       └── single-specialty.php
│
├── artricenter-locations/           # Custom Post Type: Locations
│   ├── artricenter-locations.php
│   ├── includes/
│   │   ├── class-cpt-locations.php
│   │   └── class-meta-boxes.php      # Address, phone, hours
│   └── templates/
│       └── single-location.php
│
└── artricenter-forms/               # Contact forms & CTAs
    ├── artricenter-forms.php
    ├── includes/
    │   ├── class-contact-form.php    # Form rendering & validation
    │   ├── class-ajax-handler.php    # AJAX form submission
    │   ├── class-sticky-buttons.php  # WhatsApp & Dra. Edith CTAs
    │   └── class-admin-settings.php  # Form configuration
    └── assets/
        └── js/
            └── form-submission.js
```

### Structure Rationale

- **`artricenter-structure/`:** Foundation plugin - defines site layout, navigation, and global styles. Other plugins depend on this.
- **`artricenter-doctors/`, `artricenter-specialties/`, `artricenter-locations/`:** Content-focused plugins, each owns its CPT, metadata, and display logic.
- **`artricenter-forms/`:** Interaction layer - handles user input, sticky buttons, and AJAX. Dependent on structure plugin for placement.
- **`includes/` folder:** Each plugin organizes PHP classes by responsibility (single-responsibility principle).
- **`templates/` folder:** Custom templates that WordPress will auto-discover via template hierarchy.
- **`assets/` folder:** Plugin-specific CSS/JS (not global - keeps concerns separated).

## Architectural Patterns

### Pattern 1: Single-Responsibility Plugins

**What:** Each plugin handles ONE distinct feature (e.g., doctors CPT, forms, site structure).

**When to use:** For any modular WordPress project where features need independent updates.

**Trade-offs:**
- **Pros:** Easy to maintain, test, deactivate individual features; clear boundaries
- **Cons:** More boilerplate; requires coordination between plugins

**Example:**
```php
<?php
/**
 * Plugin Name: Artricenter Doctors
 * Description: Custom Post Type for doctors/médicos
 * Version: 1.0.0
 */

// Prevent direct access
defined( 'ABSPATH' ) || exit;

class Artricenter_Doctors {
    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'register_cpt' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
    }

    public function register_cpt() {
        register_post_type( 'doctor', [
            'labels' => [ 'name' => 'Doctores' ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-id-alt',
            'supports' => [ 'title', 'thumbnail', 'editor' ],
        ] );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'doctor_details',
            'Información del Doctor',
            [ $this, 'render_meta_box' ],
            'doctor'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'doctor_details_nonce', 'doctor_details_nonce' );
        // Render meta box fields
    }
}

// Initialize
Artricenter_Doctors::get_instance();
```

### Pattern 2: Hook-Based Integration (Actions & Filters)

**What:** Plugins interact with WordPress and each other through hooks, not direct calls.

**When to use:** Whenever you need to modify WordPress behavior or other plugins' output.

**Trade-offs:**
- **Pros:** Decoupled, extensible, WordPress standard
- **Cons:** Indirection makes debugging harder; hook priority matters

**Example - Action Hook:**
```php
// Structure plugin registers a hook for others to use
do_action( 'artricenter_before_footer' );

// Forms plugin hooks into it to add sticky buttons
add_action( 'artricenter_before_footer', [ $this, 'render_sticky_buttons' ], 10 );
```

**Example - Filter Hook:**
```php
// Doctors plugin provides doctor data for header
apply_filters( 'artricenter_doctor_data', $doctor_data );

// Structure plugin modifies it for display
add_filter( 'artricenter_doctor_data', [ $this, 'format_doctor_for_header' ] );
```

### Pattern 3: Shortcode-Based Content Injection

**What:** Plugins provide shortcodes that themes/pages use to render dynamic content.

**When to use:** When content editors need control over where plugin features appear.

**Trade-offs:**
- **Pros:** Editor-friendly, flexible placement
- **Cons:** Shortcodes in content create lock-in; harder to maintain

**Example:**
```php
// Specialties plugin registers shortcode
add_shortcode( 'specialty_grid', [ $this, 'render_specialty_grid' ] );

public function render_specialty_grid( $atts ) {
    $atts = shortcode_atts( [
        'limit' => 4,
        'columns' => 2,
    ], $atts );

    $specialties = get_posts( [
        'post_type' => 'specialty',
        'posts_per_page' => $atts['limit'],
    ] );

    ob_start();
    // Render grid HTML
    return ob_get_clean();
}

// Usage in page editor: [specialty_grid limit="6" columns="3"]
```

### Pattern 4: Template Hierarchy Override

**What:** Plugins provide custom templates that WordPress automatically uses for CPTs.

**When to use:** When you need full control over how a CPT displays (single/archive pages).

**Trade-offs:**
- **Pros:** WordPress-standard, theme-friendly
- **Cons:** Requires theme cooperation (fallback to theme templates)

**Example:**
```php
// In artricenter-doctors plugin
function load_doctor_templates( $template ) {
    if ( is_singular( 'doctor' ) ) {
        $plugin_template = plugin_dir_path( __FILE__ ) . 'templates/single-doctor.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter( 'template_include', [ $this, 'load_doctor_templates' ] );
```

## Data Flow

### Request Flow

```
[User Request: /doctor/dr-edith]
    ↓
[WordPress Core: Parse URL, Query Database]
    ↓
[Template Loader: Finds single-doctor.php in plugin]
    ↓
[Structure Plugin: Hooks wp_head, wp_footer for layout]
    ↓
[Doctors Plugin: Renders doctor CPT content via template]
    ↓
[Forms Plugin: Injects sticky buttons via artricenter_before_footer hook]
    ↓
[Response: Complete HTML page sent to browser]
```

### Plugin Activation/Deactivation Flow

```
[Admin clicks "Activate Plugin"]
    ↓
[register_activation_hook() → Plugin setup function]
    ↓
[Create database tables, set options, register CPTs]
    ↓
[Flush rewrite rules to recognize new CPT URLs]
    ↓
[Plugin ready, hooks registered in init]
```

### Form Submission Flow (AJAX)

```
[User fills contact form, clicks submit]
    ↓
[JavaScript: Captures submit event, prevents default]
    ↓
[AJAX Request: POST to wp-admin/admin-ajax.php]
    ↓
[WordPress: Routes to registered AJAX handler]
    ↓
[Forms Plugin: Validates nonce, sanitizes input]
    ↓
[Forms Plugin: Processes data (email, database, etc.)]
    ↓
[JSON Response: Success/error back to JavaScript]
    ↓
[JavaScript: Updates UI, shows confirmation]
```

### Key Data Flows

1. **Navigation Rendering:** Configuration in `navigation.php` → Header plugin reads via `wp_get_nav_menu_items()` → Theme template renders
2. **CPT Display:** Plugin registers CPT → Content added via wp-admin → Template plugin provides `single-{cpt}.php` → WordPress loads template
3. **Asset Loading:** Plugin's `wp_enqueue_scripts` hook → WordPress queues CSS/JS → Theme's `wp_head()`/`wp_footer()` outputs
4. **Cross-Plugin Communication:** Plugin A exposes data via filter → Plugin B modifies via `add_filter()` → Theme displays modified result

## Scaling Considerations

| Scale | Architecture Adjustments |
|-------|--------------------------|
| 0-1k pages | Single-site WordPress, modular plugins as planned |
| 1k-100k pages | Add object caching (Memcached/Redis), CDN for assets, consider multisite if multiple clinics |
| 100k+ pages | Database sharding, separate application server from DB server, consider headless WordPress with static site generator |

### Scaling Priorities

1. **First bottleneck:** Database query performance for CPT queries.
   - **Fix:** Use `WP_Query` with proper caching, transients for expensive queries, avoid direct SQL.

2. **Second bottleneck:** Asset loading (CSS/JS) from multiple plugins.
   - **Fix:** Combine/minify assets, use conditional loading (only load doctor CSS on doctor pages), implement lazy loading.

3. **Third bottleneck:** Hook overhead from many plugins.
   - **Fix:** Use lazy loading (only register hooks when needed), minimize database queries in hooks, use object caching.

## Anti-Patterns

### Anti-Pattern 1: God Plugin

**What people do:** Put everything in one massive plugin file (`functions.php` equivalent).

**Why it's wrong:** Unmaintainable, hard to test, deactivating one feature breaks everything, conflicts inevitable.

**Do this instead:** Single-responsibility plugins - one plugin per feature (doctors, forms, structure).

### Anti-Pattern 2: Direct Database Queries

**What people do:** Use `$wpdb->get_results()` instead of WordPress APIs.

**Why it's wrong:** Bypasses caching, breaks with custom tables, SQL injection risk, not future-proof.

**Do this instead:** Use `WP_Query`, `get_posts()`, `get_post_meta()`, or custom REST API endpoints.

### Anti-Pattern 3: Hardcoded URLs/Paths

**What people do:** Write `href="/wp-content/plugins/my-plugin/style.css"`.

**Why it's wrong:** Breaks if plugin directory changes, doesn't work in subdirectory installs.

**Do this instead:** Use `plugins_url()`, `plugin_dir_path()`, `get_template_directory_uri()`.

### Anti-Pattern 4: Mixing Logic with Presentation

**What people do:** Put business logic in template files (`single.php`).

**Why it's wrong:** Impossible to reuse logic, hard to test, violates separation of concerns.

**Do this instead:** Logic in plugin classes, templates only call functions like `the_post()`, `the_content()`.

### Anti-Pattern 5: Hook Priority Conflicts

**What people do:** Add hooks without specifying priority, assume execution order.

**Why it's wrong:** Unpredictable behavior when multiple plugins hook into the same action.

**Do this instead:** Always specify priority explicitly (`add_action('init', 'callback', 10, 1)`), document dependencies.

## Integration Points

### External Services

| Service | Integration Pattern | Notes |
|---------|---------------------|-------|
| **WhatsApp API** | Direct link generation | No backend integration needed - just `wa.me/` URLs |
| **Google Maps** | Embed URLs in templates | Use Google Maps Embed API for interactive maps if needed |
| **Email (contact forms)** | WordPress `wp_mail()` function | Configure SMTP via plugin (WP Mail SMTP) for deliverability |
| **Social Media (Facebook, Twitter, LinkedIn)** | External links only | No API integration required for basic links |

### Internal Boundaries

| Boundary | Communication | Notes |
|----------|---------------|-------|
| **Structure Plugin ↔ Content Plugins** | Actions/Filters | Structure plugin provides `artricenter_before_content`, `artricenter_after_content` hooks for content injection |
| **Content Plugins ↔ Forms Plugin** | Shortcodes | Forms plugin provides `[doctor_contact_form doctor_id="123"]` shortcode |
| **All Plugins ↔ Theme** | Template Tags + Hooks | Theme calls `the_artricenter_header()`, plugins hook into `wp_head`, `wp_footer` |
| **Plugin ↔ Plugin Data** | Custom Post Types + Metadata | Doctors plugin stores doctor data, Locations plugin reads it via `get_post_meta()` |

## Build Order & Dependencies

### Recommended Build Sequence

Based on dependency analysis, build plugins in this order:

1. **Phase 1: Structure Plugin (artricenter-structure)**
   - **Why:** Foundation - other plugins depend on its hooks, styles, and navigation.
   - **Provides:** `wp_head`/`wp_footer` hooks, navigation registration, global CSS.

2. **Phase 2: Content CPT Plugins (doctors, specialties, locations)**
   - **Why:** Independent of each other, but need structure plugin's styling.
   - **Provides:** CPT registration, meta boxes, custom templates.
   - **Build in parallel:** These can be developed simultaneously.

3. **Phase 3: Interactive Plugins (forms, sticky buttons)**
   - **Why:** Depends on structure plugin for placement, may need CPT data (e.g., contact form for specific doctor).
   - **Provides:** AJAX handlers, shortcodes, interactive UI.

4. **Phase 4: Theme Integration**
   - **Why:** Templates need CPTs to be registered first.
   - **Provides:** Page templates that call plugin functions, template hierarchy overrides.

### Dependency Graph

```
Structure Plugin
    ↓ (provides hooks, styles)
    ├── Content Plugins (doctors, specialties, locations)
    │       ↓ (provide CPT data)
    │       └── Forms Plugin (uses CPT data in forms)
    └── Theme (uses all plugins' template tags & shortcodes)
```

### Critical Integration Points

- **Header/Footer:** Structure plugin MUST be active before theme can render layout.
- **CPT Permalinks:** After activating CPT plugins, flush rewrite rules (visit Settings → Permalinks).
- **Asset Loading:** All plugins hook into `wp_enqueue_scripts` (priority 10), structure plugin should load first (priority 5) to establish base styles.
- **AJAX Endpoints:** Forms plugin must register AJAX handlers before theme tries to use them.

## Migration Strategy from Astro

### Mapping Astro Components to WordPress

| Astro Component | WordPress Equivalent | Implementation |
|-----------------|---------------------|----------------|
| `Layout.astro` | Theme `header.php` + `footer.php` | Structure plugin provides `artricenter_header()`, `artricenter_footer()` functions |
| `Header.astro` | Structure Plugin + Template Tag | Plugin provides `get_artricenter_header()` function, theme calls it |
| `Footer.astro` | Structure Plugin + Template Tag | Plugin provides `get_artricenter_footer()` function, theme calls it |
| `Navigation.astro` | `wp_nav_menu()` + Structure Plugin | Register nav menu in plugin, configure in wp-admin |
| `PageSection.astro` | Shortcodes or Template Parts | `[artricenter_section]` shortcode or plugin-provided template |
| Content pages | WordPress Pages + Shortcodes | Static content → WordPress pages, dynamic sections → shortcodes |
| `navigation.ts` config | WordPress Menu System | Migrate to Appearance → Menus in wp-admin |

### Tailwind to CSS Conversion Strategy

**Current:** Tailwind CSS 4.2.2 in Astro
**Target:** Pure CSS in WordPress plugins

**Approach:**
1. **Structure Plugin:** Convert global utility classes to semantic CSS (`.artricenter-header`, `.artricenter-footer`)
2. **Component-Specific CSS:** Each plugin has its own CSS file (e.g., `doctor-card.css`)
3. **Maintain Visual Identity:** Preserve exact colors, spacing, typography from Astro site
4. **Use CSS Variables:** Define design tokens in structure plugin for consistency (e.g., `--artricenter-blue: #2563eb`)

**Example Conversion:**
```astro
<!-- Astro: Tailwind -->
<div class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-md">
```

```css
/* WordPress: Pure CSS */
.artricenter-header {
    background-color: white;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
```

## Sources

- [Plugin Handbook – WordPress Developer Resources](https://developer.wordpress.org/plugins/) (Last updated: December 14, 2023) – HIGH confidence (official documentation)
- [Plugin Basics – WordPress Developer Resources](https://developer.wordpress.org/plugins/plugin-basics/) (Last updated: December 14, 2023) – HIGH confidence (official documentation)
- [Hooks – Plugin Handbook](https://developer.wordpress.org/plugins/hooks/) (Last updated: December 14, 2023) – HIGH confidence (official documentation)
- [Shortcodes – Plugin Handbook](https://developer.wordpress.org/plugins/shortcodes/) (Last updated: December 14, 2023) – HIGH confidence (official documentation)
- [Metadata – Plugin Handbook](https://developer.wordpress.org/plugins/metadata/) (Last updated: December 14, 2023) – HIGH confidence (official documentation)
- [Theme Basics – Theme Handbook](https://developer.wordpress.org/themes/basics/) (Last updated: December 14, 2023) – HIGH confidence (official documentation)
- [Project Context – Artricenter PROJECT.md](/home/zurybr/workspaces/artricenter/.planning/PROJECT.md) – HIGH confidence (project requirements)
- [Current Codebase Analysis – Artricenter Astro Site](/home/zurybr/workspaces/artricenter/src/) – HIGH confidence (existing implementation)

---
*Architecture research for: Modular WordPress Plugin Development*
*Researched: 2026-03-19*
