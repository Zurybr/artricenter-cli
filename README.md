# Artricenter WordPress Development Environment

Docker-based development environment for WordPress 6.9.4 with PHP 8.2-FPM, MySQL 8.0, Nginx, and WP-CLI.

## Prerequisites

- Docker (version 20.10 or later)
- Docker Compose (version 2.0 or later)

## Quick Start

1. **Start all services:**
   ```bash
   docker compose up -d
   ```

2. **Wait for containers to start** (~30 seconds)

3. **Visit WordPress setup:**
   Open http://localhost:8080 in your browser

4. **Complete WordPress installation:**
   - Select language
   - Site name: Artricenter
   - Username/password (admin credentials)
   - Email address
   - Click "Install WordPress"

## Architecture

This environment includes 4 services:

| Service | Container | Image | Purpose |
|---------|-----------|-------|---------|
| wordpress | artricenter_wp | wordpress:6.9.4-php8.2-fpm-alpine | WordPress core with PHP-FPM |
| nginx | artricenter_nginx | nginx:alpine | Web server and reverse proxy |
| db | artricenter_db | mysql:8.0 | MySQL database |
| wpcli | artricenter_wpcli | wordpress:cli-php8.2 | WordPress CLI tool |

**Network:** All services communicate via `artricenter_network`

**Volumes:**
- `db_data`: Persistent MySQL storage
- `./wp-content`: Hot-reload development (see below)

## WP-CLI Usage

Run WP-CLI commands without entering the wpcli container:

```bash
# Check WordPress version
docker compose exec wpcli wp core version

# List plugins
docker compose exec wpcli wp plugin list

# Install a plugin
docker compose exec wpcli wp plugin install akismet --activate

# Clear cache
docker compose exec wpcli wp cache flush
```

## Hot-Reload Development

Plugin files in `./wp-content/` sync immediately to the WordPress container:

```bash
# Create a custom plugin directory
mkdir -p wp-content/plugins/artricenter-custom

# Edit files locally
nano wp-content/plugins/artricenter-custom/artricenter.php

# Changes appear instantly in WordPress at http://localhost:8080
# No container rebuild needed!
```

**Note:** Theme and plugin development happens in `./wp-content/` on your host machine. The container reflects changes immediately via volume mounting.

## Troubleshooting

### Check service status
```bash
docker compose ps
```

All containers should show status "Up".

### View logs
```bash
# All services
docker compose logs

# Specific service
docker compose logs wordpress
docker compose logs nginx
docker compose logs db
```

### Restart services
```bash
docker compose restart
```

### Stop all services
```bash
docker compose down
```

### Remove everything (including database)
```bash
docker compose down -v
```

### WordPress not loading

1. Check containers are running: `docker compose ps`
2. Check nginx logs: `docker compose logs nginx`
3. Wait longer for MySQL initialization (first start can take 60s)
4. Try restarting: `docker compose restart`

### Port 8080 already in use

Edit `docker-compose.yml` and change the nginx port mapping:
```yaml
ports:
  - "8081:80"  # Use 8081 instead of 8080
```

Then restart: `docker compose down && docker compose up -d`

## Database Access

**From host:** Port 3306 is not exposed by default. Use the wpcli container:

```bash
# Access MySQL via WP-CLI
docker compose exec wpcli wp db query "SHOW TABLES;"
```

**To expose port 3306:** Add to db service in docker-compose.yml:
```yaml
ports:
  - "3306:3306"
```

Then connect with:
- Host: localhost
- Port: 3306
- User: wpuser
- Password: wpsecret
- Database: artricenter

## Environment Variables

Database credentials are configured in docker-compose.yml:

| Variable | Value |
|----------|-------|
| MYSQL_DATABASE | artricenter |
| MYSQL_USER | wpuser |
| MYSQL_PASSWORD | wpsecret |
| MYSQL_ROOT_PASSWORD | rootsecret |

**Security:** These are development defaults. Change for production deployments.

## PHP Configuration

Custom PHP settings are mounted from `docker/php/uploads.ini`:

- `upload_max_filesize`: 64M (media upload limit)
- `post_max_size`: 64M (form submission limit)
- `max_execution_time`: 300s (5 minutes)
- `memory_limit`: 256M (WordPress minimum)

To modify, edit `docker/php/uploads.ini` and restart:
```bash
docker compose restart wordpress
```

## Next Steps

After WordPress installation:

1. **Install essential plugins:**
   ```bash
   docker compose exec wpcli wp plugin install query-monitor --activate
   docker compose exec wpcli wp plugin install wp-crontrol --activate
   ```

2. **Configure permalinks:**
   - Settings → Permalinks → Post name
   - Save changes

3. **Start plugin development:**
   ```bash
   mkdir -p wp-content/plugins/artricenter-custom
   cd wp-content/plugins/artricenter-custom
   # Create your plugin here
   ```

4. **Enable debugging:**
   Add to `wp-content/debug.log`:
   ```php
   define( 'WP_DEBUG', true );
   define( 'WP_DEBUG_LOG', true );
   define( 'WP_DEBUG_DISPLAY', false );
   ```

## Support

For issues or questions:
1. Check logs: `docker compose logs`
2. Verify service status: `docker compose ps`
3. Review WordPress Codex: https://developer.wordpress.org/
