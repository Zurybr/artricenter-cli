# Technology Stack

**Analysis Date:** 2026-03-19

## Languages

**Primary:**
- TypeScript [5.x] - Primary language for configuration and components

**Secondary:**
- JavaScript [ES6+] - Used in Astro components and scripts
- HTML [5] - Base markup language

## Runtime

**Environment:**
- Node.js [>=22.12.0] - Runtime environment
- Astro [6.0.7] - Web framework

**Package Manager:**
- npm [latest] - Package manager
- Lockfile: package-lock.json present

## Frameworks

**Core:**
- Astro [6.0.7] - Modern static site framework
- TypeScript [strict] - Type safety with strict mode

**Styling:**
- Tailwind CSS [4.2.2] - Utility-first CSS framework
- @tailwindcss/vite [4.2.2] - Tailwind Vite plugin

**Build/Dev:**
- Vite [latest] - Build tool and dev server
- Vitest [4.1.0] - Testing framework
- @vitest/ui [4.1.0] - Test UI

## Key Dependencies

**Critical:**
- astro [6.0.7] - Core framework
- tailwindcss [4.2.2] - CSS framework
- @tailwindcss/vite [4.2.2] - Tailwind integration

**Development:**
- @types/node [25.5.0] - Node.js types
- vitest [4.1.0] - Testing framework
- @vitest/ui [4.1.0] - Test UI

## Configuration

**Environment:**
- Node.js version requirement: >=22.12.0
- Environment variables: Not detected (static site)

**Build:**
- Astro config: `/astro.config.mjs`
- TypeScript config: `/tsconfig.json`
- Tailwind config: `/tailwind.config.mjs`
- Vitest config: `/vitest.config.ts`

## Platform Requirements

**Development:**
- Node.js >=22.12.0
- npm package manager

**Production:**
- Static hosting (Vercel, Netlify, etc.)
- No server-side runtime required

**Deployment:**
- Static site generation
- Zero JavaScript runtime in production (if configured properly)

---

*Stack analysis: 2026-03-19*
```