# Codebase Structure

**Analysis Date:** 2026-03-19

## Directory Layout

```
artricenter/
├── src/
│   ├── components/          # Reusable UI components
│   ├── config/             # Configuration files
│   ├── layouts/            # Page layout templates
│   ├── pages/              # Page components
│   └── styles/             # Global styles
├── public/                 # Static assets
├── tests/                  # Integration tests
├── .planning/              # Planning documents
├── assets/                 # Image and media assets
├── static/                 # Additional static files
└── dist/                   # Built output
```

## Directory Purposes

**`src/components/`:**
- Purpose: Reusable UI components for consistent design
- Contains: Header, Footer, Navigation, ContentSection, PageSection components
- Key files: `Header.astro`, `Footer.astro`, `Navigation.astro`, `ContentSection.astro`

**`src/config/`:**
- Purpose: Centralized configuration and data management
- Contains: Navigation configuration, validation logic, type definitions
- Key files: `navigation.ts`, `__tests__/navigation.test.ts`

**`src/layouts/`:**
- Purpose: Page layout templates and shared structure
- Contains: Main layout component with HTML structure and SEO
- Key files: `Layout.astro`

**`src/pages/`:**
- Purpose: Individual page components for routing
- Contains: Clinic information pages, services pages
- Key files: `index.astro`, `especialidades.astro`, `tratamiento-medico-integral.astro`, `club-vida-y-salud.astro`, `contactanos.astro`

**`src/styles/`:**
- Purpose: Global styles and Tailwind configuration
- Contains: Global CSS imports and overrides
- Key files: `global.css`

**`public/`:**
- Purpose: Static assets served at root path
- Contains: Images, fonts, and other media files
- Key files: Assets directory with clinic images and content

**`tests/`:**
- Purpose: Integration and SEO testing
- Contains: Test suites for navigation structure and SEO validation
- Key files: `seo-structure.test.js`

**`assets/`:**
- Purpose: Clinic-specific media and images
- Contains: Doctor photos, medical illustrations, clinic branding
- Key files: Clinic staff photos and medical imagery

**`static/`:**
- Purpose: Additional static files
- Contains: CSS and JS files for additional functionality
- Key files: CSS and JavaScript directories

## Key File Locations

**Entry Points:**
- `/src/pages/index.astro`: Main landing page
- `/src/layouts/Layout.astro`: Global layout template
- `/src/components/Header.astro`: Navigation header
- `/src/components/Footer.astro`: Page footer with contact info

**Configuration:**
- `/src/config/navigation.ts`: Navigation structure and data
- `/tailwind.config.mjs`: Tailwind CSS configuration
- `/astro.config.mjs`: Astro framework configuration

**Core Logic:**
- `/src/components/ContentSection.astro`: Reusable content sections
- `/src/components/PageSection.astro`: Page-specific sections

**Testing:**
- `/src/config/__tests__/navigation.test.ts`: Navigation validation tests
- `/tests/seo-structure.test.js`: SEO structure integration tests

## Naming Conventions

**Files:**
- Page components: `page-name.astro` (e.g., `index.astro`, `especialidades.astro`)
- Component files: `ComponentName.astro` (e.g., `Header.astro`, `ContentSection.astro`)
- Configuration files: `feature-name.ts` (e.g., `navigation.ts`)
- Test files: `feature-name.test.ts` or `feature-name.test.js`

**Directories:**
- Components: `/src/components/`
- Pages: `/src/pages/`
- Configuration: `/src/config/`
- Layouts: `/src/layouts/`
- Styles: `/src/styles/`

## Where to Add New Code

**New Page:**
- Primary code: `/src/pages/page-name.astro`
- Tests: `/tests/seo-structure.test.js` (add to page array)
- Navigation: `/src/config/navigation.ts` (add to navItems)

**New Component:**
- Implementation: `/src/components/ComponentName.astro`
- Usage: Import in pages or layouts
- Styling: Use Tailwind CSS classes

**Configuration:**
- Navigation: `/src/config/navigation.ts`
- Tailwind: `/tailwind.config.mjs`
- Astro config: `/astro.config.mjs`

**Testing:**
- Unit tests: `/src/config/__tests__/`
- Integration tests: `/tests/`

## Special Directories

**`.planning/`:**
- Purpose: Architecture and planning documentation
- Contains: Generated architecture and structure analysis
- Generated: Yes, committed to git for team visibility

**`dist/`:**
- Purpose: Build output directory
- Generated: Yes, created during build process
- Committed: No, ignored in .gitignore

**`node_modules/`:**
- Purpose: Dependencies and packages
- Generated: Yes, during npm install
- Committed: No, ignored in .gitignore

---

*Structure analysis: 2026-03-19*
*