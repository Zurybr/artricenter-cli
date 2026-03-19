# Migración de Artricenter a WordPress

## What This Is

Migración del sitio estático actual de Artricenter (clínica de reumatología construido con Astro + TypeScript + Tailwind CSS) a WordPress mediante plugins modulares. El sitio actual incluye páginas de inicio, especialidades médicas, tratamiento integral, club de vida y salud, y contacto. El objetivo es replicar la funcionalidad y el diseño visual en WordPress para permitir actualizaciones de contenido por personal no técnico.

## Core Value

Replicar completamente el sitio actual de Artricenter en WordPress mediante plugins modulares que permitan mantenimiento de contenido por staff administrativo sin necesidad de desarrolladores, preservando la identidad visual y todas las funcionalidades actuales.

## Requirements

### Validated

- ✓ **DOCKER-01** — Phase 1: Entorno Docker Compose con WordPress 6.9.4, PHP 8.2, MySQL 8.0, Nginx, WP-CLI
- ✓ **STRUCT-01** — Phase 1: Plugin de estructura con header responsive, footer con 3 sucursales, navegación móvil/desktop
- ✓ **STRUCT-02** — Phase 1: CSS puro con namespace .artricenter-, variables CSS, smooth scroll

### Active

- [ ] **PAGES-01**: Página de inicio con secciones: Artricenter, Nuestra Historia, Nuestros Médicos, Misión/Visión/Valores
- [ ] **PAGES-01**: Página de inicio con secciones: Artricenter, Nuestra Historia, Nuestros Médicos, Misión/Visión/Valores
- [ ] **PAGES-02**: Página de especialidades médicas
- [ ] **PAGES-03**: Página de tratamiento médico integral
- [ ] **PAGES-04**: Página de Club de Vida y Salud
- [ ] **PAGES-05**: Página de contacto
- [ ] **CPT-01**: Custom Post Type para especialidades médicas
- [ ] **CPT-02**: Custom Post Type para doctores/médicos
- [ ] **CPT-03**: Custom Post Type para sucursales/ubicaciones
- [ ] **FORM-01**: Formulario de contacto funcional
- [ ] **FORM-02**: Formularios adicionales (reservas, consultas)
- [ ] **STICKY-01**: Botones sticky de WhatsApp y Dra. Edith
- [ ] **DEPLOY-01**: Proceso de despliegue de plugins a WordPress producción via wp-admin

### Out of Scope

- **Migración automática de contenido** — El contenido se migrará manualmente después de tener los plugins funcionales
- **Temas de WordPress de terceros** — Desarrollo personalizado, no usar temas preexistentes
- **E-commerce** — No se requieren funcionalidades de venta online
- **Blog/Noticias** — No está en el alcance inicial (posible extensión futura)
- **Sistema de reservas de citas en tiempo real** — Los formularios son suficientes inicialmente
- **Mantenimiento de Tailwind en WordPress** — Conversión a CSS puro

## Context

**Sitio actual:**
- Framework: Astro 6.0.7 con TypeScript
- Estilos: Tailwind CSS 4.2.2
- Estructura: Component-based static site
- Páginas: index, especialidades, tratamiento-medico-integral, club-vida-y-salud, contactanos
- Características: Header responsive, footer profesional, botones sticky (WhatsApp, Dra. Edith), grid de doctores, secciones de contenido con animaciones sutiles

**WordPress destino:**
- Instancia WordPress existente en producción
- Acceso via wp-admin para administración
- Acceso API disponible (REST API)
- Entorno Docker local para desarrollo/pruebas

**Contenido actual:**
- Información de clínica de reumatología (Artricenter)
- 4 sucursales: La Raza, Zaragoza, Atizapán, Viaducto
- 3 doctores principales con fotos, especialidades y redes sociales
- Secciones: Misión, Visión, Valores
- Tratamientos para: artritis reumatoide, artrosis, fibromialgia, espondilitis anquilosante

## Constraints

- **Docker solo para desarrollo**: Docker es entorno de pruebas local, NO será la infraestructura de producción
- **Despliegue manual**: Plugins se subirán a producción via wp-admin, no deployment automatizado
- **CSS puro**: Convertir Tailwind actual a CSS nativo, no mantener Tailwind en WordPress
- **Plugins modulares**: Cada funcionalidad principal en su propio plugin para facilidad de mantenimiento
- **Compatibilidad**: Plugins deben ser compatibles con WordPress actual (versión 6.x+) y no romper funcionalidades existentes
- **Sin interrupción**: No afectar WordPress de producción durante desarrollo

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Docker local para desarrollo | Aislamiento del entorno de producción, pruebas sin riesgo | ✓ Good |
| Single plugin con clases separadas | Header/footer/nav/estilos interdependen; separar crearía dependencias artificiales | ✓ Good |
| CSS puro vs Tailwind en WordPress | Mayor compatibilidad, menos dependencias, mejor performance | ✓ Good |
| Namespace .artricenter- en CSS | Previene conflictos con temas de WordPress | ✓ Good |
| Template tags para integración | Flexible para temas, no inyección automática | ✓ Good |
| Custom hooks para extensibilidad | Permite que plugins futuros inyecten contenido | ✓ Good |
| wp-admin para despliegue | Acceso disponible, sin necesidad de FTP/SSH directo | — Pending |

---
*Last updated: 2026-03-19 after Phase 1*
