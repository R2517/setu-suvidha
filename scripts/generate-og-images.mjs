import fs from 'node:fs/promises';
import path from 'node:path';
import sharp from 'sharp';

const ROOT = process.cwd();
const SOURCE_JSON = path.join(ROOT, 'resources', 'seo', 'og-pages.json');
const OUTPUT_DIR = path.join(ROOT, 'public', 'images', 'og');
const WIDTH = 1200;
const HEIGHT = 630;

const themes = {
  sunrise: {
    bg: 'linear-gradient(135deg, #f59e0b 0%, #ea580c 52%, #7c2d12 100%)',
    accent: '#fde68a',
    glow: '#fb923c',
  },
  ocean: {
    bg: 'linear-gradient(135deg, #0ea5e9 0%, #2563eb 50%, #1e3a8a 100%)',
    accent: '#bae6fd',
    glow: '#60a5fa',
  },
  forest: {
    bg: 'linear-gradient(135deg, #16a34a 0%, #15803d 50%, #14532d 100%)',
    accent: '#bbf7d0',
    glow: '#4ade80',
  },
  violet: {
    bg: 'linear-gradient(135deg, #a855f7 0%, #7c3aed 50%, #4c1d95 100%)',
    accent: '#e9d5ff',
    glow: '#c084fc',
  },
};

function escapeXml(text) {
  return String(text)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');
}

function wrapText(text, maxChars) {
  const words = String(text).split(/\s+/).filter(Boolean);
  const lines = [];
  let line = '';

  for (const word of words) {
    const next = line ? `${line} ${word}` : word;
    if (next.length <= maxChars) {
      line = next;
      continue;
    }
    if (line) {
      lines.push(line);
    }
    line = word;
  }

  if (line) {
    lines.push(line);
  }

  return lines;
}

function buildSvg({ title, subtitle, themeName }) {
  const theme = themes[themeName] || themes.sunrise;
  const titleLines = wrapText(title, 28).slice(0, 3);
  const subtitleLines = wrapText(subtitle, 52).slice(0, 3);

  const titleSvg = titleLines
    .map((line, idx) => {
      const y = 255 + idx * 66;
      return `<text x="90" y="${y}" font-family="Inter,Arial,sans-serif" font-size="56" font-weight="800" fill="#ffffff">${escapeXml(line)}</text>`;
    })
    .join('\n');

  const subtitleSvg = subtitleLines
    .map((line, idx) => {
      const y = 475 + idx * 36;
      return `<text x="92" y="${y}" font-family="Inter,Arial,sans-serif" font-size="30" font-weight="500" fill="${theme.accent}">${escapeXml(line)}</text>`;
    })
    .join('\n');

  return `
<svg width="${WIDTH}" height="${HEIGHT}" viewBox="0 0 ${WIDTH} ${HEIGHT}" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <filter id="blurGlow" x="-30%" y="-30%" width="160%" height="160%">
      <feGaussianBlur stdDeviation="42" />
    </filter>
  </defs>
  <rect width="${WIDTH}" height="${HEIGHT}" fill="#0f172a"/>
  <rect width="${WIDTH}" height="${HEIGHT}" fill="${theme.bg}"/>
  <circle cx="1040" cy="102" r="220" fill="${theme.glow}" opacity="0.45" filter="url(#blurGlow)" />
  <circle cx="120" cy="620" r="240" fill="${theme.glow}" opacity="0.25" filter="url(#blurGlow)" />
  <rect x="70" y="60" width="1060" height="510" rx="24" fill="rgba(15,23,42,0.26)" stroke="rgba(255,255,255,0.28)" stroke-width="2" />
  <text x="92" y="132" font-family="Inter,Arial,sans-serif" font-size="28" font-weight="700" fill="#ffffff">SETU Suvidha</text>
  <text x="92" y="170" font-family="Inter,Arial,sans-serif" font-size="20" font-weight="500" fill="${theme.accent}">setusuvidha.com</text>
  ${titleSvg}
  ${subtitleSvg}
</svg>`;
}

async function main() {
  const source = await fs.readFile(SOURCE_JSON, 'utf8');
  const pages = JSON.parse(source);
  await fs.mkdir(OUTPUT_DIR, { recursive: true });

  for (const page of pages) {
    const svg = buildSvg({
      title: page.title || 'SETU Suvidha',
      subtitle: page.subtitle || 'Government Service Platform',
      themeName: page.theme || 'sunrise',
    });

    const outputPath = path.join(OUTPUT_DIR, `${page.slug}.png`);
    await sharp(Buffer.from(svg))
      .png({ compressionLevel: 9, quality: 90 })
      .toFile(outputPath);
  }

  console.log(`Generated ${pages.length} OG images in public/images/og`);
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});

