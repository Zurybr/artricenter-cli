# Artricenter - Sitio Web Institucional

Sitio web estático para Artricenter, clínica especializada en el diagnóstico y tratamiento de enfermedades reumáticas.

## 🌐 Sitio en Producción

- **GitHub Pages**: https://zurybr.github.io/artricenter-cli/
- **Dominio**: https://artricenter.com (próximamente)

## 🚀 Tecnologías

- **Framework**: Astro 6.0.7
- **Estilado**: Tailwind CSS
- **Despliegue**: GitHub Pages
- **Node.js**: v22

## 📋 Contenido

El sitio incluye información sobre:

- **Quiénes Somos**: Historia, equipo médico y valores
- **Especialidades**:
  - Artrosis / Osteoartrosis
  - Artritis Reumatoide
  - Fibromialgia
  - Espondilitis Anquilosante
  - Reumatismos de Partes Blandas
- **Tratamiento Médico Integral**: PAIPER
- **Club Vida y Salud**: Programas de bienestar
- **Contacto**: Información de ubicación y contacto

## 🏥 Sucursales

- **La Raza**: Calzada Vallejo 233, GAM, CDMX
- **Atizapán**: Blvd. Adolfo López Mateos 65, Edo. Méx
- **Viaducto**: Viaducto Río de la Piedad 130, Venustiano Carranza, CDMX

## 🛠️ Desarrollo

### Requisitos Previos

- Node.js v22 o superior
- npm

### Instalación

```bash
# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev
```

El sitio estará disponible en http://localhost:4321

### Construcción para Producción

```bash
# Construir sitio estático
npm run build
```

Los archivos construidos se generan en la carpeta `dist/`

## 📦 Despliegue

El sitio se despliega automáticamente a GitHub Pages cuando se hace push a la rama `main`.

### Flujo de Despliegue

1. Hacer push de cambios a `main`
2. GitHub Actions construye el proyecto con Node.js v22
3. Se corrigen los paths para GitHub Pages (`/artricenter-cli/`)
4. Se despliega a la rama `gh-pages`
5. GitHub Pages publica el sitio automáticamente

## 🎨 Características Técnicas

- **Mobile-first design**: Navegación responsive optimizada para móviles
- **Menú hamburguesa**: Panel deslizante en dispositivos móviles
- **Sticky header**: Header fijo con navegación siempre visible
- **Sticky buttons**: Botones flotantes para contacto WhatsApp
- **Scroll-mt-20**: Compensación de scroll para header sticky
- **SEO optimizado**: Meta tags, Open Graph, Twitter Cards
- **Accesibilidad**: Navegación por teclado, ARIA labels
- **Performance**: Imágenes optimizadas (.avif), CSS crítico inline

## 📁 Estructura del Proyecto

```
src/
├── components/
│   ├── Header.astro       # Header con navegación responsive
│   ├── Navigation.astro   # Navegación desktop
│   └── Footer.astro       # Footer con sucursales
├── layouts/
│   └── Layout.astro        # Layout principal
├── pages/
│   ├── index.astro                      # Página principal
│   ├── especialidades.astro             # Especialidades
│   ├── tratamiento-medico-integral.astro # Tratamiento PAIPER
│   ├── club-vida-y-salud.astro         # Club Vida y Salud
│   └── contactanos.astro                # Contacto
└── config/
    └── navigation.ts      # Configuración de navegación
```

## 🔧 Configuración

### Base Path

El sitio usa `/artricenter-cli/` como base path para GitHub Pages. En local el desarrollo usa `/`.

### Assets

- Imágenes en `public/assets/` se copian a la raíz del build
- Astro procesa automáticamente los paths durante el build

## 📱 Navegación

El sitio tiene navegación jerárquica:

- **Quienes Somos**
  - Artricenter
  - Nuestra Historia
  - Nuestros Médicos
  - Misión | Visión | Valores

- **Especialidades**
  - Artrosis / Osteoartrosis
  - Artritis Reumatoide
  - Fibromialgia
  - Espondilitis Anquilosante
  - Reumatismos de Partes Blandas

- **Tratamiento Médico Integral**
  - Diagnóstico
  - PAIPER

- **Club Vida y Salud**
  - Club Vida y Salud
  - Testimonios

- **Contáctanos**
  - Blog
  - Contáctanos

## 🐛 Troubleshooting

### El sitio no se ve en GitHub Pages

1. Verificar que el workflow terminó exitosamente en Actions
2. Esperar 2-5 minutos para que GitHub Pages actualice
3. Limpiar caché del navegador (Ctrl+F5 o Cmd+Shift+R)

### Las imágenes no cargan

Verificar que los paths en los HTML incluyan `/artricenter-cli/assets/`

### La navegación no funciona

Verificar que los href incluyan `/artricenter-cli/` para páginas internas

## 📄 Licencia

Este proyecto es propiedad de Artricenter. Todos los derechos reservados.

## 👨‍💻 Desarrollo

Desarrollado con ❤️ usando Astro y Tailwind CSS.
