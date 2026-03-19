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

      // Fix all asset paths
      content = content.replace(/src="\/assets\//g, 'src="/artricenter-cli/assets/');
      content = content.replace(/href="\/assets\//g, 'href="/artricenter-cli/assets/');

      fs.writeFileSync(filePath, content);
      console.log(`Fixed paths in ${filePath}`);
    }
  });
}

console.log('Fixing asset paths for GitHub Pages...');
fixPaths(distPath);
console.log('Done!');
