# Stack Research

**Domain:** WordPress Plugin Development with Docker Migration
**Researched:** 2026-03-19
**Confidence:** HIGH

## Recommended Stack

### Core Technologies

| Technology | Version | Purpose | Why Recommended |
|------------|---------|---------|-----------------|
| WordPress | 6.9.4 | Production target version | Current stable release with latest security patches and features |
| PHP | 8.2 - 8.3 | Plugin development language | WordPress 6.9+ officially supports PHP 8.2, 8.3, 8.4, and 8.5. PHP 8.2 provides best stability/feature balance |
| Docker WordPress Image | 6.9.4-php8.2-apache | Local development environment | Official WordPress Docker image with Apache and PHP 8.2 |
| MySQL | 8.0 | Database for local development | Official WordPress Docker Compose setup uses MySQL 8.0 |
| Docker Compose | 3.x | Container orchestration | Standard for multi-container Docker setups |

### Supporting Libraries

| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| PHPUnit | 9.x - 10.x | Plugin testing framework | When writing unit tests for plugin logic. Official WordPress testing framework |
| PHPUnit Polyfills | Latest | PHPUnit compatibility layer | Required for WordPress test suite. Handles version compatibility |
| PHP CodeSniffer (PHPCS) | 3.x | Code quality linting | For enforcing WordPress coding standards |
| WordPress Coding Standards (WPCS) | Latest | WordPress-specific PHPCS rules | For checking code against WordPress coding standards |
| WP-CLI | 2.12.0+ | Command-line WordPress management | For efficient WordPress development tasks (install, plugin management, etc.) |

### Development Tools

| Tool | Purpose | Notes |
|------|---------|-------|
| Docker Desktop | Docker container management | Required for running local WordPress environment |
| VS Code with PHP Intelephense | IDE with PHP intelligence | Provides autocomplete, type checking for PHP development |
| Query Monitor plugin | Debug queries, hooks, performance | Install in local WordPress for debugging |
| Debug Bar plugin | Debug WordPress internals | Complements Query Monitor for development |

## Installation

### Docker WordPress Environment

```bash
# Create docker-compose.yml (see Stack Patterns below)
# Start WordPress environment
docker compose up -d

# Access WordPress at http://localhost:8080
# Access WordPress CLI
docker compose exec wordpress wp [command]
```

### Plugin Development Tools

```bash
# Core testing framework
composer require --dev phpunit/phpunit
composer require --dev yoast/phpunit-polyfills

# Code quality tools
composer require --dev squizlabs/php_codesniffer
composer require --dev wp-coding-standards/wpcs

# Configure PHPCS to use WordPress standards
vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
```

### CSS Conversion Tools

For converting Tailwind CSS to pure CSS (project requirement):

```bash
# Install PostCSS and tools for analyzing current Tailwind setup
npm install -D postcss postcss-cli

# Tailwind CLI (for analyzing current build output)
npm install -D tailwindcss

# CSS optimization
npm install -D cssnano postcss-preset-env
```

## Alternatives Considered

| Recommended | Alternative | When to Use Alternative |
|-------------|-------------|-------------------------|
| Docker (Apache) | Docker (Nginx + PHP-FPM) | If you prefer Nginx or need FPM-specific performance tuning. FPM variant requires reverse proxy setup |
| PHP 8.2 | PHP 8.4 or 8.5 | If you need latest PHP features. Use with caution: ensure production WordPress supports it |
| Manual wp-admin upload | WP-CLI deployment | If you have SSH access and prefer command-line deployment. Not applicable for this project (wp-admin only) |
| Pure CSS | CSS-in-JS or CSS frameworks | If you need dynamic styling. Not recommended for WordPress plugins due to performance overhead |

## What NOT to Use

| Avoid | Why | Use Instead |
|-------|-----|-------------|
| PHP 7.4 or earlier | End-of-life, no security updates, WordPress 6.9+ requires PHP 8.0+ | PHP 8.2 or 8.3 |
| MAMP/WAMP/XAMPP | Unnecessary complexity when using Docker, harder to replicate exactly | Docker Compose setup |
| Tailwind in WordPress | Project constraint: convert to pure CSS for better compatibility and performance | Pure CSS converted from Tailwind |
| Direct database modifications | Breaks WordPress data integrity, hard to maintain | WordPress APIs (Custom Post Types, Meta, etc.) |
| Custom theme files | Project requires modular plugins for easier maintenance | Custom plugins for all functionality |
| jQuery in plugins | WordPress includes jQuery but modern vanilla JS is preferred | Vanilla JavaScript with WordPress hooks |
| Automating deployment to production | Project constraint: only wp-admin access available | Manual zip upload via wp-admin |

## Stack Patterns by Variant

**If developing locally with hot-reload:**
- Use Docker Compose with volume mounts for plugins directory
- Because: Changes to plugin files are immediately reflected without rebuilding containers
- Mount local plugin directory to `/var/www/html/wp-content/plugins/your-plugin/`

**If testing WordPress core compatibility:**
- Use WordPress Docker beta tag for testing against upcoming versions
- Because: Ensures plugins work with future WordPress releases before they're stable
- Example: `image: wordpress:beta-7.0-php8.2-apache`

**If debugging complex plugin issues:**
- Install Query Monitor and Debug Bar in local WordPress
- Because: Provides visibility into database queries, hooks, and execution flow
- Access via WordPress admin: Plugins → Add New → search plugin name

**If testing with multiple PHP versions:**
- Create multiple Docker Compose services with different PHP variants
- Because: Ensures plugin works across PHP 8.2, 8.3, 8.4
- Example: `wordpress-php82`, `wordpress-php83`, `wordpress-php84`

## Version Compatibility

| Package A | Compatible With | Notes |
|-----------|-----------------|-------|
| WordPress 6.9.4 | PHP 8.2, 8.3, 8.4, 8.5 | PHP 8.2 recommended for stability |
| WordPress 6.9.4 | MySQL 5.7+, MariaDB 10.2+ | MySQL 8.0 recommended (matches Docker image) |
| PHPUnit 9.x | PHP 7.3 - 8.2 | Use PHPUnit 9.x for PHP 8.2 compatibility |
| PHPUnit 10.x | PHP 8.1 - 8.4 | Use PHPUnit 10.x for PHP 8.3+ projects |
| WordPress Coding Standards | PHPCS 3.x | Requires PHPCS 3.5.0 or higher |
| WP-CLI 2.12.0 | WordPress 3.7+ | All modern WordPress versions supported |

## Docker Compose Setup Pattern

```yaml
# docker-compose.yml for WordPress plugin development
services:
  wordpress:
    image: wordpress:6.9.4-php8.2-apache
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wpuser
      WORDPRESS_DB_PASSWORD: wppass
      WORDPRESS_DB_NAME: wordpress
      WORDPRESS_DEBUG: 1
    volumes:
      - wordpress_data:/var/www/html
      - ./plugins:/var/www/html/wp-content/plugins
    depends_on:
      - db

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wpuser
      MYSQL_PASSWORD: wppass
      MYSQL_RANDOM_ROOT_PASSWORD: '1'
    volumes:
      - db_data:/var/lib/mysql

  wpcli:
    image: wordpress:cli-2.12.0-php8.2
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wpuser
      WORDPRESS_DB_PASSWORD: wppass
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - wordpress_data:/var/www/html
      - ./plugins:/var/www/html/wp-content/plugins
    depends_on:
      - wordpress
      - db

volumes:
  wordpress_data:
  db_data:
```

## Plugin Development Workflow

1. **Set up local environment**: `docker compose up -d`
2. **Install WordPress**: `docker compose exec wpcli wp core install --url=http://localhost:8080 --title="Dev Site" --admin_user=admin --admin_password=admin --admin_email=admin@example.com`
3. **Create plugin directory**: `mkdir -p plugins/artricenter-structure`
4. **Create plugin header**: Standard WordPress plugin file header
5. **Develop plugin features**: Use WordPress hooks, filters, and APIs
6. **Test locally**: Access http://localhost:8080/wp-admin
7. **Code quality check**: `vendor/bin/phpcs --standard=WordPress plugins/artricenter-structure`
8. **Package for deployment**: Create ZIP file of plugin directory
9. **Deploy to production**: Upload ZIP via WordPress admin → Plugins → Add New → Upload Plugin

## CSS Conversion Strategy

Since the project requires converting Tailwind CSS to pure CSS:

1. **Analyze current Tailwind usage**: Review `tailwind.config.mjs` and component usage
2. **Extract design tokens**: Identify colors, spacing, typography from Tailwind config
3. **Create CSS variables**: Convert design tokens to CSS custom properties
4. **Write component CSS**: Convert Tailwind utility classes to semantic CSS classes
5. **Optimize**: Use cssnano for minification and cleanup
6. **Test in WordPress**: Enqueue CSS in plugin and verify visual parity

## Sources

- Docker Hub Official WordPress Image — HIGH confidence. Verified current WordPress versions (6.9.4), PHP variants (8.2, 8.3, 8.4, 8.5), and official Docker Compose setup example. https://hub.docker.com/_/wordpress
- WordPress Plugin Developer Handbook — HIGH confidence. Official WordPress plugin development documentation covering security, hooks, APIs, and best practices. https://developer.wordpress.org/plugins/
- WordPress Core PHPUnit Testing Documentation — HIGH confidence. Official guide for PHPUnit testing with WordPress, including setup workflows and polyfill requirements. https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/
- Tailwind CSS Preprocessor Documentation — MEDIUM confidence. Explains Tailwind's PostCSS architecture and recommendations for using with other CSS tools. Confirms that Tailwind is a PostCSS plugin and discusses build-time optimizations. https://tailwindcss.com/docs/using-with-preprocessors

---
*Stack research for: WordPress Plugin Development with Docker Migration*
*Researched: 2026-03-19*
