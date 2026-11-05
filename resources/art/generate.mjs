/**
 * Lion Painters — original SVG artwork generator.
 *
 * Every visual on the site is drawn from a small, consistent set of
 * architectural compositions so the brand stays cohesive without relying on
 * third-party stock photography.
 *
 * Run: node resources/art/generate.mjs
 */
import { mkdirSync, writeFileSync } from 'node:fs';
import { resolve } from 'node:path';

const OUT = resolve('public/images');
mkdirSync(OUT, { recursive: true });

const grain = `
  <filter id="grain" x="-20%" y="-20%" width="140%" height="140%">
    <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="3" stitchTiles="stitch" result="n"/>
    <feColorMatrix in="n" type="saturate" values="0" result="g"/>
    <feComponentTransfer in="g" result="t">
      <feFuncA type="linear" slope="0.14" intercept="0"/>
    </feComponentTransfer>
    <feComposite in="t" in2="SourceGraphic" operator="in"/>
  </filter>`;

const gold = `
  <linearGradient id="gold" x1="0" y1="0" x2="1" y2="1">
    <stop offset="0" stop-color="#EBD49B"/>
    <stop offset="0.5" stop-color="#C6A45F"/>
    <stop offset="1" stop-color="#967536"/>
  </linearGradient>`;

const lionArt = `
  <g fill="none" stroke="url(#gold)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
    <path d="M0 -124 C20 -122 28 -108 42 -102 C60 -100 64 -82 78 -72 C80 -54 96 -44 92 -26
             C106 -10 96 8 88 20 C92 40 76 50 64 58 C60 78 42 84 26 86
             C14 100 -6 94 -18 90 C-36 100 -50 86 -60 74 C-78 74 -86 58 -90 44
             C-106 32 -100 12 -100 -4 C-112 -18 -102 -38 -92 -48
             C-92 -66 -76 -76 -64 -82 C-60 -100 -42 -102 -32 -106 C-22 -124 -10 -126 0 -124Z"/>
    <path d="M0 -84 C30 -84 46 -60 46 -30 C46 4 24 32 0 46 C-24 32 -46 4 -46 -30 C-46 -60 -30 -84 0 -84Z" fill="#1B2B33"/>
    <path d="M-30 -90 L-24 -70 M30 -90 L24 -70 M-4 -108 L0 -94 M14 -104 L8 -92 M-18 -102 L-12 -90"/>
    <path d="M-30 -26 C-20 -36 -8 -34 -2 -25 M30 -26 C20 -36 8 -34 2 -25"/>
    <path d="M0 -24 L-8 -10 L8 -10 Z M0 -10 L0 0 M0 0 C-8 8 -18 4 -22 -2 M0 0 C8 8 18 4 22 -2" stroke="#C6A45F"/>
    <path d="M-42 14 L-24 10 M-42 26 L-24 24 M42 14 L24 10 M42 26 L24 24" stroke-width="2.5"/>
  </g>`;

/** Framed artwork, with the motif centred and scaled to fit. */
function frame(x, y, w, h, art, mat = '#F3ECDD') {
  const innerW = w - 36;
  const innerH = h - 36;
  const scale = Math.min(innerW / 260, innerH / 250) * 1.55;
  return `
    <g>
      <rect x="${x + 9}" y="${y + 14}" width="${w}" height="${h}" fill="#000" opacity=".2"/>
      <rect x="${x}" y="${y}" width="${w}" height="${h}" fill="url(#gold)"/>
      <rect x="${x + 8}" y="${y + 8}" width="${w - 16}" height="${h - 16}" fill="${mat}"/>
      <rect x="${x + 17}" y="${y + 17}" width="${w - 34}" height="${h - 34}" fill="#1B2B33"/>
      <g transform="translate(${x + w / 2} ${y + h / 2}) scale(${scale})">${art}</g>
    </g>`;
}

function lightShaft(x, y, w, h, opacity = 0.16) {
  return `<path d="M${x} ${y} L${x + w} ${y} L${x + w + 160} ${y + h} L${x - 110} ${y + h} Z" fill="#FFF3D6" opacity="${opacity}" filter="url(#soft)"/>`;
}

/** Interior room scene shared by the hero, projects and before/after. */
function room({
  wallTop, wallBottom, floor, sofa, accent, art = 'abstract', window = 'arch',
  width = 1200, height = 900, haze = 0.24, vignette = 0.3,
}) {
  const W = width;
  const H = height;
  const horizon = H * 0.765;
  const winX = W * 0.52;
  const winW = W * 0.32;
  const winY = H * 0.115;
  const winH = horizon - winY - H * 0.025;
  const winCX = winX + winW / 2;
  const arch = `M${winX} ${winY + winH} L${winX} ${winY + winW / 2} A${winW / 2} ${winW / 2} 0 0 1 ${winX + winW} ${winY + winW / 2} L${winX + winW} ${winY + winH} Z`;
  const artX = W * 0.335;
  const artY = H * 0.175;
  const artW = W * 0.21;
  const artH = H * 0.265;
  const mat = '#F3ECDD';

  const artMarkup = art === 'lion'
    ? lionArt
    : `<g>
        <circle cx="0" cy="-26" r="88" fill="${accent}" opacity=".85"/>
        <circle cx="34" cy="-70" r="30" fill="${mat}" opacity=".65"/>
        <path d="M-104 84 Q -20 -40 104 84" fill="none" stroke="${mat}" stroke-width="12" opacity=".75"/>
        <path d="M-84 104 Q 0 4 84 104" fill="none" stroke="${accent}" stroke-width="4" opacity=".65"/>
      </g>`;

  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" role="img">
  <defs>
    <linearGradient id="wall" x1="0" y1="0" x2=".45" y2="1">
      <stop offset="0" stop-color="${wallTop}"/>
      <stop offset="1" stop-color="${wallBottom}"/>
    </linearGradient>
    <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#EFE0BE"/>
      <stop offset=".5" stop-color="#DCC79E"/>
      <stop offset="1" stop-color="#8FA08A"/>
    </linearGradient>
    <linearGradient id="sofaGrad" x1="0" y1="0" x2=".2" y2="1">
      <stop offset="0" stop-color="${sofa}"/>
      <stop offset="1" stop-color="${sofa}" stop-opacity=".78"/>
    </linearGradient>
    <linearGradient id="floorGrad" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="${floor}"/>
      <stop offset="1" stop-color="#15120F"/>
    </linearGradient>
    <radialGradient id="glow" cx=".62" cy=".42" r=".62">
      <stop offset="0" stop-color="#FFF3D3" stop-opacity=".42"/>
      <stop offset="1" stop-color="#FFF3D3" stop-opacity="0"/>
    </radialGradient>
    <radialGradient id="vignette" cx=".5" cy=".45" r=".78">
      <stop offset=".45" stop-color="#08131B" stop-opacity="0"/>
      <stop offset="1" stop-color="#08131B" stop-opacity="${vignette}"/>
    </radialGradient>
    ${gold}
    <filter id="soft"><feGaussianBlur stdDeviation="16"/></filter>
    <filter id="softSm"><feGaussianBlur stdDeviation="5"/></filter>
    ${grain}
  </defs>

  <rect width="${W}" height="${H}" fill="url(#wall)"/>
  <rect width="${W}" height="${H}" fill="url(#glow)"/>

  <!-- ceiling & mouldings -->
  <rect x="0" y="0" width="${W}" height="${H * 0.045}" fill="#08131B" opacity=".22"/>
  <rect x="0" y="${H * 0.045}" width="${W}" height="6" fill="${sofa}" opacity=".18"/>
  <rect x="0" y="${H * 0.09}" width="${W}" height="7" fill="#000" opacity=".16"/>
  <rect x="0" y="${H * 0.09 + 7}" width="${W}" height="2" fill="${accent}" opacity=".5"/>
  <rect x="0" y="${horizon - 30}" width="${W}" height="16" fill="#000" opacity=".14"/>
  <rect x="0" y="${horizon - 14}" width="${W}" height="14" fill="${sofa}" opacity=".24"/>

  <!-- floor -->
  <rect x="0" y="${horizon}" width="${W}" height="${H - horizon}" fill="url(#floorGrad)"/>
  <g stroke="#F6E9CF" stroke-opacity=".055" stroke-width="2">
    ${Array.from({ length: 11 }, (_, i) => `<path d="M${(W / 10) * i} ${H} L${W * 0.5 + (i - 5) * 58} ${horizon}"/>`).join('')}
    ${Array.from({ length: 4 }, (_, i) => `<path d="M0 ${horizon + 34 + i * 44} H${W}"/>`).join('')}
  </g>

  <!-- rug -->
  <g>
    <path d="M${W * 0.18} ${horizon + 34} H${W * 0.82} L${W * 0.92} ${H - 16} H${W * 0.08} Z" fill="${sofa}" opacity=".12"/>
    <path d="M${W * 0.205} ${horizon + 46} H${W * 0.795} L${W * 0.88} ${H - 28} H${W * 0.12} Z" fill="none" stroke="${accent}" stroke-width="2" opacity=".35"/>
    <path d="M${W * 0.24} ${horizon + 60} L${W * 0.77} ${H - 40} M${W * 0.76} ${horizon + 60} L${W * 0.23} ${H - 40}" stroke="${accent}" stroke-width="1.5" opacity=".12"/>
  </g>

  <!-- window -->
  <g>
    <rect x="${winX - 22}" y="${winY - 22}" width="${winW + 44}" height="${winH + 44}" fill="${sofa}" opacity=".13"/>
    <path d="${arch}" fill="url(#sky)"/>
    <g opacity=".3" fill="#46503F">
      <path d="M${winX + winW * 0.06} ${winY + winH} L${winX + winW * 0.14} ${winY + winH * 0.66} L${winX + winW * 0.28} ${winY + winH}Z"/>
      <path d="M${winX + winW * 0.7} ${winY + winH} L${winX + winW * 0.82} ${winY + winH * 0.62} L${winX + winW * 0.96} ${winY + winH}Z"/>
    </g>
    <path d="${arch}" fill="none" stroke="${accent}" stroke-opacity=".6" stroke-width="5"/>
    <g stroke="${accent}" stroke-opacity=".45" stroke-width="3">
      <path d="M${winCX} ${winY + 8} V${winY + winH}"/>
      <path d="M${winX} ${winY + winH * 0.62} H${winX + winW}"/>
    </g>
    <rect x="${winX - 6}" y="${winY + winH}" width="${winW + 12}" height="14" fill="${sofa}" opacity=".45"/>
  </g>

  <!-- curtains -->
  <g>
    <rect x="${winX - 96}" y="${winY - 46}" width="${winW + 192}" height="8" rx="4" fill="url(#gold)" opacity=".7"/>
    <path d="M${winX - 92} ${winY - 40} C${winX - 50} ${winY + 80} ${winX - 78} ${winY + winH * .55} ${winX - 58} ${horizon + 16}
             L${winX - 8} ${horizon + 16} C${winX - 28} ${winY + winH * .5} ${winX - 6} ${winY + 100} ${winX + 8} ${winY - 40} Z"
          fill="${sofa}" opacity=".38"/>
    <path d="M${winX + winW + 92} ${winY - 40} C${winX + winW + 50} ${winY + 80} ${winX + winW + 78} ${winY + winH * .55} ${winX + winW + 58} ${horizon + 16}
             L${winX + winW + 8} ${horizon + 16} C${winX + winW + 28} ${winY + winH * .5} ${winX + winW + 6} ${winY + 100} ${winX + winW - 8} ${winY - 40} Z"
          fill="${sofa}" opacity=".34"/>
    <g stroke="#000" stroke-opacity=".08" stroke-width="2" fill="none">
      <path d="M${winX - 74} ${winY - 30} C${winX - 48} ${winY + 190} ${winX - 54} ${winY + winH * .75} ${winX - 44} ${horizon}"/>
      <path d="M${winX + winW + 74} ${winY - 30} C${winX + winW + 48} ${winY + 190} ${winX + winW + 54} ${winY + winH * .75} ${winX + winW + 44} ${horizon}"/>
    </g>
  </g>

  ${lightShaft(winX + winW * 0.12, winY + 30, winW * 0.72, horizon - winY, 0.22)}
  <rect width="${W}" height="${H}" fill="#0C1E2E" opacity="${haze}"/>

  <!-- artwork -->
  ${frame(artX, artY, artW, artH, artMarkup)}

  <!-- console under the artwork -->
  <g>
    <rect x="${W * 0.315}" y="${horizon - 232}" width="${W * 0.25}" height="9" rx="4" fill="url(#gold)" opacity=".8"/>
    <rect x="${W * 0.33}" y="${horizon - 223}" width="6" height="${H * 0.09}" fill="url(#gold)" opacity=".55"/>
    <rect x="${W * 0.548}" y="${horizon - 223}" width="6" height="${H * 0.09}" fill="url(#gold)" opacity=".55"/>
    <path d="M${W * 0.415} ${horizon - 232} h24 l-5 -42 h-14 z" fill="#EDE4D2" opacity=".92"/>
    <g stroke="#66765C" stroke-width="3" fill="none" stroke-linecap="round">
      <path d="M${W * 0.425} ${horizon - 274} q -10 -30 -28 -44"/>
      <path d="M${W * 0.432} ${horizon - 274} q 6 -34 24 -48"/>
      <path d="M${W * 0.428} ${horizon - 274} q -2 -26 4 -42"/>
    </g>
    <rect x="${W * 0.47}" y="${horizon - 243}" width="56" height="11" rx="2" fill="#E7DBC4" opacity=".9"/>
    <rect x="${W * 0.473}" y="${horizon - 254}" width="42" height="11" rx="2" fill="${accent}" opacity=".75"/>
  </g>

  <!-- sofa -->
  <g>
    <ellipse cx="${W * 0.475}" cy="${horizon + 24}" rx="${W * 0.3}" ry="16" fill="#000" opacity=".25" filter="url(#softSm)"/>
    <rect x="${W * 0.305}" y="${horizon - 158}" width="${W * 0.39}" height="62" rx="22" fill="url(#sofaGrad)"/>
    <rect x="${W * 0.28}" y="${horizon - 112}" width="${W * 0.44}" height="86" rx="16" fill="url(#sofaGrad)"/>
    <g fill="none" stroke="#000" stroke-opacity=".09" stroke-width="2">
      <rect x="${W * 0.302}" y="${horizon - 148}" width="${W * 0.18}" height="56" rx="14"/>
      <rect x="${W * 0.518}" y="${horizon - 148}" width="${W * 0.18}" height="56" rx="14"/>
      <rect x="${W * 0.3}" y="${horizon - 88}" width="${W * 0.19}" height="52" rx="10"/>
      <rect x="${W * 0.51}" y="${horizon - 88}" width="${W * 0.19}" height="52" rx="10"/>
    </g>
    <rect x="${W * 0.305}" y="${horizon - 150}" width="68" height="66" rx="12" transform="rotate(-6 ${W * 0.305} ${horizon - 150})" fill="${accent}" opacity=".82"/>
    <rect x="${W * 0.6}" y="${horizon - 152}" width="60" height="64" rx="12" transform="rotate(5 ${W * 0.6} ${horizon - 152})" fill="${sofa}" opacity=".95" stroke="#000" stroke-opacity=".1"/>
    <path d="M${W * 0.29} ${horizon - 116} q 30 -18 56 6 q -10 44 -58 46 z" fill="${sofa}" opacity=".55"/>
    <rect x="${W * 0.28}" y="${horizon - 32}" width="${W * 0.44}" height="10" fill="#000" opacity=".14"/>
    <g stroke="#3A3126" stroke-width="6" opacity=".6">
      <path d="M${W * 0.31} ${horizon - 22} V${horizon + 12}"/>
      <path d="M${W * 0.67} ${horizon - 22} V${horizon + 12}"/>
    </g>
  </g>

  <!-- coffee table -->
  <g>
    <ellipse cx="${W * 0.47}" cy="${horizon + 96}" rx="${W * 0.16}" ry="16" fill="#000" opacity=".3"/>
    <rect x="${W * 0.36}" y="${horizon + 48}" width="${W * 0.22}" height="12" rx="6" fill="url(#gold)" opacity=".85"/>
    <rect x="${W * 0.395}" y="${horizon + 60}" width="7" height="${H * 0.052}" fill="url(#gold)" opacity=".55"/>
    <rect x="${W * 0.54}" y="${horizon + 60}" width="7" height="${H * 0.052}" fill="url(#gold)" opacity=".55"/>
    <g>
      <path d="M${W * 0.435} ${horizon + 48} h30 l-5 -20 h-20 z" fill="#EDE4D2" opacity=".95"/>
      <path d="M${W * 0.44} ${horizon + 42} q -16 -8 -14 -24 M${W * 0.465} ${horizon + 40} q 4 -22 18 -30 M${W * 0.458} ${horizon + 42} q -6 -24 -22 -30" stroke="#7F8F72" stroke-width="3" fill="none" stroke-linecap="round"/>
    </g>
    <rect x="${W * 0.505}" y="${horizon + 34}" width="60" height="10" rx="2" fill="#E9DDC7" opacity=".9"/>
    <rect x="${W * 0.505}" y="${horizon + 23}" width="46" height="10" rx="2" fill="${accent}" opacity=".8"/>
  </g>

  <!-- side table & lamp -->
  <g>
    <rect x="${W * 0.755}" y="${horizon - 58}" width="${W * 0.11}" height="8" rx="4" fill="url(#gold)" opacity=".7"/>
    <path d="M${W * 0.768} ${horizon - 50} V${horizon + 26} M${W * 0.852} ${horizon - 50} V${horizon + 26}" stroke="url(#gold)" stroke-width="4" opacity=".55"/>
    <rect x="${W * 0.805}" y="${horizon - 160}" width="6" height="102" fill="url(#gold)" opacity=".6"/>
    <path d="M${W * 0.805 - 36} ${horizon - 154} h78 l-15 -50 h-48 z" fill="#F3E6C8" opacity=".92"/>
    <ellipse cx="${W * 0.805}" cy="${horizon - 156}" rx="58" ry="10" fill="#FFF3D3" opacity=".3" filter="url(#softSm)"/>
    <circle cx="${W * 0.805}" cy="${horizon - 160}" r="4" fill="#FFF3D3" opacity=".8"/>
  </g>

  <!-- plant -->
  <g transform="translate(${W * 0.905} ${horizon + 10})">
    <path d="M-24 0 h48 l-8 60 h-32 z" fill="#3A3126" opacity=".85"/>
    <path d="M-24 0 h48 l-2 10 h-44 z" fill="url(#gold)" opacity=".5"/>
    <g stroke="#586B57" stroke-width="4" fill="none" stroke-linecap="round">
      <path d="M0 0 C-4 -50 -26 -62 -38 -94"/>
      <path d="M2 -6 C12 -54 34 -66 44 -100"/>
      <path d="M0 -10 C-2 -60 4 -92 0 -124"/>
      <path d="M-38 -94 q -24 -4 -28 -26 q 26 -2 28 26"/>
      <path d="M44 -100 q 26 -4 30 -26 q -28 -2 -30 26"/>
      <path d="M0 -124 q 2 -26 -12 -40 q -16 20 12 40"/>
    </g>
  </g>

  <rect width="${W}" height="${H}" fill="url(#vignette)"/>
  <rect width="${W}" height="${H}" filter="url(#grain)" opacity=".5"/>
</svg>`;
}

/** Exterior weatherboard home at golden hour. */
function facade({ width = 1200, height = 900 } = {}) {
  const W = width;
  const H = height;
  const base = H * 0.8;
  const wall = '#EADFC9';
  const trim = '#F7F1E4';
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" role="img">
  <defs>
    <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#24384C"/>
      <stop offset=".62" stop-color="#B7A183"/>
      <stop offset="1" stop-color="#EBD8B2"/>
    </linearGradient>
    <linearGradient id="wallGrad" x1="0" y1="0" x2=".3" y2="1">
      <stop offset="0" stop-color="${wall}"/>
      <stop offset="1" stop-color="#D6C7AC"/>
    </linearGradient>
    ${gold}
    <filter id="soft"><feGaussianBlur stdDeviation="16"/></filter>
    ${grain}
  </defs>
  <rect width="${W}" height="${H}" fill="url(#sky)"/>
  <circle cx="${W * 0.79}" cy="${H * 0.23}" r="${H * 0.15}" fill="#F8E9C6" opacity=".55" filter="url(#soft)"/>
  <g fill="#1E2E3A" opacity=".3">
    <path d="M0 ${H * 0.63} Q ${W * 0.18} ${H * 0.53} ${W * 0.42} ${H * 0.67} V${H * 0.8} H0 Z"/>
    <path d="M${W * 0.62} ${H * 0.66} Q ${W * 0.82} ${H * 0.55} ${W} ${H * 0.64} V${H * 0.8} H${W * 0.62} Z"/>
  </g>

  <rect x="${W * 0.12}" y="${H * 0.35}" width="${W * 0.7}" height="${base - H * 0.35}" fill="url(#wallGrad)"/>
  <g stroke="${trim}" stroke-opacity=".5" stroke-width="3">
    ${Array.from({ length: 8 }, (_, i) => `<path d="M${W * 0.12} ${H * 0.35 + (base - H * 0.35) * (i + 1) / 9} H${W * 0.82}"/>`).join('')}
  </g>
  <rect x="${W * 0.12}" y="${base - 12}" width="${W * 0.7}" height="12" fill="${trim}" opacity=".55"/>

  <path d="M${W * 0.06} ${H * 0.36} L${W * 0.47} ${H * 0.12} L${W * 0.88} ${H * 0.36} Z" fill="#2C3033"/>
  <path d="M${W * 0.06} ${H * 0.36} L${W * 0.47} ${H * 0.12} L${W * 0.88} ${H * 0.36}" fill="none" stroke="${trim}" stroke-opacity=".3" stroke-width="10"/>
  <rect x="${W * 0.6}" y="${H * 0.155}" width="40" height="${H * 0.145}" fill="#2C3033"/>
  <rect x="${W * 0.585}" y="${H * 0.15}" width="64" height="13" fill="${trim}" opacity=".55"/>
  <path d="M${W * 0.12} ${H * 0.355} H${W * 0.82}" stroke="#000" stroke-opacity=".18" stroke-width="10"/>

  ${[0.2, 0.58].map(x => `
    <g>
      <rect x="${W * x}" y="${H * 0.43}" width="${W * 0.15}" height="${H * 0.23}" fill="#2A3B47"/>
      <rect x="${W * x + 8}" y="${H * 0.43 + 8}" width="${W * 0.15 - 16}" height="${H * 0.23 - 16}" fill="#B7C4C3" opacity=".72"/>
      <path d="M${W * x} ${H * 0.43} q ${W * 0.075} 26 ${W * 0.15} 0 v-8 h-${W * 0.15} z" fill="${trim}" opacity=".9"/>
      <g stroke="${trim}" stroke-width="6">
        <path d="M${W * x + W * 0.075} ${H * 0.43} V${H * 0.66}"/>
        <path d="M${W * x} ${H * 0.545} H${W * x + W * 0.15}"/>
      </g>
      <rect x="${W * x - 8}" y="${H * 0.66}" width="${W * 0.15 + 16}" height="12" fill="${trim}"/>
      <path d="M${W * x - 6} ${H * 0.43 - 4} q ${W * 0.085} -44 ${W * 0.15 + 12} 0 z" fill="${trim}" opacity=".9"/>
    </g>`).join('')}

  <g>
    <rect x="${W * 0.47}" y="${H * 0.48}" width="${W * 0.09}" height="${base - H * 0.48}" fill="#26384B"/>
    <rect x="${W * 0.478}" y="${H * 0.495}" width="${W * 0.074}" height="${H * 0.11}" fill="#C9BFA9" opacity=".22"/>
    <rect x="${W * 0.478}" y="${H * 0.625}" width="${W * 0.074}" height="${H * 0.09}" fill="#C9BFA9" opacity=".12"/>
    <circle cx="${W * 0.547}" cy="${H * 0.62}" r="4" fill="url(#gold)"/>
    <path d="M${W * 0.45} ${H * 0.468} h ${W * 0.13} l -14 -20 h ${-W * 0.10} z" fill="${trim}"/>
    <circle cx="${W * 0.576}" cy="${H * 0.45}" r="6" fill="#F5E4B8" opacity=".85"/>
  </g>

  <g>
    <rect x="0" y="${H * 0.86}" width="${W}" height="9" fill="${trim}" opacity=".85"/>
    <rect x="0" y="${H * 0.945}" width="${W}" height="9" fill="${trim}" opacity=".7"/>
    ${Array.from({ length: 36 }, (_, i) => `<rect x="${i * (W / 36) + 5}" y="${H * 0.825}" width="11" height="${H * 0.175}" rx="4" fill="${trim}" opacity=".9"/>`).join('')}
  </g>

  <g fill="#3F5140" opacity=".9">
    <path d="M${W * 0.035} ${H * 0.85} q 26 -140 52 -170 q 22 100 8 170 z"/>
    <path d="M${W * 0.885} ${H * 0.85} q 38 -150 68 -180 q 26 110 6 180 z"/>
    <path d="M${W * 0.3} ${H * 0.85} q 30 -70 54 -80 q 8 46 -6 80 z" opacity=".66"/>
    <path d="M${W * 0.72} ${H * 0.85} q 24 -56 46 -64 q 8 38 -4 64 z" opacity=".6"/>
  </g>
  <rect width="${W}" height="${H}" filter="url(#grain)" opacity=".45"/>
</svg>`;
}

/** Timber deck, pergola and garden at dusk. */
function deck({ width = 1200, height = 900 } = {}) {
  const W = width;
  const H = height;
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" role="img">
  <defs>
    <linearGradient id="dusk" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#1A2C3C"/>
      <stop offset=".5" stop-color="#53645F"/>
      <stop offset="1" stop-color="#D3BC93"/>
    </linearGradient>
    <linearGradient id="timber" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#A17E52"/>
      <stop offset="1" stop-color="#5F462D"/>
    </linearGradient>
    ${gold}
    <filter id="soft"><feGaussianBlur stdDeviation="14"/></filter>
    <filter id="softSm"><feGaussianBlur stdDeviation="4"/></filter>
    ${grain}
  </defs>
  <rect width="${W}" height="${H}" fill="url(#dusk)"/>
  <circle cx="${W * 0.73}" cy="${H * 0.29}" r="${H * 0.12}" fill="#F8E9C6" opacity=".45" filter="url(#soft)"/>

  <g fill="#20372F" opacity=".9">
    <path d="M0 ${H * 0.62} Q ${W * 0.12} ${H * 0.38} ${W * 0.3} ${H * 0.62} Z"/>
    <path d="M${W * 0.52} ${H * 0.62} Q ${W * 0.72} ${H * 0.34} ${W} ${H * 0.55} V${H * 0.66} H${W * 0.52} Z"/>
    <path d="M${W * 0.3} ${H * 0.62} Q ${W * 0.42} ${H * 0.44} ${W * 0.55} ${H * 0.62} Z"/>
  </g>

  <!-- pergola -->
  <g stroke="#26221C" fill="none" opacity=".95">
    <path d="M${W * 0.045} ${H * 0.66} V${H * 0.07} H${W * 0.955} V${H * 0.66}" stroke-width="22"/>
    <path d="M${W * 0.045} ${H * 0.095} H${W * 0.955}" stroke-width="14"/>
    ${Array.from({ length: 14 }, (_, i) => `<path d="M${W * 0.06 + i * W * 0.068} ${H * 0.07} V${H * 0.13}" stroke-width="9"/>`).join('')}
  </g>

  <!-- string lights -->
  <g>
    <path d="M${W * 0.06} ${H * 0.16} Q ${W * 0.5} ${H * 0.29} ${W * 0.94} ${H * 0.16}" stroke="#2A2620" stroke-width="2" fill="none"/>
    ${Array.from({ length: 11 }, (_, i) => {
      const t = i / 10;
      const x = W * 0.06 + t * W * 0.88;
      const y = H * 0.16 + Math.sin(Math.PI * t) * H * 0.11;
      return `<circle cx="${x}" cy="${y + 8}" r="5" fill="#F7E2AC" opacity=".9"/><circle cx="${x}" cy="${y + 8}" r="16" fill="#F7E2AC" opacity=".18" filter="url(#softSm)"/>`;
    }).join('')}
  </g>

  <!-- fence -->
  <g>
    <rect x="${W * 0.04}" y="${H * 0.5}" width="${W * 0.92}" height="${H * 0.14}" fill="#7B6042"/>
    <g stroke="#3D2E1F" stroke-width="4" opacity=".55">
      ${Array.from({ length: 22 }, (_, i) => `<path d="M${W * 0.05 + i * W * 0.042} ${H * 0.5} V${H * 0.64}"/>`).join('')}
    </g>
    <rect x="${W * 0.04}" y="${H * 0.62}" width="${W * 0.92}" height="6" fill="#3D2E1F" opacity=".3"/>
  </g>

  <rect x="0" y="${H * 0.64}" width="${W}" height="${H * 0.36}" fill="url(#timber)"/>
  <g stroke="#352618" stroke-opacity=".55" stroke-width="3">
    ${Array.from({ length: 15 }, (_, i) => `<path d="M${W * 0.5} ${H * 0.64} L${i * W * 0.08 - W * 0.08} ${H}"/>`).join('')}
    ${Array.from({ length: 8 }, (_, i) => `<path d="M0 ${H * 0.68 + i * H * 0.042} H${W}"/>`).join('')}
  </g>

  <!-- outdoor seating -->
  <g>
    <ellipse cx="${W * 0.27}" cy="${H * 0.79}" rx="${W * 0.18}" ry="14" fill="#1B140D" opacity=".35" filter="url(#softSm)"/>
    <rect x="${W * 0.14}" y="${H * 0.69}" width="${W * 0.26}" height="${H * 0.09}" rx="6" fill="#E9DDC6"/>
    <rect x="${W * 0.14}" y="${H * 0.63}" width="${W * 0.26}" height="${H * 0.08}" rx="10" fill="#F3E9D7"/>
    <rect x="${W * 0.15}" y="${H * 0.642}" width="74" height="48" rx="6" fill="#C9A75D" opacity=".7"/>
    <rect x="${W * 0.32}" y="${H * 0.67}" width="56" height="46" rx="8" fill="#43594B" opacity=".85"/>
    <rect x="${W * 0.17}" y="${H * 0.72}" width="8" height="${H * 0.055}" fill="#3A2C1E"/>
    <rect x="${W * 0.36}" y="${H * 0.72}" width="8" height="${H * 0.055}" fill="#3A2C1E"/>
    <rect x="${W * 0.46}" y="${H * 0.735}" width="${W * 0.15}" height="10" rx="4" fill="#3A2C1E"/>
    <rect x="${W * 0.475}" y="${H * 0.745}" width="7" height="52" fill="#3A2C1E"/>
    <rect x="${W * 0.585}" y="${H * 0.745}" width="7" height="52" fill="#3A2C1E"/>
    <rect x="${W * 0.5}" y="${H * 0.715}" width="30" height="18" rx="2" fill="#E8DCC5" opacity=".8"/>
  </g>

  <g transform="translate(${W * 0.78} ${H * 0.715})">
    <path d="M-36 0 h72 l-10 78 h-52 z" fill="#2E2A24"/>
    <path d="M-36 0 h72 l-2 10 h-68 z" fill="#C6A45F" opacity=".4"/>
    <g stroke="#4E6150" stroke-width="5" fill="none" stroke-linecap="round">
      <path d="M0 0 q -6 -52 0 -102"/>
      <path d="M0 -44 q -32 -22 -44 -54 q 32 6 44 54"/>
      <path d="M0 -64 q 32 -24 46 -56 q -32 6 -46 56"/>
      <path d="M0 -96 q 6 -32 -4 -50 q -14 24 4 50"/>
    </g>
  </g>
  <rect width="${W}" height="${H}" filter="url(#grain)" opacity=".45"/>
</svg>`;
}

/** Close-up of a roller laying a freshly painted stroke. */
function roller({ width = 1200, height = 900, base = '#33433C' } = {}) {
  const W = width;
  const H = height;
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" role="img">
  <defs>
    <linearGradient id="wall" x1="0" y1="0" x2=".7" y2="1">
      <stop offset="0" stop-color="${base}"/>
      <stop offset="1" stop-color="#15252B"/>
    </linearGradient>
    <linearGradient id="paint" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#F4EAD4"/>
      <stop offset="1" stop-color="#D6C39D"/>
    </linearGradient>
    ${gold}
    <filter id="soft"><feGaussianBlur stdDeviation="8"/></filter>
    ${grain}
  </defs>
  <rect width="${W}" height="${H}" fill="url(#wall)"/>
  <path d="M0 ${H * 0.42} C ${W * 0.24} ${H * 0.2} ${W * 0.7} ${H * 0.5} ${W} ${H * 0.3} V${H} H0 Z" fill="url(#paint)"/>
  <path d="M${W * 0.02} ${H * 0.41} C ${W * 0.3} ${H * 0.24} ${W * 0.68} ${H * 0.46} ${W * 0.985} ${H * 0.31}" fill="none" stroke="#B39B6E" stroke-width="3" stroke-dasharray="10 12" opacity=".5"/>
  <g opacity=".08" fill="#5A4B33">
    ${Array.from({ length: 26 }, (_, i) => `<circle cx="${(i * 137) % W}" cy="${H * 0.55 + (i * 53) % (H * 0.4)}" r="${8 + (i % 4) * 4}"/>`).join('')}
  </g>
  <g transform="rotate(-24 ${W * 0.5} ${H * 0.42})">
    <rect x="${W * 0.2}" y="${H * 0.32}" width="${W * 0.42}" height="${H * 0.16}" rx="26" fill="#E8DCC4"/>
    <rect x="${W * 0.2}" y="${H * 0.36}" width="${W * 0.42}" height="${H * 0.04}" fill="#C9A75D" opacity=".45"/>
    <rect x="${W * 0.24}" y="${H * 0.48}" width="16" height="40" fill="#B9B4A8"/>
    <rect x="${W * 0.24}" y="${H * 0.5}" width="${W * 0.3}" height="20" rx="10" fill="#B9B4A8"/>
    <rect x="${W * 0.52}" y="${H * 0.5}" width="22" height="${H * 0.24}" rx="8" fill="#8E877A"/>
    <rect x="${W * 0.54}" y="${H * 0.5}" width="26" height="${H * 0.3}" rx="10" fill="#4A4034"/>
  </g>
  <path d="M${W * 0.08} ${H * 0.12} V${H * 0.9}" stroke="#C6A45F" stroke-width="2" opacity=".3"/>
  <circle cx="${W * 0.82}" cy="${H * 0.18}" r="${H * 0.08}" fill="#F4EAD4" opacity=".12" filter="url(#soft)"/>
  <rect width="${W}" height="${H}" filter="url(#grain)" opacity=".55"/>
</svg>`;
}

/** Editorial still life: colour swatches, brush and paint tin. */
function swatches({ width = 1200, height = 900 } = {}) {
  const W = width;
  const H = height;
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" role="img">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#F4EDDF"/>
      <stop offset="1" stop-color="#CDBC9F"/>
    </linearGradient>
    ${gold}
    <filter id="soft"><feGaussianBlur stdDeviation="22"/></filter>
    ${grain}
  </defs>
  <rect width="${W}" height="${H}" fill="url(#bg)"/>
  <circle cx="${W * 0.72}" cy="${H * 0.34}" r="${W * 0.32}" fill="#FBF6EA" opacity=".6" filter="url(#soft)"/>

  <rect x="${W * 0.1}" y="${H * 0.12}" width="${W * 0.38}" height="${H * 0.76}" fill="#F7F0E1"/>
  <rect x="${W * 0.1}" y="${H * 0.12}" width="${W * 0.38}" height="${H * 0.38}" fill="#43594B"/>
  <rect x="${W * 0.1}" y="${H * 0.5}" width="${W * 0.38}" height="${H * 0.38}" fill="#B9C0B3"/>
  <rect x="${W * 0.1}" y="${H * 0.5}" width="${W * 0.38}" height="4" fill="#C6A45F" opacity=".6"/>

  <g transform="rotate(-8 ${W * 0.7} ${H * 0.5})">
    ${['#26384B', '#43594B', '#8F9A88', '#E8DCC5', '#C6A45F'].map((c, i) => `
      <g transform="translate(${W * 0.52 + i * 26} ${H * 0.2 + i * 6})">
        <rect width="${W * 0.15}" height="${H * 0.5}" rx="4" fill="#FFFDF7" opacity=".95"/>
        <rect x="10" y="10" width="${W * 0.15 - 20}" height="${H * 0.3}" fill="${c}"/>
        <rect x="10" y="${H * 0.35}" width="${W * 0.1}" height="6" fill="#8C8273" opacity=".5"/>
        <rect x="10" y="${H * 0.38}" width="${W * 0.07}" height="5" fill="#8C8273" opacity=".4"/>
      </g>`).join('')}
  </g>

  <g transform="rotate(18 ${W * 0.7} ${H * 0.75})">
    <rect x="${W * 0.3}" y="${H * 0.76}" width="${W * 0.5}" height="14" rx="7" fill="#B08A56"/>
    <rect x="${W * 0.76}" y="${H * 0.73}" width="50" height="42" rx="5" fill="url(#gold)"/>
    <path d="M${W * 0.8} ${H * 0.76} q 26 6 26 36 q -26 0 -26 -36" fill="#43594B"/>
  </g>
  <ellipse cx="${W * 0.33}" cy="${H * 0.68}" rx="36" ry="52" fill="#2E3B36" opacity=".45" filter="url(#soft)"/>
  <rect width="${W}" height="${H}" filter="url(#grain)" opacity=".4"/>
</svg>`;
}

/** Abstract editorial texture for journal cards. */
function texture({ from, to, shape = '#C6A45F', width = 1200, height = 900 }) {
  const W = width;
  const H = height;
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" role="img">
  <defs>
    <linearGradient id="t" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="${from}"/>
      <stop offset="1" stop-color="${to}"/>
    </linearGradient>
    <filter id="soft"><feGaussianBlur stdDeviation="30"/></filter>
    ${grain}
  </defs>
  <rect width="${W}" height="${H}" fill="url(#t)"/>
  <circle cx="${W * 0.72}" cy="${H * 0.3}" r="${H * 0.32}" fill="${shape}" opacity=".4" filter="url(#soft)"/>
  <path d="M0 ${H * 0.7} C ${W * 0.25} ${H * 0.4} ${W * 0.6} ${H * 0.9} ${W} ${H * 0.55} V${H} H0 Z" fill="#000" opacity=".18"/>
  <g stroke="${shape}" stroke-width="2" opacity=".4">
    ${Array.from({ length: 8 }, (_, i) => `<circle cx="${W * 0.5}" cy="${H * 0.45}" r="${70 + i * 34}" fill="none"/>`).join('')}
  </g>
  <rect width="${W}" height="${H}" filter="url(#grain)" opacity=".55"/>
</svg>`;
}

const files = {
  'hero.svg': room({
    wallTop: '#43544C', wallBottom: '#14242A', floor: '#241F1A', sofa: '#E8DCC6',
    accent: '#C6A45F', art: 'lion', haze: 0.12, vignette: 0.34, width: 1920, height: 1080,
  }),
  'project-toorak.svg': room({
    wallTop: '#4C5B4D', wallBottom: '#2B3A31', floor: '#2A241D', sofa: '#E9DFCB', accent: '#C6A45F',
  }),
  'project-brighton.svg': room({
    wallTop: '#6E818B', wallBottom: '#3A4D58', floor: '#2B2A26', sofa: '#EFE8D9', accent: '#C6A45F',
  }),
  'project-fitzroy.svg': room({
    wallTop: '#7E5B49', wallBottom: '#483128', floor: '#2C231C', sofa: '#EADFCB', accent: '#D8BE8A',
  }),
  'project-south-yarra.svg': room({
    wallTop: '#70685C', wallBottom: '#3E3933', floor: '#27231F', sofa: '#E5DAC6', accent: '#C6A45F',
  }),
  'project-hawthorn.svg': facade(),
  'project-port-melbourne.svg': deck(),
  'project-commercial.svg': room({
    wallTop: '#36484E', wallBottom: '#1A292F', floor: '#252522', sofa: '#DCD2BE', accent: '#C6A45F', haze: 0.1,
  }),
  'about.svg': roller({ base: '#3E5148' }),
  'blog-sheen.svg': roller({ base: '#5A4E42' }),
  'blog-palette.svg': swatches(),
  'blog-weather.svg': texture({ from: '#26384B', to: '#5E6C64', shape: '#C6A45F' }),
  'before.svg': room({
    wallTop: '#8D8272', wallBottom: '#6E6558', floor: '#4A4238', sofa: '#C9BFAE', accent: '#9C8A6A', haze: 0.04, vignette: 0.18, width: 1600, height: 1000,
  }),
  'after.svg': room({
    wallTop: '#3F5148', wallBottom: '#22332C', floor: '#2A231B', sofa: '#EDE3D0', accent: '#C6A45F', haze: 0.02, vignette: 0.24, width: 1600, height: 1000,
  }),
};

for (const [name, svg] of Object.entries(files)) {
  writeFileSync(resolve(OUT, name), svg.trim() + '\n');
}

console.log(`Generated ${Object.keys(files).length} artwork files in public/images.`);
