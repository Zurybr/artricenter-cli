# Deployment Notes - Artricenter WordPress Setup

## Date: 2026-03-20

## Changes Deployed to Docker

### 1. CSS Styles Added
- **File**: `wp-content/plugins/artricenter-structure/assets/css/content.css`
- **Commit**: `e9dc928` - feat(artricenter-structure): add content CSS styles for pages and shortcodes

**Styles include:**
- Homepage sections (hero gradient, history, doctors grid, mission cards)
- Doctors grid with hover effects and responsive layout
- Specialties list with card design
- Mission/Vision/Values cards with color coding (blue, green, orange)
- Generic page layouts
- Responsive breakpoints for mobile, tablet, desktop

### 2. Plugin Enqueue Updates
- **File**: `wp-content/plugins/artricenter-structure/artricenter-structure.php`
- **Change**: Added enqueue for `content.css` (depends on `variables.css`)

### 3. Database Changes (via PHP API in Docker)

#### Page Templates Assigned
- **Inicio** (ID: 6) → `page-homepage.php`
- **Especialidades** (ID: 7) → `page-especialidades.php`
- **Club de Vida y Salud** (ID: 9) → `page-club-vida.php`
- **Contacto** (ID: 10) → `page-contacto.php`

#### Page Content Updated
All pages updated with professional welcome text describing Artricenter services.

#### Sample Content Created
**3 Doctors:**
- Dra. María González - Reumatología - Clínica Central
- Dr. Carlos Mendoza - Reumatología Pediátrica - Clínica Norte
- Dra. Ana Rodríguez - Osteoporosis - Clínica Central

**3 Specialties:**
- Reumatología
- Osteoporosis
- Artritis

## Server Deployment Notes

### When deploying to production server:

1. **Pull latest code:**
   ```bash
   git pull origin main
   ```

2. **Copy plugins to WordPress:**
   ```bash
   cp -r wp-content/plugins/artricenter-structure /path/to/wp-content/plugins/
   cp -r wp-content/plugins/artricenter-content /path/to/wp-content/plugins/
   ```

3. **Activate plugins in WordPress Admin:**
   - Artricenter Structure
   - Artricenter Content Engine

4. **Assign page templates:**
   - Edit each page in WordPress Admin
   - Select appropriate template from "Template" dropdown
   - Update page

5. **Flush permalinks:**
   - Settings → Permalinks → Save Changes

6. **Verify CSS loading:**
   - View page source
   - Check for `artricenter-structure-content.css` in `<head>`

## URLs to Test

- Homepage: http://localhost:8080/
- Especialidades: http://localhost:8080/especialidades/
- Club de Vida: http://localhost:8080/club-vida-y-salud/
- Contacto: http://localhost:8080/contacto/

## Known Issues

- Page templates must be assigned manually in WordPress Admin (or via SQL)
- Sample doctors/specialties created for demo - replace with real data
- Doctor images are placeholders - upload featured images

## Next Steps

1. Replace sample content with real data
2. Upload doctor photos via WordPress Media Library
3. Customize mission/vision/values content (currently hardcoded in template)
4. Add analytics/tracking
5. SEO optimization
