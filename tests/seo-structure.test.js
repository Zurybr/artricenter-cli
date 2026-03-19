const fs = require('fs');
const path = require('path');

const pages = [
  'quienes-somos.html',
  'especialidades.html',
  'tratamiento-medico-integral.html',
  'club-vida-y-salud.html',
  'contactanos.html'
];

function assertPattern(content, pattern, message) {
  if (!pattern.test(content)) {
    throw new Error(message + ` (pattern: ${pattern})`);
  }
}

for (const page of pages) {
  const html = fs.readFileSync(path.join(process.cwd(), page), 'utf8');

  assertPattern(html, /<meta[^>]*name="description"/i, `${page}: missing description meta`);
  assertPattern(html, /<link[^>]*rel="canonical"/i, `${page}: missing canonical`);
  assertPattern(html, /<meta[^>]*property="og:title"/i, `${page}: missing og:title`);
  assertPattern(html, /<meta[^>]*name="twitter:card"/i, `${page}: missing twitter:card`);
  assertPattern(html, /aria-labelledby="/i, `${page}: sections missing aria-labelledby`);
  assertPattern(html, /Enlaces relacionados/i, `${page}: missing related links section`);
}

console.log('SEO structure checks passed for all core pages.');
