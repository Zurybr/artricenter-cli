# Pitfalls Research

**Domain:** WordPress Migration (Static Site to WordPress with Custom Plugins)
**Researched:** 2026-03-19
**Confidence:** MEDIUM

## Critical Pitfalls

### Pitfall 1: CSS Namespace Collisions

**What goes wrong:**
Tailwind utility classes conflict with existing WordPress theme styles, causing layout breaks when plugins are activated. The converted CSS selectors override or get overridden by WordPress admin bar, theme styles, or other plugins.

**Why it happens:**
- Tailwind uses generic utility classes (`.flex`, `.text-center`, `.p-4`) that are extremely common
- WordPress themes and plugins often use similarly generic class names
- CSS specificity wars between your plugin, active theme, and WordPress core
- No namespacing strategy in the conversion from Tailwind to plain CSS

**How to avoid:**
1. Prefix ALL CSS classes with project-specific namespace (e.g., `.artricenter-`, `-at-`)
2. Wrap all plugin output in container div with unique ID
3. Use CSS specificity strategically (add `#artricenter-content` prefix to all selectors)
4. Load plugin CSS with higher priority than theme CSS
5. Test with multiple active themes (Twenty Twenty-Four, popular commercial themes)

**Warning signs:**
- Styles work in one theme but break in another
- WordPress admin bar styling affects your plugin output
- Hover states or responsive behavior works inconsistently
- Other plugins' styles bleeding into your content

**Phase to address:**
Phase: **STRUCT-02 (Tailwind to CSS Conversion)**
- This MUST be addressed during CSS conversion, not after
- Create CSS namespace convention before converting any Tailwind classes
- Establish testing protocol with multiple themes before writing production CSS

---

### Pitfall 2: Plugin Activation Dependencies

**What goes wrong:**
Custom plugins activate successfully but functionality fails because required WordPress features, other plugins, or theme dependencies are missing. Errors appear only when specific actions are taken, not on activation.

**Why it happens:**
- WordPress plugin activation doesn't enforce dependencies
- Assuming theme functions (`the_content()`, `get_header()`) will always be available
- Not checking if required WordPress features (Custom Post Types, taxonomies) are registered before using them
- Multiple plugins trying to register the same CPT names or rewrite rules

**How to avoid:**
1. Implement activation checks in each plugin:
   ```php
   register_activation_hook(__FILE__, 'artricenter_check_dependencies');
   function artricenter_check_dependencies() {
       if (!current_theme_supports('post-thumbnails')) {
           deactivate_plugins(plugin_basename(__FILE__));
           wp_die('Theme does not support post-thumbnails');
       }
   }
   ```
2. Use `wp_using_ext_object_cache()` checks for caching dependencies
3. Verify rewrite rules flush properly after CPT registration
4. Add admin notices when dependencies are missing (soft failures)
5. Create a "System Status" admin page showing all dependencies

**Warning signs:**
- White screens or PHP errors only on specific pages
- Custom post types return 404 errors
- Shortcodes render as raw text instead of executing
- Images not displaying (missing `post-thumbnails` support)

**Phase to address:**
Phase: **DOCKER-01 (Initial Setup)**
- Establish dependency checking pattern BEFORE building plugins
- Create reusable dependency checker class all plugins use
- Test activation/deactivation cycles in local Docker environment

---

### Pitfall 3: wp-admin Deployment Failures

**What goes wrong:**
Plugin works perfectly in Docker development environment but fails when uploaded via wp-admin to production due to file permissions, missing files in ZIP, or path differences.

**Why it happens:**
- Docker volume mounts don't replicate production file structure
- ZIP creation excludes hidden files (`.htaccess`, `.gitignore`)
- File permissions differ between Docker container and production hosting
- Absolute paths in plugin code point to Docker-specific locations
- Maximum upload size limits on production hosting

**How to avoid:**
1. Create deployment checklist:
   ```bash
   # Build ZIP properly
   zip -r plugin-name.zip plugin-name/ -x "*.git*" "node_modules/*"

   # Verify ZIP contents
   unzip -l plugin-name.zip
   ```
2. Use `plugin_dir_path(__FILE__)` and `plugins_url()` instead of absolute paths
3. Add admin notice showing PHP memory limit and upload_max_filesize
4. Test deployment process in staging environment before production
5. Include README with deployment instructions and common errors

**Warning signs:**
- "Plugin has no valid header" error despite header being present
- Images or assets return 404 after activation
- PHP warnings about "failed to open stream" in production logs
- Plugin settings page doesn't save options

**Phase to address:**
Phase: **DEPLOY-01 (First Production Deployment)**
- Document exact ZIP creation process
- Test deployment to staging WordPress instance
- Create deployment runbook with troubleshooting steps
- Verify all asset paths work without volume mounts

---

### Pitfall 4: Custom Post Type Permalink Conflicts

**What goes wrong:**
Custom Post Types for specialties, doctors, or locations conflict with existing WordPress pages, taxonomies, or other plugins' rewrite rules, causing 404 errors or wrong templates loading.

**Why it happens:**
- CPT slugs (`especialidades`, `doctores`) conflict with page slugs
- WordPress rewrite rule priority: pages > posts > CPTs
- Taxonomies and CPTs with same rewrite slug
- Not flushing rewrite rules after CPT registration changes
- Pretty permalinks vs. default permalinks behavior differences

**How to avoid:**
1. Use unique rewrite slugs:
   ```php
   register_post_type('artricenter_doctor', [
       'rewrite' => ['slug' => 'doctor-artricenter'], // Unique prefix
       'has_archive' => 'doctores-artricenter'
   ]);
   ```
2. Register CPTs on `init` hook with priority 0 (early)
3. Always flush rewrite rules in activation hook:
   ```php
   register_activation_hook(__FILE__, function() {
       artricenter_register_cpt();
       flush_rewrite_rules();
   });
   ```
4. Test with both pretty permalinks AND default permalinks
5. Check for conflicts using `rewrite_rules_array` filter inspection

**Warning signs:**
- CPT single pages work but archive returns 404
- Page with same slug as CPT overrides CPT archive
- Changing permalink structure breaks CPT URLs
- Category/tag pages show wrong content

**Phase to address:**
Phase: **CPT-01, CPT-02, CPT-03 (Custom Post Type Development)**
- Test CPT URLs immediately after registration
- Verify rewrite rules with Rewrite Rule Inspector plugin
- Document all CPT slugs and taxonomies to avoid conflicts

---

### Pitfall 5: Form Security Vulnerabilities

**What goes wrong:**
Contact forms and booking forms accept and process user input without proper validation, leading to XSS vulnerabilities, header injection, or spam abuse.

**Why it happens:**
- Not escaping output when displaying form-submitted data
- Missing nonce verification on form submission
- Not sanitizing email headers (allowing CC/BCC injection)
- No rate limiting or CAPTCHA
- Trusted user input without `sanitize_*` functions

**How to avoid:**
1. Always include nonces:
   ```php
   wp_nonce_field('artricenter_contact_form', 'contact_nonce');
   // Verify
   if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'artricenter_contact_form')) {
       wp_die('Security check failed');
   }
   ```
2. Sanitize all input:
   ```php
   $name = sanitize_text_field($_POST['name']);
   $email = sanitize_email($_POST['email']);
   $message = sanitize_textarea_field($_POST['message']);
   ```
3. Escape all output:
   ```php
   echo esc_html($user_submitted_data);
   ```
4. Use WordPress HTTP API instead of `mail()`:
   ```php
   wp_mail($to, $subject, $message, $headers);
   ```
5. Implement rate limiting using Transients API:
   ```php
   $rate_limit_key = 'form_submit_' . $ip_address;
   if (get_transient($rate_limit_key)) {
       wp_die('Please wait before submitting again');
   }
   set_transient($rate_limit_key, 1, MINUTE_IN_SECONDS * 5);
   ```

**Warning signs:**
- Form submissions contain HTML/JavaScript that executes when displayed
- Email headers being injected (spam being sent through your forms)
- No nonce field in form HTML
- Direct POST requests succeed without nonce verification

**Phase to address:**
Phase: **FORM-01, FORM-02 (Form Development)**
- Start with secure form template
- Use security scanning plugin before deployment
- Add nonce and sanitization to form development checklist

---

## Technical Debt Patterns

| Shortcut | Immediate Benefit | Long-term Cost | When Acceptable |
|----------|-------------------|----------------|-----------------|
| Inline CSS in plugin files instead of separate stylesheet | Faster development, no asset loading issues | Harder to maintain styles, can't use CSS preprocessing, file size bloat | MVP only, must refactor before adding more than 3 pages |
| Hardcoding content (doctor names, specialties) in PHP instead of database | No database queries needed, immediate display | Can't update content without code changes, defeats WordPress CMS purpose | NEVER - this defeats the entire project purpose |
| Using `query_posts()` instead of `WP_Query` or `get_posts()` | One-line code, familiar to beginners | Breaks main query, causes pagination issues, bad performance | NEVER - always use proper query methods |
| Loading CSS/JS on every page instead of conditionally | Always works, no conditional logic | Slows down entire site, conflicts on pages where not needed | Acceptable for small sites (<10 pages), but should enqueue conditionally |
| Not using WordPress transients for cache | No caching logic to maintain | Slower page loads, unnecessary database queries | During development only, must add caching before production |

## Integration Gotchas

| Integration | Common Mistake | Correct Approach |
|-------------|----------------|------------------|
| WhatsApp sticky button | Hardcoding phone number in template | Store in WordPress Settings API, create admin settings page |
| Google Maps (if used) | Embedding API key directly in code | Store in wp-config.php as constant or environment variable |
| Contact form email | Hardcoding recipient email in plugin | Use Settings API, allow admin to configure email address |
| Image uploads | Using `move_uploaded_file()` to arbitrary locations | Use WordPress `wp_upload_bits()` or `media_handle_upload()` |
| Form submissions | Using `mail()` function directly | Use `wp_mail()` with proper headers and HTML email support |

## Performance Traps

| Trap | Symptoms | Prevention | When It Breaks |
|------|----------|------------|----------------|
| Loading all plugin assets on every page | Slow initial page load, unused CSS/JS blocking rendering | Use `wp_enqueue_scripts` hooks with conditional logic (`is_page()`, `is_singular()`) | At 10+ pages or 5+ plugins |
| Database queries in loops | N+1 query problem, page slows as content grows | Use `WP_Query` with proper parameters, cache results with transients | At 50+ custom post entries |
| Not using object caching | Repeated database queries for same data | Implement Redis/Memcached with WordPress object cache drop-in | At 100+ concurrent users |
- Using `SELECT *` instead of specific columns
- Not adding database indexes to custom tables
- Using `file_get_contents()` instead of WordPress HTTP API

## Security Mistakes

| Mistake | Risk | Prevention |
|---------|------|------------|
| Direct file access without ABSPATH check | PHP files can be executed directly outside WordPress | Always include `defined('ABSPATH') || exit;` at top of PHP files |
| Missing capability checks | Unauthorized users can access admin functionality | Use `current_user_can()` before any admin action |
| Displaying unsanitized data | XSS attacks, malicious script execution | Always escape output with `esc_html()`, `esc_attr()`, `esc_url()` |
| SQL queries without prepare() | SQL injection attacks | Always use `$wpdb->prepare()` for queries with user input |
| Missing nonce verification | CSRF attacks, unauthorized form submissions | Add `wp_nonce_field()` to forms and verify on submission |
| Debug mode enabled in production | Exposes file paths, database queries, sensitive info | Set `WP_DEBUG` to `false` in production, never display errors to users |
| Insecure file uploads | Remote code execution via uploaded files | Validate file types, use WordPress upload functions, store outside webroot |
| Hardcoded credentials | Credentials exposed in code repository | Store in wp-config.php constants or environment variables |
| AJAX without privilege checks | Unauthorized AJAX actions | Check `current_user_can()` and `check_ajax_referer()` in AJAX handlers |
| Not updating WordPress/plugins | Known vulnerabilities exploitable | Keep WordPress core and all plugins updated, use automatic updates for security patches |

## UX Pitfalls

| Pitfall | User Impact | Better Approach |
|---------|-------------|-----------------|
| No loading indicators on form submissions | Users submit multiple times, duplicate entries | Add spinner or "Submitting..." text, disable submit button during AJAX |
| No confirmation after form submission | Users unsure if submission succeeded | Show success message or redirect to thank you page |
| Sticky buttons covering mobile content | Can't access important information on mobile | Add responsive media queries to adjust position/size on small screens |
| Generic error messages | Users don't know how to fix problems | Show specific error messages ("Invalid email format" vs. "Error occurred") |
| No responsive menu handling | Can't navigate on mobile devices | Implement hamburger menu for mobile, test touch targets are 44px+ |
| Images not optimized | Slow page loads, poor mobile experience | Use WordPress image compression, lazy loading, responsive images |
| Missing alt text on images | Poor accessibility, SEO penalty | Add alt text fields to image uploaders in admin |
| Color contrast issues | Poor readability, accessibility violations | Follow WCAG AA guidelines (4.5:1 contrast ratio for text) |

## "Looks Done But Isn't" Checklist

- [ ] **Contact Form:** Often missing spam protection — verify CAPTCHA or rate limiting implemented
- [ ] **Custom Post Types:** Often missing template files — verify single-{cpt}.php and archive-{cpt}.php exist
- [ ] **Sticky Buttons:** Often covering content on mobile — test on 375px width viewport
- [ ] **Doctor Profiles:** Often missing social media links — verify all social links open in new tabs with proper rel attributes
- [ ] **Responsive Images:** Often missing srcset attributes — verify images serve different sizes for different devices
- [ ] **Form Validation:** Often missing client-side validation — verify HTML5 validation or JavaScript validation before submission
- [ ] **Error Handling:** Often missing graceful failure modes — test with invalid data, network failures
- [ ] **Admin Interface:** Often missing help text or descriptions — verify each form field has label and help text
- [ ] **Caching:** Often missing cache invalidation — verify transients clear when content updates
- [ ] **Browser Compatibility:** Often only tested in Chrome — verify functionality in Firefox, Safari, Edge
- [ ] **Accessibility:** Often missing keyboard navigation — verify all interactive elements work with Tab key
- [ ] **SEO Metadata:** Often missing meta descriptions/OG tags — verify each page has proper SEO fields

## Recovery Strategies

| Pitfall | Recovery Cost | Recovery Steps |
|---------|---------------|----------------|
| CSS Namespace Collision | MEDIUM | 1. Add namespace prefix to all CSS selectors 2. Test with multiple themes 3. May need to refactor HTML to use new class names |
| CPT Permalink Conflict | LOW | 1. Change CPT rewrite slug to unique value 2. Flush rewrite rules 3. Test all CPT URLs 4. Add 301 redirects if needed |
| Form Security Vulnerability | HIGH | 1. Immediately deactivate vulnerable plugin 2. Add nonce verification and sanitization 3. Test with penetration testing tools 4. Deploy fix immediately |
| wp-admin Deployment Failure | LOW | 1. Verify ZIP file contents include all files 2. Check file permissions (644 for files, 755 for directories) 3. Re-upload via FTP if wp-admin fails |
| Performance Degradation | MEDIUM | 1. Identify slow queries with Query Monitor plugin 2. Add transients for cached data 3. Optimize database queries 4. Consider object caching |
| Missing Dependency | LOW | 1. Check PHP version and WordPress version requirements 2. Verify required functions exist before using them 3. Add graceful degradation or dependency checks |
| Asset Loading Issues | LOW | 1. Verify `wp_enqueue_scripts` hooks are properly used 2. Check file paths with `plugins_url()` 3. Clear browser cache and test again |

## Pitfall-to-Phase Mapping

| Pitfall | Prevention Phase | Verification |
|---------|------------------|--------------|
| CSS Namespace Collisions | STRUCT-02 (CSS Conversion) | Test with 3 different themes, verify no style conflicts in browser dev tools |
| Plugin Activation Dependencies | DOCKER-01 (Initial Setup) | Activate/deactivate cycle 10 times, verify all error messages appear correctly |
| wp-admin Deployment Failures | DEPLOY-01 (First Production Deployment) | Deploy to staging, verify all functionality matches Docker environment |
| CPT Permalink Conflicts | CPT-01, CPT-02, CPT-03 | Use Rewrite Rules Inspector plugin, verify all CPT URLs return 200 status |
| Form Security Vulnerabilities | FORM-01, FORM-02 | Run security scanner (e.g., Wordfence), test XSS attempts, verify nonces present |
| Performance Issues | All phases (ongoing) | Use Query Monitor plugin, target < 500ms page load time, < 50 database queries |
| Asset Loading Problems | STRUCT-01 (Structure Plugin) | Check all assets load with correct URLs, no 404s in browser console |
| Mobile UX Issues | All phases | Test on actual mobile devices (not just dev tools), verify all touch targets work |
| Accessibility Failures | All phases | Run WAVE or Lighthouse accessibility audit, target WCAG AA compliance |
| Caching Issues | DEPLOY-01 | Verify transients expire correctly, content updates appear immediately after cache clear |

## Sources

- **WordPress Plugin Handbook** - Official documentation on plugin basics, hooks, and security
  - https://developer.wordpress.org/plugins/plugin-basics/ (HIGH confidence - official source)
  - https://developer.wordpress.org/plugins/ (HIGH confidence - official source)
- **WordPress Codex** - Plugin development best practices
  - https://codex.wordpress.org/Writing_a_Plugin (HIGH confidence - official source)
- **Common WordPress Plugin Security Vulnerabilities** - SQL injection, XSS, CSRF patterns
  - Based on established WordPress security best practices (MEDIUM confidence - general knowledge)
- **WordPress Custom Post Type Development** - Rewrite rules, permalink structure
  - Based on WordPress CPT registration patterns (MEDIUM confidence - general knowledge)
- **Docker vs Production WordPress** - Environment differences, deployment challenges
  - Based on containerized development vs. traditional hosting patterns (MEDIUM confidence - general knowledge)
- **Tailwind CSS to WordPress CSS Migration** - Utility class conversion challenges
  - Based on utility-first CSS framework migration patterns (LOW confidence - limited specific documentation found)

## Notes on Research Confidence

**HIGH Confidence Areas:**
- WordPress plugin structure and hooks (official documentation)
- Plugin security best practices (nonces, sanitization, escaping)
- WordPress APIs (Options, Settings, HTTP, database)
- Plugin activation/deactivation lifecycle

**MEDIUM Confidence Areas:**
- CPT permalink conflicts and rewrite rules (well-documented but complex)
- Docker vs production deployment differences (general patterns, not WordPress-specific)
- Performance optimization for WordPress (general web performance principles)

**LOW Confidence Areas:**
- Tailwind to WordPress CSS conversion (specific migration patterns not well-documented)
- Static site to WordPress migration best practices (general web migration patterns applied)
- wp-admin deployment automation (manual deployment is standard, automated patterns vary)

**Gaps Requiring Phase-Specific Research:**
- Exact CSS namespace strategy (needs testing with Artricenter's specific Tailwind setup)
- Production hosting environment specifics (requires checking actual production WordPress configuration)
- Asset optimization for WordPress (requires testing with actual image sizes and performance requirements)

---
*Pitfalls research for: WordPress Migration (Static Site to WordPress with Custom Plugins)*
*Researched: 2026-03-19*
