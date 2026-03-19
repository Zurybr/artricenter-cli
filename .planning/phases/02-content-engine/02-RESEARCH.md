# Phase 2: Content Engine - Research

**Researched:** 2026-03-19
**Confidence:** HIGH

## WordPress Native APIs

All required functionality can be implemented using WordPress core APIs without external dependencies like ACF:

- **CPT Registration**: `register_post_type()` — unique rewrite slugs to avoid permalink conflicts
- **Meta Boxes**: `add_meta_box()` with nonce verification, capability checks, sanitization/escaping
- **Page Creation**: `wp_insert_post()` on activation hook
- **Page Templates**: Plugin-provided templates with Template Name header
- **Shortcodes**: `add_shortcode()` for CPT listings and homepage sections

## Security Best Practices

Meta box implementation requires:
- **Nonce verification**: `wp_nonce_field()`, `check_ajax_referer()`
- **Capability checks**: `current_user_can()` before saving
- **Sanitization**: `sanitize_text_field()`, `esc_url()`, `esc_html()` on input
- **Escaping**: `esc_attr()`, `esc_html()` on output

## Architecture Patterns

From existing `artricenter-structure` plugin:
- **PSR-4 autoloading**: Namespace prefix `Artricenter\Content\*`
- **Class-based components**: Each CPT type gets its own class
- **Template tags**: Functions for reusable components
- **Plugin hooks**: Activation hook for setup

## Page Template Strategy

Plugin-provided page templates:
```php
<?php
/**
 * Template Name: Artricenter Homepage
 *
 * @package Artricenter_Content
 */
```

Pages created programmatically on activation:
```php
wp_insert_post([
  'post_title' => 'Inicio',
  'post_type' => 'page',
  'post_status' => 'publish',
  'page_template' => 'template-homepage.php'
]);
```

## Shortcode Implementation

Shortcodes for CPT listings:
```php
add_shortcode('artricenter_doctores_grid', 'doctores_grid_shortcode');
add_shortcode('artricenter_mission_cards', 'mission_cards_shortcode');
```

Homepage uses shortcodes for dynamic content:
```
[artricenter_doctores_grid]
[artricenter_mission_cards]
```

## Validation Architecture

### Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | WordPress PHPUnit + integration tests |
| **Config file** | `wp-content/plugins/artricenter-content/tests/bootstrap.php` |
| **Quick run command** | `docker compose exec wordpress wp plugin test --dependencies` |
| **Full suite command** | `docker compose exec wordpress wp plugin test` |
| **Estimated runtime** | ~45 seconds |

### Sampling Rate

- **After every task commit:** Verify CPT registration, meta box display, page creation
- **After every plan wave:** Run integration tests + browser verification
- **Before `/gsd:verify-work`:** All success criteria verified
- **Max feedback latency:** 60 seconds

### Per-Task Verification Map

Each task must include automated verification command:
- CPT registration: `wp post type list` or direct database check
- Meta box: Check admin UI rendering
- Page creation: `wp post list` to verify page exists
- Template: Verify template attribute assigned
- Shortcode: Test shortcode output with content

### Wave 0 Requirements

Phase 2 infrastructure files created during implementation:
- `artricenter-content.php` — Main plugin file
- `includes/class-doctores.php` — Doctores CPT
- `includes/class-especialidades.php` — Especialidades CPT
- `includes/class-sucursales.php` — Sucursales CPT
- `includes/class-page-creator.php` — Page creation on activation
- `templates/` — Page templates and template parts
- `includes/class-shortcodes.php` — Shortcode registration

### Manual-Only Verifications

| Behavior | Why Manual | Test Instructions |
|----------|------------|-------------------|
| Meta box display in admin | Admin UI requires visual inspection | Create/edit CPT entry, verify meta boxes render |
| Media uploader for images | Interactive media modal | Test image upload in CPT meta box |
| Page template assignment | Admin dropdown selection | Edit page, verify template option available |
| Shortcode output rendering | Visual content inspection | Add shortcode to page, verify output displays |
| CPT archive query | Content listing verification | Visit CPT archive page, verify entries show |
| Homepage section layout | Visual design verification | Visit homepage, verify sections render correctly |

## Pitfalls and Solutions

### CPT Rewrite Slug Conflicts
- **Pitfall**: CPTs with generic names (doctor, location) conflict with pages
- **Solution**: Use unique rewrite slugs (e.g., `doctor-artricenter`, `sucursal-artricenter`)
- **Reference**: CPT-04 requirement

### Meta Box Security
- **Pitfall**: Missing nonce verification allows CSRF attacks
- **Solution**: Always include `wp_nonce_field()` and verify on save
- **Reference**: WordPress Plugin Handbook security examples

### Page Creation on Activation
- **Pitfall**: Pages created multiple times if plugin activated/deactivated
- **Solution**: Check if page exists before creating (`get_page_by_path()`)
- **Reference**: WordPress activation hook best practices

### Template Loading
- **Pitfall**: Theme templates override plugin templates
- **Solution**: Plugin-provided templates with unique names, document override pattern
- **Reference**: WordPress template hierarchy

### Shortcode Performance
- **Pitfall**: Shortcodes query database on every page load
- **Solution**: Use `WP_Query` with proper caching, limit result sets
- **Reference**: WordPress shortcode performance guidelines

## Open Questions

1. **Media uploader integration**: Verify `wp_enqueue_media()` for plugin meta boxes
2. **Color picker implementation**: Native `wp-color-picker` or predefined select dropdown

## Ready for Planning

All technical patterns documented. Planner can create executable plans with:
- CPT registration with unique rewrite slugs
- Secure meta box implementation
- Programmatic page creation
- Plugin-provided page templates
- Shortcode implementation
- PSR-4 autoloading following structure plugin pattern
- Template tag functions
