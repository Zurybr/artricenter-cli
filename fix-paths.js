import fs from 'fs';
import path from 'path';

const distPath = './dist';

function fixPaths(dir) {
  const files = fs.readdirSync(dir);

  files.forEach(file => {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);

    if (stat.isDirectory()) {
      fixPaths(filePath);
    } else if (file.endsWith('.html')) {
      let content = fs.readFileSync(filePath, 'utf-8');

      // Fix asset paths (only if not already fixed)
      content = content.replace(/src="\/assets\//g, 'src="/artricenter-cli/assets/');
      content = content.replace(/href="\/assets\//g, 'href="/artricenter-cli/assets/');

      // Fix home link ("Quienes Somos") and anchor links to home
      content = content.replace(/href="\/"/g, 'href="/artricenter-cli/"');
      content = content.replace(/href="\/#/g, 'href="/artricenter-cli/#');

      // Fix internal navigation links - specific pages
      const pages = ['especialidades', 'club-vida-y-salud', 'tratamiento-medico-integral', 'contactanos'];
      pages.forEach(page => {
        content = content.replace(new RegExp(`href="/${page}(?![a-z-])`, 'g'), `href="/artricenter-cli/${page}`);
        content = content.replace(new RegExp(`href="/${page}#`, 'g'), `href="/artricenter-cli/${page}#`);
      });

      fs.writeFileSync(filePath, content);
      console.log(`Fixed paths in ${filePath}`);
    }
  });
}

console.log('Fixing asset and navigation paths for GitHub Pages...');
fixPaths(distPath);
console.log('Done!');
