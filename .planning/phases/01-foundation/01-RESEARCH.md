# Phase 1: Foundation - Research

**Researched:** 2026-03-19
**Domain:** WordPress plugin development with Docker environment
**Confidence:** HIGH

## Summary

Phase 1 establishes the foundational infrastructure for the Artricenter WordPress migration: a Docker-based development environment and a structure plugin that provides site-wide layout components (header, footer, navigation) and global CSS. This phase enables all subsequent development by providing a local WordPress 6.9.4 environment with PHP 8.2 and MySQL 8.0, plus a modular plugin architecture with namespaced CSS to prevent conflicts with WordPress themes.

The primary technical challenge is converting the existing Astro site's Tailwind CSS to pure CSS with a `.artricenter-` namespace prefix while maintaining visual parity. The plugin must provide template tags for header/footer integration rather than automatic injection, allowing WordPress themes to control placement. Navigation will use WordPress's built-in menu system (`register_nav_menu()`, `wp_nav_menu()`) for admin-manageable menus.

**Primary recommendation:** Use official WordPress Docker images with separate containers for WordPress (PHP 8.2-FPM), Nginx web server, MySQL 8.0, and WP-CLI. Structure the plugin as a single cohesive unit with separate PHP classes for header, footer, navigation, and hooks, following WordPress Coding Standards (WPCS) throughout.

<user_constraints>
## User Constraints (from CONTEXT.md)

### Locked Decisions
- **WordPress version**: 6.9.4 with PHP 8.2 (Apache variant) — current stable with official PHP 8.2 support
- **Database**: MySQL 8.0 (official mysql image) — matches production requirements
- **WP-CLI included**: As separate container service for efficient WordPress management
- **Volume mounting**: Plugin directories mounted for hot-reload development
- **Local URL**: http://localhost:8080 (port 8080 to avoid conflicts)
- **Persistent data**: MySQL data volume for database persistence across container restarts
- **Single structure plugin**: `artricenter-structure` — contains header, footer, navigation, global CSS, and hooks
- **Namespace prefix**: `.artricenter-` on ALL CSS classes to prevent conflicts with WordPress themes and plugins
- **Conversion approach**: Manual conversion from Tailwind utilities to semantic CSS with CSS variables for design tokens
- **Header component**: Desktop (logo + horizontal nav), Mobile (hamburger menu + centered text + slide-in overlay)
- **Footer component**: Three sucursales cards (La Raza/blue, Atizapán/green, Viaducto/orange) with info section
- **Navigation**: Desktop horizontal menu with dropdowns, Mobile full-screen overlay with submenu accordions
- **WordPress integration**: Use template tags `artricenter_get_header()` and `artricenter_get_footer()` in theme, not automatic injection
- **JavaScript**: Mobile menu toggle with ARIA attributes, escape key, focus trapping (basic implementation)
- **Sticky behavior**: Header sticks to top with `position: sticky; top: 0; z-index: 50`

### Claude's Discretion
- Exact CSS specificity and selector organization (use BEM or utility-first within namespace)
- Mobile menu animation timing and easing functions
- Header/footer HTML markup details (semantic HTML5 tags, ARIA roles)
- WordPress template hierarchy integration (which template files to modify)

### Deferred Ideas (OUT OF SCOPE)
None — discussion stayed within phase scope.
</user_constraints>

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|-----------------|
| DOCKER-01 | WordPress 6.9.4 + PHP 8.2 + MySQL 8.0 locally using Docker Compose | Official Docker images provide WordPress 6.9.4 with PHP 8.2-FPM variant. MySQL 8.0 official image available. Docker Compose orchestrates multi-container setup. |
| DOCKER-02 | Access WordPress site at http://localhost with hot-reload for plugin development | Volume mounting plugin directories enables hot-reload. Port mapping 8080:80 avoids conflicts. Nginx web server handles PHP-FPM via FastCGI. |
| DOCKER-03 | Docker environment includes WP-CLI for efficient WordPress management | Official `wordpress:cli` image provides WP-CLI. Separate container with shared volumes executes WP-CLI commands against WordPress container. |
| STRUCT-01 | Header component with logo and responsive navigation (mobile overlay + desktop) | Template tag `artricenter_get_header()` returns HTML string. Mobile: hamburger + centered text + slide-in overlay. Desktop: logo + horizontal nav. Sticky positioning. |
| STRUCT-02 | Footer component with 3 sucursales cards including addresses, phone, and Google Maps links | Template tag `artricenter_get_footer()` returns HTML string. 3-column grid (stacked mobile). Color-coded cards (blue/green/orange) with icons, addresses, phone, map links. |
| STRUCT-03 | Global CSS converted from Tailwind with `.artricenter-` namespace prefix | Manual Tailwind → semantic CSS conversion. CSS variables for design tokens. BEM methodology within namespace. Enqueue via `wp_enqueue_style()`. |
| STRUCT-04 | Smooth scroll navigation for in-page anchors | `html { scroll-behavior: smooth; scroll-padding-top: 5rem; }` in main.css. Compatible with all modern browsers. |
| STRUCT-05 | Register WordPress hooks for other plugins to inject content | `do_action('artricenter_before_content')` and `do_action('artricenter_after_content')` in hook class. PHPDoc documents usage. |
</phase_requirements>

## Standard Stack

### Core
| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| WordPress | 6.9.4 | CMS platform | Current stable release with PHP 8.2 support |
| PHP | 8.2-FPM | Server-side scripting | Official WordPress recommended version |
| MySQL | 8.0 | Database | Official mysql image, production-compatible |
| Nginx | (Alpine) | Web server | Lightweight reverse proxy for PHP-FPM |
| WP-CLI | (latest) | Command-line interface | Official WordPress CLI tool |
| Docker Compose | v3.9+ | Container orchestration | Standard multi-container Docker management |

### Supporting
| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| Docker Volume Driver | (native) | Persistent data | MySQL data persistence across restarts |
| Alpine Linux Images | (latest) | Base images | Smaller image size, faster builds |

### Alternatives Considered
| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| WordPress + Nginx + PHP-FPM | WordPress Apache variant | Apache simpler but slower; Nginx+PHP-FPM is production-standard |
| MySQL 8.0 | MariaDB 10.x | MariaDB drop-in replacement but MySQL 8.0 explicitly specified |
| Separate containers | All-in-one WordPress image | All-in-one simpler but less flexible; separation matches best practices |

**Installation:**
```bash
# No npm packages needed for this phase
# Docker images pulled automatically via docker-compose up
# Docker Compose v2.20+ recommended
docker --version  # Verify Docker installed
docker compose version  # Verify Docker Compose installed
```

## Architecture Patterns

### Recommended Project Structure
```
wp-content/
└── plugins/
    └── artricenter-structure/
        ├── artricenter-structure.php    # Main plugin file with header
        ├── includes/
        │   ├── class-header.php         # Header component
        │   ├── class-footer.php         # Footer component
        │   ├── class-navigation.php     # Navigation menu registration
        │   └── class-hooks.php           # Custom hook registration
        ├── assets/
        │   ├── css/
        │   │   ├── main.css              # Converted CSS with namespace
        │   │   └── variables.css         # CSS variables (design tokens)
        │   └── js/
        │       └── mobile-menu.js        # Mobile menu toggle logic
        ├── templates/
        │   ├── template-tags.php        # Template tag functions
        │   └── hooks.php                 # Hook registration
        └── README.md                     # Plugin documentation

docker/
├── docker-compose.yml                   # Multi-container orchestration
├── nginx/
│   └── default.conf                     # Nginx configuration
├── php/
│   └── uploads.ini                      # PHP upload limits
└── wp-cli/
    └── wp-cli.yml                       # WP-CLI configuration
```

### Pattern 1: WordPress Plugin Header
**What:** Standard WordPress plugin header comment in main PHP file
**When to use:** Every WordPress plugin must have exactly one file with this header
**Example:**
```php
<?php
/**
 * Plugin Name: Artricenter Structure
 * Plugin URI: https://artricenter.com.mx
 * Description: Provides site-wide layout components (header, footer, navigation) and global CSS for Artricenter.
 * Version: 1.0.0
 * Author: Artricenter
 * Author URI: https://artricenter.com.mx
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: artricenter-structure
 * Domain Path: /languages
 */

// Security: Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
```

### Pattern 2: Template Tag Functions
**What:** Functions that return HTML strings for theme integration
**When to use:** When plugin needs to provide reusable components to themes
**Example:**
```php
/**
 * Retrieve the Artricenter header HTML.
 *
 * @return string Header HTML markup
 */
function artricenter_get_header(): string {
    $header = new \Artricenter\Structure\Header();
    return $header->render();
}

/**
 * Display the Artricenter header.
 *
 * Usage in theme: <?php artricenter_the_header(); ?>
 */
function artricenter_the_header(): void {
    echo artricenter_get_header();
}
```

### Pattern 3: WordPress Menu Registration
**What:** Register custom navigation menu locations using `register_nav_menu()`
**When to use:** When plugin provides theme integration for navigation
**Example:**
```php
// Source: https://developer.wordpress.org/reference/functions/register_nav_menu/
add_action( 'after_setup_theme', 'artricenter_register_menus' );
function artricenter_register_menus() {
    register_nav_menus( array(
        'artricenter_primary' => __( 'Artricenter Primary Menu', 'artricenter-structure' ),
        'artricenter_mobile'  => __( 'Artricenter Mobile Menu', 'artricenter-structure' ),
    ) );
}
```

### Pattern 4: CSS Enqueuing
**What:** Properly enqueue CSS files using WordPress hooks
**When to use:** Always include CSS via `wp_enqueue_scripts`, never hardcode `<link>` tags
**Example:**
```php
add_action( 'wp_enqueue_scripts', 'artricenter_enqueue_styles' );
function artricenter_enqueue_styles(): void {
    wp_enqueue_style(
        'artricenter-structure-style',
        plugins_url( 'assets/css/main.css', __FILE__ ),
        array(),                    // Dependencies
        '1.0.0',                    // Version
        'all'                       // Media
    );
}
```

### Pattern 5: JavaScript Enqueuing with Localization
**What:** Enqueue JavaScript with server-side data passed via `wp_localize_script()`
**When to use:** When JavaScript needs dynamic values from WordPress (e.g., AJAX URLs, nonces)
**Example:**
```php
add_action( 'wp_enqueue_scripts', 'artricenter_enqueue_scripts' );
function artricenter_enqueue_scripts(): void {
    wp_enqueue_script(
        'artricenter-mobile-menu',
        plugins_url( 'assets/js/mobile-menu.js', __FILE__ ),
        array(),                    // No jQuery dependency
        '1.0.0',
        true                        // Load in footer
    );
}
```

### Anti-Patterns to Avoid
- **Direct SQL queries without $wpdb->prepare():** Use prepared statements to prevent SQL injection
- **Hardcoded plugin paths:** Always use `plugins_url()` or `plugin_dir_path()`
- **Output escaping in template tags:** Return strings, let theme handle escaping (or use `wp_kses_post()` for HTML)
- **Skipping capability checks:** Always check `current_user_can()` before privileged operations
- **Modifying WordPress core:** Use hooks and filters instead
- **Hardcoding text without translation:** Always wrap strings in `__()` or `_e()` with text domain

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| WordPress environment setup | Manual LAMP stack installation | Docker Compose with official images | Reproducible, isolated, matches production, easier team onboarding |
| CSS framework | Custom utility classes | WordPress coding standards + BEM within namespace | Proven patterns, better maintainability, WP-compatible |
| Navigation menus | Custom HTML markup | `wp_nav_menu()` with registered locations | Admin-manageable, WordPress-native, accessibility built-in |
| Asset loading | `<link>` and `<script>` tags in templates | `wp_enqueue_style()` / `wp_enqueue_script()` | Proper dependency management, cache busting, WordPress integration |
| Form handling | Raw `$_POST` processing | WordPress Options API / Settings API | Sanitization, validation, security built-in |
| Database queries | Direct MySQL queries | WordPress `WP_Query`, `get_posts()`, `$wpdb->prepare()` | Caching, optimization, security |

**Key insight:** WordPress provides APIs for almost everything. Using them ensures compatibility, security, and future-proofing. Custom solutions introduce maintenance burden and security risks.

## Common Pitfalls

### Pitfall 1: Plugin Activation Hook Not Firing
**What goes wrong:** Activation hook code never executes when plugin is activated
**Why it happens:** Hook registered inside another hook (e.g., `add_action('init', ...)`), wrong main file path, or network-activation issues
**How to avoid:** Register activation hooks at top-level scope in main plugin file, verify path with `plugin_basename(__FILE__)`
**Warning signs:** Database tables not created, options not set, flush_rewrite_rules() not working

### Pitfall 2: CSS Specificity Wars
**What goes wrong:** Plugin styles overridden by theme or other plugins
**Why it happens:** Insufficient specificity, missing namespace prefix, not using `!important` strategically
**How to avoid:** Use `.artricenter-` prefix on ALL selectors, leverage CSS variables for theming, test with popular themes (Twenty Twenty-Four, Astra, OceanWP)
**Warning signs:** Layout breaks when theme changes, browser inspector shows theme styles overriding plugin

### Pitfall 3: Mobile Menu Accessibility Failures
**What goes wrong:** Mobile menu not keyboard-navigable, missing ARIA labels, focus not trapped
**Why it happens:** JavaScript only handles mouse events, no semantic HTML, no focus management
**How to avoid:** Use semantic `<button>` elements, add `aria-expanded`, `aria-label`, handle Escape key, trap focus when menu open, test with screen reader
**Warning signs:** Can't navigate menu with Tab key, screen reader doesn't announce menu state, focus escapes when menu open

### Pitfall 4: Docker Volume Mount Permission Issues
**What goes wrong:** WordPress container can't write to mounted volumes, permission denied errors
**Why it happens:** Container runs as `www-data` (UID 82) but host files owned by different user
**How to avoid:** Set `USER_ID` environment variable, use Docker Compose `user` directive, or chmod host directories to 777 (development only)
**Warning signs:** WordPress can't install plugins, upload fails, can't modify theme files

### Pitfall 5: WP-CLI Container Can't Connect to WordPress
**What goes wrong:** `wp-cli` container commands fail with "Error establishing database connection"
**Why it happens:** WP-CLI container not in same Docker network, or `WORDPRESS_DB_HOST` mismatch
**How to avoid:** Ensure both containers in same network, use service name as hostname (e.g., `wordpress:8080`), verify environment variables match
**Warning signs:** `wp core is-installed` returns false, can't list plugins, database errors

## Code Examples

Verified patterns from official sources:

### WordPress Plugin Structure
```php
// Source: https://developer.wordpress.org/plugins/plugin-basics/

/**
 * Plugin Name: Artricenter Structure
 * Description: Site-wide layout components for Artricenter
 * Version: 1.0.0
 * Author: Artricenter
 * License: GPL v2+
 */

// Security check
defined( 'ABSPATH' ) || exit;

// Autoloader for classes
spl_autoload_register( function ( $class ) {
    $prefix = 'Artricenter\\Structure\\';
    $base_dir = __DIR__ . '/includes/';

    $len = strlen( $prefix );
    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );
    $file = $base_dir . str_replace( '\\', '/', strtolower( $relative_class ) ) . '.php';

    if ( file_exists( $file ) ) {
        require $file;
    }
} );

// Initialize plugin
function artricenter_structure_init() {
    $plugin = new \Artricenter\Structure\Plugin();
    $plugin->run();
}
add_action( 'plugins_loaded', 'artricenter_structure_init' );
```

### Register Navigation Menus
```php
// Source: https://developer.wordpress.org/reference/functions/register_nav_menu/

add_action( 'after_setup_theme', 'artricenter_register_menus' );
function artricenter_register_menus() {
    register_nav_menus( array(
        'artricenter_primary' => __( 'Artricenter Primary Menu', 'artricenter-structure' ),
        'artricenter_mobile'  => __( 'Artricenter Mobile Menu', 'artricenter-structure' ),
    ) );
}
```

### Display Navigation Menu
```php
// Source: https://developer.wordpress.org/reference/functions/wp_nav_menu/

function artricenter_get_navigation( $location = 'artricenter_primary' ): string {
    $args = array(
        'theme_location' => $location,
        'container'      => false,           // No container div
        'menu_class'     => 'artricenter-nav',
        'fallback_cb'    => false,           // Don't show pages if menu not set
        'echo'           => false,           // Return instead of echo
    );

    return wp_nav_menu( $args );
}
```

### CSS Namespacing Pattern
```css
/* Source: WordPress coding standards + BEM methodology */

/* CSS Variables - Design Tokens */
:root {
  --artricenter-color-blue: #2563eb;
  --artricenter-color-green: #16a34a;
  --artricenter-color-orange: #ea580c;
  --artricenter-spacing-unit: 1rem;
  --artricenter-border-radius: 0.5rem;
}

/* Block component */
.artricenter-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: white;
  border-bottom: 1px solid #e5e7eb;
  position: sticky;
  top: 0;
  z-index: 50;
}

/* Element */
.artricenter-header__logo {
  height: 4rem;
  width: auto;
}

/* Modifier */
.artricenter-header--mobile {
  height: 3.5rem;
}

/* Responsive breakpoints (Tailwind conversion) */
@media (min-width: 1024px) {
  .artricenter-header {
    height: 5rem;
  }
}
```

### Docker Compose Configuration
```yaml
# Source: Docker Compose best practices + WordPress Docker official docs
version: '3.9'

services:
  wordpress:
    image: wordpress:6.9.4-php8.2-fpm-alpine
    container_name: artricenter_wp
    restart: unless-stopped
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_NAME: artricenter
      WORDPRESS_DB_USER: wpuser
      WORDPRESS_DB_PASSWORD: wpsecret
    volumes:
      - ./wp-content:/var/www/html/wp-content
      - ./php/uploads.ini:/usr/local/etc/php/conf.d/uploads.ini
    depends_on:
      - db
    networks:
      - artricenter_network

  nginx:
    image: nginx:alpine
    container_name: artricenter_nginx
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./wp-content:/var/www/html/wp-content
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - wordpress
    networks:
      - artricenter_network

  db:
    image: mysql:8.0
    container_name: artricenter_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: artricenter
      MYSQL_USER: wpuser
      MYSQL_PASSWORD: wpsecret
      MYSQL_ROOT_PASSWORD: rootsecret
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - artricenter_network

  wpcli:
    image: wordpress:cli-php8.2
    container_name: artricenter_wpcli
    volumes:
      - ./wp-content:/var/www/html/wp-content
    depends_on:
      - db
      - wordpress
    networks:
      - artricenter_network

volumes:
  db_data:

networks:
  artricenter_network:
```

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| Apache + mod_php | Nginx + PHP-FPM | ~2018 | Better performance, separation of concerns, production-standard |
| Hardcoded navigation | `wp_nav_menu()` | WordPress 3.0 (2010) | Admin-manageable menus, accessibility, internationalization |
| Inline CSS/JS | Enqueued assets | WordPress 2.1 (2007) | Cache busting, dependency management, proper loading order |
| Manual WP setup | Docker Compose | ~2016 | Reproducible environments, team onboarding, isolation |
| jQuery for mobile menus | Vanilla JS | ~2020 | Faster load times, less overhead, modern browser support |

**Deprecated/outdated:**
- **PHP 7.x**: Replaced by PHP 8.0+ (WordPress 6.9 requires PHP 7.2.24+ but recommends 8.x)
- **MySQL 5.7**: Replaced by MySQL 8.0 (better performance, JSON support, window functions)
- **Apache mod_php**: Still supported but Nginx+PHP-FPM is performance standard
- **Hardcoded theme paths**: Use `get_template_directory()` or `plugin_dir_path()` instead
- **`wp_head()`/`wp_footer()` missing**: All themes must include these for plugin compatibility

## Open Questions

1. **WordPress theme selection for development**
   - What we know: Plugin provides header/footer via template tags, theme must call them
   - What's unclear: Which theme to use for local development (Twenty Twenty-Four, custom starter theme, or child theme)
   - Recommendation: Use Twenty Twenty-Four (default WordPress 6.9 theme) for initial development, ensures maximum compatibility. Can switch to custom theme later.

2. **CSS conversion tooling vs. manual conversion**
   - What we know: CONTEXT.md specifies "manual conversion from Tailwind utilities to semantic CSS"
   - What's unclear: Whether to use any tooling (PostCSS, PurgeCSS) or pure manual conversion
   - Recommendation: Manual conversion as specified. Use browser DevTools to inspect Tailwind output, then write semantic CSS with namespace. Faster than setting up build tooling for one-time conversion.

3. **Mobile menu animation implementation**
   - What we know: Slide-in from right with backdrop blur, submenu accordions
   - What's unclear: Exact timing (300ms? 200ms?) and easing function (ease-out? cubic-bezier?)
   - Recommendation: Use existing Astro site's animation timing (300ms, ease-out) as reference. Adjust based on feel after implementation.

## Validation Architecture

### Test Framework
| Property | Value |
|----------|-------|
| Framework | WordPress + Manual Testing (PHPUnit setup optional) |
| Config file | None for WordPress (Wave 0) |
| Quick run command | `docker compose up -d && docker compose exec -T wordpress wp-cli wp plugin status` |
| Full suite command | Manual verification checklist (see below) |

### Phase Requirements → Test Map
| Req ID | Behavior | Test Type | Automated Command | File Exists? |
|--------|----------|-----------|-------------------|-------------|
| DOCKER-01 | Docker environment starts with correct services | smoke | `docker compose ps` | ❌ Wave 0 |
| DOCKER-02 | WordPress accessible at localhost:8080 | smoke | `curl -s -o /dev/null -w "%{http_code}" http://localhost:8080` | ❌ Wave 0 |
| DOCKER-03 | WP-CLI commands execute | smoke | `docker compose exec -T wp-cli wp core version` | ❌ Wave 0 |
| STRUCT-01 | Header displays on all pages | manual | Visual inspection in browser | ❌ Wave 0 |
| STRUCT-02 | Footer displays on all pages | manual | Visual inspection in browser | ❌ Wave 0 |
| STRUCT-03 | CSS loads with namespace prefix | manual | Browser DevTools inspect elements | ❌ Wave 0 |
| STRUCT-04 | Smooth scroll works for anchors | manual | Click anchor link, observe scroll behavior | ❌ Wave 0 |
| STRUCT-05 | Custom hooks fire | manual | Add test plugin to hook into actions, verify execution | ❌ Wave 0 |

### Sampling Rate
- **Per task commit:** `docker compose ps` (verify containers running)
- **Per wave merge:** Full manual verification checklist (browse site, test mobile menu, check CSS namespace)
- **Phase gate:** All success criteria verified (docker env working, header/footer/nav displayed, CSS namespaced, smooth scroll, hooks registered)

### Wave 0 Gaps
- [ ] `docker-compose.yml` — Multi-container orchestration (WordPress, Nginx, MySQL, WP-CLI)
- [ ] `nginx/default.conf` — Nginx configuration for PHP-FPM
- [ ] `php/uploads.ini` — PHP upload limits configuration
- [ ] `wp-content/plugins/artricenter-structure/` — Plugin directory structure
- [ ] `artricenter-structure.php` — Main plugin file with header
- [ ] `includes/class-header.php` — Header component class
- [ ] `includes/class-footer.php` — Footer component class
- [ ] `includes/class-navigation.php` — Navigation menu registration
- [ ] `includes/class-hooks.php` — Custom hook registration
- [ ] `assets/css/main.css` — Converted CSS with `.artricenter-` namespace
- [ ] `assets/css/variables.css` — CSS variables for design tokens
- [ ] `assets/js/mobile-menu.js` — Mobile menu toggle logic
- [ ] `templates/template-tags.php` — Template tag functions
- [ ] `README.md` — Plugin documentation

## Sources

### Primary (HIGH confidence)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/) - Complete plugin development guide
- [Plugin Basics – Plugin Handbook](https://developer.wordpress.org/plugins/plugin-basics/) - Plugin structure, headers, hooks, file organization
- [Template Tags – Theme Handbook](https://developer.wordpress.org/themes/basics/template-tags/) - Template tag usage and patterns
- [register_nav_menu() Function Reference](https://developer.wordpress.org/reference/functions/register_nav_menu/) - Navigation menu registration API
- [wp_nav_menu() Function Reference](https://developer.wordpress.org/reference/functions/wp_nav_menu/) - Navigation menu display API
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) - PHP, HTML, CSS conventions
- [Plugin Security Handbook](https://developer.wordpress.org/plugins/security/) - Nonces, sanitization, escaping, capability checks
- [WordPress Docker Official Images](https://hub.docker.com/_/wordpress) - Official WordPress Docker images documentation
- [Docker Compose Documentation](https://docs.docker.com/compose/) - Docker Compose reference

### Secondary (MEDIUM confidence)
- Existing Artricenter Astro site codebase - Reference implementation for header, footer, navigation, CSS
- CONTEXT.md decisions - Locked technical choices for this phase
- REQUIREMENTS.md - Phase requirements and success criteria
- WordPress Pro skill (.agents/skills/wordpress-pro/SKILL.md) - WordPress security and API patterns
- WP Plugin Development skill (.agents/skills/wp-plugin-development/SKILL.md) - Plugin architecture and lifecycle

### Tertiary (LOW confidence)
- Web search attempts returned empty results (search service issues) - Relied on official documentation instead
- No external community resources needed - Official WordPress docs sufficient

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH - Official WordPress/Docker docs provide complete specifications
- Architecture: HIGH - WordPress plugin architecture well-documented, existing Astro code provides reference
- Pitfalls: HIGH - Common WordPress plugin issues well-documented in official handbooks
- CSS conversion: MEDIUM - Manual conversion approach specified but Tailwind-to-semantic CSS has subjective decisions
- Mobile menu implementation: MEDIUM - Accessibility requirements clear but exact animation timing requires iteration

**Research date:** 2026-03-19
**Valid until:** 2026-04-19 (30 days - WordPress core and Docker images stable, but security updates may change best practices)
