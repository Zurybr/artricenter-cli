# Artricenter Structure Plugin

Provides site-wide layout components (header, footer, navigation) and global CSS for Artricenter WordPress site.

## Features

- Responsive header with logo and navigation (mobile overlay + desktop horizontal menu)
- Footer with 3 sucursales cards (La Raza, Atizapán, Viaducto) and info section
- Namespaced CSS (.artricenter-) to prevent theme conflicts
- Smooth scroll navigation for in-page anchors
- Custom hooks for plugin extensibility

## Installation

1. Upload `artricenter-structure` folder to `/wp-content/plugins/`
2. Activate plugin in WordPress admin (Plugins → Add New)
3. Add template tags to theme:
   - Header: `<?php artricenter_the_header(); ?>` in theme's `header.php`
   - Footer: `<?php artricenter_the_footer(); ?>` in theme's `footer.php`

## Custom Hooks

### artricenter_before_content

Fires before the main content area. Use for banners, announcements, or content that should appear at the top of every page.

**Example:**
```php
add_action( 'artricenter_before_content', 'my_custom_banner' );
function my_custom_banner() {
    echo '<div class="custom-banner">Welcome to Artricenter!</div>';
}
```

### artricenter_after_content

Fires after the main content area. Use for CTAs, related content, or elements that should appear at the bottom of every page.

**Example:**
```php
add_action( 'artricenter_after_content', 'my_custom_cta' );
function my_custom_cta() {
    echo '<div class="custom-cta">Contact us today!</div>';
}
```

## Template Tags

### artricenter_the_header()
Displays the Artricenter header with logo and responsive navigation.

### artricenter_the_footer()
Displays the Artricenter footer with sucursales cards and info section.

## CSS Namespace

All CSS classes use the `.artricenter-` prefix to prevent conflicts with WordPress themes.

Example:
- `.artricenter-header` (not `.header`)
- `.artricenter-footer` (not `.footer`)
- `.artricenter-nav` (not `.nav`)

## Requirements

- WordPress 6.9.4+
- PHP 8.2+
- Theme with `wp_head()` and `wp_footer()` hooks

## License

GPL v2 or later
