# Astro Migration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Migrate Artricenter website from static HTML + JavaScript navigation to Astro with Tailwind CSS and component-based architecture

**Architecture:** Server-side rendering with top-down data flow, component-based architecture with type-safe configuration, Tailwind CSS for styling

**Tech Stack:** Astro, TypeScript, Tailwind CSS, Vite

---

## File Structure

```
src/
├── layouts/
│   └── Layout.astro              # Base HTML structure, global head, scripts
├── components/
│   ├── Header.astro              # Logo, CTA container, renders Navigation
│   ├── Navigation.astro          # Desktop menu, mobile drawer, dropdowns
│   ├── Footer.astro              # Site footer with links
│   ├── PageSection.astro         # Generic content section component
│   └── ContentSection.astro      # Reusable content sections
├── pages/
│   ├── index.astro               # Main page (Quienes Somos)
│   ├── especialidades.astro      # Specialties page
│   ├── tratamiento-medico-integral.astro  # Treatment page
│   ├── club-vida-y-salud.astro   # Patient resources page
│   └── contactanos.astro         # Contact page
├── config/
│   └── navigation.ts             # Type-safe navigation configuration
└── styles/
    └── global.css                # Minimal global styles
```

---

### Task 1: Initialize Astro Project

**Files:**
- Create: `package.json`
- Create: `astro.config.mjs`
- Create: `tsconfig.json`
- Create: `tailwind.config.mjs`
- Create: `src/styles/global.css`

- [ ] **Step 1: Initialize Astro with Tailwind**

Run: `npm create astro@latest . -- --template minimal --yes --install --git`
Expected: Astro project created with dependencies

- [ ] **Step 2: Install Tailwind CSS integration**

Run: `npx astro add tailwind --yes`
Expected: Tailwind CSS configured

- [ ] **Step 3: Install TypeScript dependencies**

Run: `npm install -D @types/node`
Expected: TypeScript types installed

- [ ] **Step 4: Configure Astro for strict type checking**

Modify: `tsconfig.json`
```json
{
  "extends": "astro/tsconfigs/strict",
  "compilerOptions": {
    "baseUrl": ".",
    "paths": {
      "@/*": ["./src/*"]
    }
  }
}
```

- [ ] **Step 5: Create global styles**

Create: `src/styles/global.css`
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Minimal global overrides */
@layer base {
  html {
    scroll-behavior: smooth;
  }
}
```

- [ ] **Step 6: Commit Astro setup**

```bash
git add .
git commit -m "feat: initialize Astro project with Tailwind CSS"
```

---

### Task 2: Create Navigation Configuration

**Files:**
- Create: `src/config/navigation.ts`
- Test: `src/config/__tests__/navigation.test.ts`

- [ ] **Step 1: Write navigation config test**

Create: `src/config/__tests__/navigation.test.ts`
```typescript
import { describe, it, expect } from 'vitest';
import { navItems, validateNavItems } from '../navigation';

describe('Navigation Config', () => {
  it('should have valid structure', () => {
    expect(() => validateNavItems(navItems)).not.toThrow();
  });

  it('should have required properties for each item', () => {
    navItems.forEach(item => {
      expect(item).toHaveProperty('label');
      expect(item).toHaveProperty('page');
      expect(item.label).toBeTruthy();
      expect(item.page).toBeTruthy();
    });
  });

  it('should have valid children structure', () => {
    navItems.forEach(item => {
      if (item.children) {
        item.children.forEach(child => {
          expect(child).toHaveProperty('label');
          expect(child).toHaveProperty('page');
        });
      }
    });
  });
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `npm test`
Expected: FAIL with "Cannot find module '../navigation'"

- [ ] **Step 3: Implement navigation config**

Create: `src/config/navigation.ts`
```typescript
export interface NavItem {
  label: string;
  page: string;
  hash?: string;
  children?: NavItem[];
}

export interface CtaConfig {
  label: string;
  href: string;
}

export interface NavConfig {
  items: NavItem[];
  ctas: {
    edith: CtaConfig;
    whatsapp: CtaConfig;
  };
}

export function validateNavItems(items: NavItem[]): void {
  items.forEach(item => {
    if (!item.label || !item.page) {
      throw new Error(`Invalid nav item: ${JSON.stringify(item)}`);
    }
    if (item.children) {
      validateNavItems(item.children);
    }
  });
}

export const navItems: NavItem[] = [
  {
    label: "Conoce al equipo",
    page: "/",
    children: [
      { label: "Quiénes somos", page: "/", hash: "#quienes-somos" },
      { label: "Nuestra historia", page: "/", hash: "#nuestra-historia" },
      { label: "Nuestros medicos", page: "/", hash: "#nuestros-medicos" },
      { label: "Mision, vision y valores", page: "/", hash: "#mision-vision-valores" }
    ]
  },
  {
    label: "Enfermedades que tratamos",
    page: "/especialidades",
    children: [
      { label: "Artrosis", page: "/especialidades", hash: "#artrosis-osteoartrosis" },
      { label: "Artritis Reumatoide", page: "/especialidades", hash: "#artritis-reumatoide" },
      { label: "Fibromialgia", page: "/especialidades", hash: "#fibromialgia" },
      { label: "Espondilitis Anquilosante", page: "/especialidades", hash: "#espondilitis-anquilosante" },
      { label: "Reumatismos de Partes Blandas", page: "/especialidades", hash: "#reumatismos-de-partes-blandas" }
    ]
  },
  {
    label: "Tratamiento y seguimiento",
    page: "/tratamiento-medico-integral",
    children: [
      { label: "Diagnóstico", page: "/tratamiento-medico-integral", hash: "#diagnostico" },
      { label: "Paiper", page: "/tratamiento-medico-integral", hash: "#paiper" }
    ]
  },
  {
    label: "Recursos para pacientes",
    page: "/club-vida-y-salud",
    children: []
  },
  {
    label: "Contacto y cita",
    page: "/contactanos",
    children: [
      { label: "Contacto", page: "/contactanos", hash: "#contactanos" },
      { label: "Testimonios", page: "/contactanos", hash: "#testimonios" }
    ]
  }
];

export const navConfig: NavConfig = {
  items: navItems,
  ctas: {
    edith: {
      label: "Agendar valoracion",
      href: "/contactanos#contactanos"
    },
    whatsapp: {
      label: "WhatsApp para cita",
      href: "https://wa.me/525559890607?text=Hola%2C%20quiero%20agendar%20una%20consulta%20de%20valoracion."
    }
  }
};
```

- [ ] **Step 4: Install Vitest**

Run: `npm install -D vitest @vitest/ui`
Expected: Vitest installed

- [ ] **Step 5: Configure Vitest**

Modify: `package.json`
```json
{
  "scripts": {
    "test": "vitest"
  }
}
```

Create: `vitest.config.ts`
```typescript
import { defineConfig } from 'vitest/config';

export default defineConfig({
  test: {
    globals: true
  }
});
```

- [ ] **Step 6: Run tests to verify they pass**

Run: `npm test`
Expected: PASS

- [ ] **Step 7: Commit navigation config**

```bash
git add .
git commit -m "feat: add type-safe navigation configuration with tests"
```

---

### Task 3: Create Base Layout

**Files:**
- Create: `src/layouts/Layout.astro`

- [ ] **Step 1: Create Layout component**

Create: `src/layouts/Layout.astro`
```astro
---
import Header from '@/components/Header.astro';
import Footer from '@/components/Footer.astro';
import { navConfig } from '@/config/navigation';

interface Props {
  title: string;
  description?: string;
  currentPage?: string;
}

const {
  title = 'Artricenter',
  description = 'Clinica enfocada en diagnostico y tratamiento integral de enfermedades reumaticas.',
  currentPage = '/'
} = Astro.props;

const canonicalURL = new URL(Astro.url.pathname, Astro.site);
---

<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href={canonicalURL} />
    <meta name="description" content={description} />
    <meta name="robots" content="index, follow" />

    <!-- Open Graph -->
    <meta property="og:locale" content="es_MX" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Artricenter" />
    <meta property="og:title" content={title} />
    <meta property="og:description" content={description} />
    <meta property="og:url" content={canonicalURL} />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content={title} />
    <meta name="twitter:description" content={description} />

    <!-- Structured Data -->
    <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "MedicalClinic",
        "name": "Artricenter",
        "url": "https://artricenter.com/",
        "medicalSpecialty": "Rheumatology",
        "description": "Clinica enfocada en diagnostico y tratamiento integral de enfermedades reumaticas."
      }
    </script>

    <title>{title}</title>
    <link rel="stylesheet" href="/src/styles/global.css" />
  </head>
  <body class="bg-white text-gray-900 antialiased">
    <Header navItems={navConfig.items} currentPage={currentPage} />

    <main class="min-h-screen">
      <slot />
    </main>

    <Footer />

    <script>
      // Minimal client-side JS for smooth scroll
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
          }
        });
      });
    </script>
  </body>
</html>
```

- [ ] **Step 2: Commit Layout**

```bash
git add src/layouts/Layout.astro
git commit -m "feat: add base Layout with SEO and structured data"
```

---

### Task 4: Create Navigation Component

**Files:**
- Create: `src/components/Navigation.astro`

- [ ] **Step 1: Create Navigation component**

Create: `src/components/Navigation.astro`
```astro
---
import { NavItem } from '@/config/navigation';

interface Props {
  items: NavItem[];
  currentPage: string;
}

const { items, currentPage } = Astro.props;

function isActive(page: string, currentPage: string): boolean {
  return page === currentPage;
}
---

<!-- Desktop Navigation -->
<nav class="hidden lg:block" aria-label="Navegacion principal">
  <ul class="flex items-center space-x-8">
    {items.map((item, index) => (
      <li class="relative group">
        <a
          href={item.page + (item.hash || '')}
          class:list={[
            "inline-flex items-center px-3 py-2 text-sm font-medium transition-colors",
            { "text-blue-600": isActive(item.page, currentPage) },
            { "text-gray-700 hover:text-blue-600": !isActive(item.page, currentPage) }
          ]}
          aria-current={isActive(item.page, currentPage) ? 'page' : undefined}
        >
          {item.label}
        </a>

        {item.children && item.children.length > 0 && (
          <div class="absolute left-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <div class="py-1">
              {item.children.map(child => (
                <a
                  href={child.page + (child.hash || '')}
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600"
                >
                  {child.label}
                </a>
              ))}
            </div>
          </div>
        )}
      </li>
    ))}
  </ul>
</nav>

<!-- Mobile Navigation Toggle -->
<script>
  // Mobile menu toggle logic
  const mobileToggle = document.querySelector('[data-mobile-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');

  mobileToggle?.addEventListener('click', () => {
    const isClosed = mobileMenu?.classList.contains('hidden');
    if (isClosed) {
      mobileMenu?.classList.remove('hidden');
      mobileToggle?.setAttribute('aria-expanded', 'true');
    } else {
      mobileMenu?.classList.add('hidden');
      mobileToggle?.setAttribute('aria-expanded', 'false');
    }
  });

  // Close mobile menu on link click
  mobileMenu?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu?.classList.add('hidden');
      mobileToggle?.setAttribute('aria-expanded', 'false');
    });
  });
</script>
```

- [ ] **Step 2: Commit Navigation**

```bash
git add src/components/Navigation.astro
git commit -m "feat: add Navigation component with desktop dropdowns"
```

---

### Task 5: Create Header Component

**Files:**
- Create: `src/components/Header.astro`

- [ ] **Step 1: Create Header component**

Create: `src/components/Header.astro`
```astro
---
import Navigation from './Navigation.astro';
import { NavItem } from '@/config/navigation';

interface Props {
  navItems: NavItem[];
  currentPage: string;
}

const { navItems, currentPage } = Astro.props;
---

<header class="bg-white border-b border-gray-200 sticky top-0 z-40">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo -->
      <a
        href="/"
        class="flex items-center space-x-2"
        aria-label="Ir a la página principal de Artricenter"
      >
        <img
          src="/assets/logo.png"
          alt="Artricenter"
          class="h-10 w-auto"
        />
      </a>

      <!-- Desktop Navigation -->
      <Navigation items={navItems} currentPage={currentPage} />

      <!-- Mobile Menu Toggle -->
      <button
        type="button"
        class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100"
        aria-expanded="false"
        data-mobile-toggle
        aria-label="Abrir menú"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile Navigation Menu -->
  <div
    class="lg:hidden hidden border-t border-gray-200 bg-white"
    data-mobile-menu
    aria-hidden="true"
  >
    <nav class="px-4 py-4 space-y-1" aria-label="Navegación móvil">
      {navItems.map(item => (
        <div>
          <a
            href={item.page}
            class:list={[
              "block px-3 py-2 text-base font-medium rounded-md",
              { "bg-blue-50 text-blue-600": item.page === currentPage },
              { "text-gray-700 hover:bg-gray-100": item.page !== currentPage }
            ]}
          >
            {item.label}
          </a>

          {item.children && item.children.length > 0 && (
            <div class="mt-1 ml-4 space-y-1">
              {item.children.map(child => (
                <a
                  href={child.page + (child.hash || '')}
                  class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md"
                >
                  {child.label}
                </a>
              ))}
            </div>
          )}
        </div>
      ))}
    </nav>
  </div>
</header>
```

- [ ] **Step 2: Commit Header**

```bash
git add src/components/Header.astro
git commit -m "feat: add Header component with mobile menu"
```

---

### Task 6: Create Footer Component

**Files:**
- Create: `src/components/Footer.astro`

- [ ] **Step 1: Create Footer component**

Create: `src/components/Footer.astro`
```astro
---
const currentYear = new Date().getFullYear();
---

<footer class="bg-gray-50 border-t border-gray-200 mt-auto">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- About -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Artricenter</h3>
        <p class="text-gray-600 text-sm">
          Clinica especializada en diagnostico y tratamiento integral de enfermedades reumaticas.
        </p>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Enlaces rápidos</h3>
        <ul class="space-y-2">
          <li>
            <a href="/" class="text-gray-600 hover:text-blue-600 text-sm">Quienes Somos</a>
          </li>
          <li>
            <a href="/especialidades" class="text-gray-600 hover:text-blue-600 text-sm">Especialidades</a>
          </li>
          <li>
            <a href="/tratamiento-medico-integral" class="text-gray-600 hover:text-blue-600 text-sm">Tratamiento</a>
          </li>
          <li>
            <a href="/contactanos" class="text-gray-600 hover:text-blue-600 text-sm">Contacto</a>
          </li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contacto</h3>
        <ul class="space-y-2 text-sm text-gray-600">
          <li>Agenda tu valoración hoy</li>
          <li>
            <a href="/contactanos" class="text-blue-600 hover:text-blue-700">
              Formulario de contacto
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="mt-8 pt-8 border-t border-gray-200 text-center text-sm text-gray-500">
      <p>&copy; {currentYear} Artricenter. Todos los derechos reservados.</p>
    </div>
  </div>
</footer>
```

- [ ] **Step 2: Commit Footer**

```bash
git add src/components/Footer.astro
git commit -m "feat: add Footer component"
```

---

### Task 7: Create PageSection Component

**Files:**
- Create: `src/components/PageSection.astro`

- [ ] **Step 1: Create PageSection component**

Create: `src/components/PageSection.astro`
```astro
---
interface Props {
  id?: string;
  title: string;
  content: string;
  variant?: 'default' | 'highlight' | 'compact';
}

const {
  id,
  title,
  content,
  variant = 'default'
} = Astro.props;

const variantClasses = {
  default: 'py-12 px-4',
  highlight: 'py-16 px-4 bg-blue-50',
  compact: 'py-8 px-4'
};
---

<section
  id={id}
  class:list={[
    "max-w-7xl mx-auto",
    variantClasses[variant]
  ]}
>
  <div class="prose prose-lg max-w-none">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">{title}</h2>
    <p class="text-gray-700 leading-relaxed">{content}</p>
  </div>
</section>
```

- [ ] **Step 2: Commit PageSection**

```bash
git add src/components/PageSection.astro
git commit -m "feat: add PageSection component"
```

---

### Task 8: Create ContentSection Component

**Files:**
- Create: `src/components/ContentSection.astro`

- [ ] **Step 1: Create ContentSection component**

Create: `src/components/ContentSection.astro`
```astro
---
interface Props {
  id: string;
  title: string;
  children?: any;
}

const { id, title } = Astro.props;
---

<section id={id} class="max-w-7xl mx-auto px-4 py-12">
  <h2 class="text-3xl font-bold text-gray-900 mb-6">{title}</h2>
  <div class="prose prose-lg max-w-none">
    <slot />
  </div>
</section>
```

- [ ] **Step 2: Commit ContentSection**

```bash
git add src/components/ContentSection.astro
git commit -m "feat: add ContentSection component"
```

---

### Task 9: Migrate Index Page (Quienes Somos)

**Files:**
- Modify: `src/pages/index.astro`

- [ ] **Step 1: Create index page**

Create: `src/pages/index.astro`
```astro
---
import Layout from '@/layouts/Layout.astro';
import ContentSection from '@/components/ContentSection.astro';
import { navConfig } from '@/config/navigation';

const quienesSomos = "Artricenter es un equipo enfocado en reumatologia integral, con atencion cercana para personas que necesitan recuperar movilidad, bienestar y confianza en su tratamiento.";

const artricenter = "Nuestro centro reune evaluacion medica, seguimiento continuo y educacion para pacientes con dolor articular cronico o enfermedades reumaticas.";

const historia = "Iniciamos con la mision de ofrecer una alternativa especializada y humana. Hoy acompanamos a cientos de familias en procesos de diagnostico temprano y control de sintomas.";

const medicos = "Contamos con especialistas certificados y personal clinico capacitado para crear planes claros, personalizados y medibles para cada etapa del tratamiento.";

const misionVisionValores = "Mision: mejorar calidad de vida. Vision: ser referencia nacional en manejo integral de enfermedades reumaticas. Valores: empatia, ciencia, transparencia y acompanamiento constante.";
---

<Layout
  title="Quienes Somos | Artricenter"
  description="Conoce al equipo de Artricenter, su historia y el enfoque de reumatologia integral para mejorar movilidad y calidad de vida."
  currentPage="/"
>
  <div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Quienes Somos</h1>

    <ContentSection id="quienes-somos" title={quienesSomos}>
      <p class="text-gray-700">{artricenter}</p>
    </ContentSection>

    <ContentSection id="nuestra-historia" title="Nuestra Historia">
      <p class="text-gray-700">{historia}</p>
    </ContentSection>

    <ContentSection id="nuestros-medicos" title="Nuestros Medicos">
      <p class="text-gray-700">{medicos}</p>
    </ContentSection>

    <ContentSection id="mision-vision-valores" title="Mision, Vision y Valores">
      <p class="text-gray-700">{misionVisionValores}</p>
    </ContentSection>

    <section id="enlaces-relacionados" class="py-12">
      <h2 class="text-3xl font-bold text-gray-900 mb-6">Enlaces relacionados</h2>
      <ul class="space-y-2">
        <li>
          <a href="/especialidades" class="text-blue-600 hover:text-blue-700">
            Enfermedades que tratamos
          </a>
        </li>
        <li>
          <a href="/tratamiento-medico-integral" class="text-blue-600 hover:text-blue-700">
            Tratamiento y seguimiento
          </a>
        </li>
        <li>
          <a href="/contactanos" class="text-blue-600 hover:text-blue-700">
            Agenda una valoracion
          </a>
        </li>
      </ul>
    </section>
  </div>
</Layout>
```

- [ ] **Step 2: Commit index page**

```bash
git add src/pages/index.astro
git commit -m "feat: migrate index page (Quienes Somos)"
```

---

### Task 10: Migrate Remaining Pages

**Files:**
- Create: `src/pages/especialidades.astro`
- Create: `src/pages/tratamiento-medico-integral.astro`
- Create: `src/pages/club-vida-y-salud.astro`
- Create: `src/pages/contactanos.astro`

- [ ] **Step 1: Read existing HTML files**

Run: `ls *.html`
Expected: List of HTML files to migrate

- [ ] **Step 2: Migrate especialidades page**

Create: `src/pages/especialidades.astro`
```astro
---
import Layout from '@/layouts/Layout.astro';
// Read content from especialidades.html and structure accordingly
---
<Layout
  title="Enfermedades que Tratamos | Artricenter"
  description="Informacion sobre enfermedades reumaticas: Artrosis, Artritis Reumatoide, Fibromialgia, Espondilitis Anquilosante y mas."
  currentPage="/especialidades"
>
  <!-- Migrate content from especialidades.html -->
</Layout>
```

- [ ] **Step 3: Migrate tratamiento page**

Create: `src/pages/tratamiento-medico-integral.astro`
```astro
---
import Layout from '@/layouts/Layout.astro';
---
<Layout
  title="Tratamiento y Seguimiento | Artricenter"
  description="Tratamiento integral para enfermedades reumaticas con diagnostico y seguimiento personalizado."
  currentPage="/tratamiento-medico-integral"
>
  <!-- Migrate content from tratamiento-medico-integral.html -->
</Layout>
```

- [ ] **Step 4: Migrate club-vida-y-salud page**

Create: `src/pages/club-vida-y-salud.astro`
```astro
---
import Layout from '@/layouts/Layout.astro';
---
<Layout
  title="Recursos para Pacientes | Artricenter"
  description="Recursos y educacion para pacientes con enfermedades reumaticas."
  currentPage="/club-vida-y-salud"
>
  <!-- Migrate content from club-vida-y-salud.html -->
</Layout>
```

- [ ] **Step 5: Migrate contactanos page**

Create: `src/pages/contactanos.astro`
```astro
---
import Layout from '@/layouts/Layout.astro';
---
<Layout
  title="Contacto y Cita | Artricenter"
  description="Agenda tu valoracion en Artricenter. Contacto y citas."
  currentPage="/contactanos"
>
  <!-- Migrate content from contactanos.html -->
</Layout>
```

- [ ] **Step 6: Commit remaining pages**

```bash
git add src/pages/
git commit -m "feat: migrate remaining pages to Astro"
```

---

### Task 11: Copy Assets and Configure Build

**Files:**
- Copy: `assets/` to `public/`
- Modify: `astro.config.mjs`

- [ ] **Step 1: Copy assets to public directory**

Run: `cp -r assets public/ 2>/dev/null || mkdir -p public/assets && cp assets/* public/assets/`
Expected: Assets copied to public directory

- [ ] **Step 2: Configure Astro site URL**

Modify: `astro.config.mjs`
```javascript
import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';

export default defineConfig({
  integrations: [tailwind()],
  site: 'https://artricenter.com',
  base: '/',
});
```

- [ ] **Step 3: Test build locally**

Run: `npm run build`
Expected: Successful build in `dist/`

- [ ] **Step 4: Test dev server**

Run: `npm run dev`
Expected: Development server running

- [ ] **Step 5: Commit build configuration**

```bash
git add public/ astro.config.mjs
git commit -m "feat: configure build and copy assets"
```

---

### Task 12: Final Testing and Polish

**Files:**
- Test: All pages and components

- [ ] **Step 1: Run test suite**

Run: `npm test`
Expected: All tests pass

- [ ] **Step 2: Test navigation functionality**

Manual test: Navigate through all menu items
Expected: All links work, dropdowns function

- [ ] **Step 3: Test mobile responsiveness**

Manual test: Resize browser, test mobile menu
Expected: Mobile menu opens/closes correctly

- [ ] **Step 4: Check SEO metadata**

Run: `npm run build && npm run preview`
Check: Page source for meta tags, structured data
Expected: All SEO elements present

- [ ] **Step 5: Accessibility audit**

Run: `npx pa11y dist/index.html`
Expected: No critical accessibility issues

- [ ] **Step 6: Final commit**

```bash
git add .
git commit -m "chore: final polish and testing complete"
```

---

## Testing Strategy

- **Unit tests:** Navigation config validation
- **Integration tests:** Component rendering
- **E2E tests:** Navigation flows
- **Accessibility:** pa11y automated testing
- **Manual testing:** Visual regression, mobile responsiveness

## Success Criteria

- [ ] Navigation renders correctly without JavaScript
- [ ] All components reusable across pages
- [ ] Tailwind styles applied consistently
- [ ] Type-safe configuration
- [ ] No malformed DOM elements
- [ ] Mobile responsive
- [ ] Accessibility standards met
- [ ] SEO maintained or improved

## Notes

- Preserve all existing content and SEO metadata
- Test thoroughly before removing old HTML files
- Keep old files as reference during migration
- Deploy to staging before production
