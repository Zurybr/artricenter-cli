import { readFileSync } from 'fs';
import { join } from 'path';
import { describe, it, expect } from 'vitest';

describe('SEO structure checks', () => {
  const pages = [
    'index.html',
    'especialidades.html',
    'tratamiento-medico-integral.html',
    'club-vida-y-salud.html',
    'contactanos.html'
  ];

  pages.forEach(page => {
    describe(`${page}`, () => {
      const html = readFileSync(join(process.cwd(), page), 'utf8');

      it('should have description meta tag', () => {
        expect(html).toMatch(/<meta[^>]*name="description"/i);
      });

      it('should have canonical link', () => {
        expect(html).toMatch(/<link[^>]*rel="canonical"/i);
      });

      it('should have og:title meta tag', () => {
        expect(html).toMatch(/<meta[^>]*property="og:title"/i);
      });

      it('should have twitter:card meta tag', () => {
        expect(html).toMatch(/<meta[^>]*name="twitter:card"/i);
      });

      it('should have aria-labelledby attributes on sections', () => {
        expect(html).toMatch(/aria-labelledby="/i);
      });

      it('should have related links section', () => {
        expect(html).toMatch(/Enlaces relacionados/i);
      });
    });
  });
});
