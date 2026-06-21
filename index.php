<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = current_user();
page_start('a-amusement — a social space for everyone', 'landing', $user);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>a-amusement — a social space for everyone</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg:        #0D0F1A;
    --surface:   #13162A;
    --border:    #1E2240;
    --violet:    #7B5EA7;
    --violet-lt: #9B7EC8;
    --mint:      #4FFFB0;
    --mint-dk:   #00C97A;
    --text:      #E8EAF6;
    --muted:     #8087A8;
    --font-head: 'Space Grotesk', sans-serif;
    --font-body: 'Inter', sans-serif;
  }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: var(--font-body);
    line-height: 1.6;
    min-height: 100vh;
    overflow-x: hidden;
  }

  /* ── HERO ── */
  .hero {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 4rem 1.5rem 5rem;
    text-align: center;
    overflow: hidden;
  }

  .hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 80% 60% at 50% 20%, rgba(123,94,167,.35) 0%, transparent 70%),
      radial-gradient(ellipse 60% 40% at 20% 80%, rgba(79,255,176,.12) 0%, transparent 60%),
      radial-gradient(ellipse 50% 35% at 80% 70%, rgba(123,94,167,.15) 0%, transparent 60%);
    pointer-events: none;
    animation: auroraPulse 8s ease-in-out infinite alternate;
  }

  @keyframes auroraPulse {
    from { opacity: .7; transform: scale(1); }
    to   { opacity: 1;  transform: scale(1.04); }
  }

  /* Star field */
  .hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      radial-gradient(1px 1px at 10% 15%, rgba(255,255,255,.4) 0%, transparent 100%),
      radial-gradient(1px 1px at 25% 40%, rgba(255,255,255,.25) 0%, transparent 100%),
      radial-gradient(1px 1px at 60% 10%, rgba(255,255,255,.35) 0%, transparent 100%),
      radial-gradient(1px 1px at 75% 55%, rgba(255,255,255,.2) 0%, transparent 100%),
      radial-gradient(1px 1px at 88% 25%, rgba(255,255,255,.3) 0%, transparent 100%),
      radial-gradient(1px 1px at 45% 70%, rgba(255,255,255,.2) 0%, transparent 100%),
      radial-gradient(1px 1px at 5%  85%, rgba(255,255,255,.25) 0%, transparent 100%),
      radial-gradient(1px 1px at 90% 80%, rgba(255,255,255,.2) 0%, transparent 100%);
    pointer-events: none;
  }

  .hero-inner {
    position: relative;
    z-index: 1;
    max-width: 860px;
    width: 100%;
  }

  .hero-logo {
    width: min(680px, 92vw);
    margin: 0 auto 2.5rem;
    filter: drop-shadow(0 0 32px rgba(123,94,167,.6));
  }

  .hero-tagline {
    font-family: var(--font-head);
    font-size: clamp(1.6rem, 4vw, 2.6rem);
    font-weight: 600;
    letter-spacing: -.02em;
    line-height: 1.2;
    margin-bottom: 1.1rem;
    color: #fff;
  }

  .hero-tagline em {
    font-style: normal;
    color: var(--mint);
  }

  .hero-sub {
    font-size: clamp(.95rem, 1.8vw, 1.15rem);
    color: var(--muted);
    max-width: 560px;
    margin: 0 auto 2.5rem;
  }

  .hero-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    align-items: center;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .75rem 1.75rem;
    border-radius: 999px;
    font-family: var(--font-head);
    font-weight: 600;
    font-size: .95rem;
    text-decoration: none;
    transition: transform .15s, box-shadow .15s, background .15s;
    cursor: pointer;
    border: none;
  }
  .btn:hover { transform: translateY(-2px); }

  .btn-primary {
    background: var(--mint);
    color: #0D0F1A;
    box-shadow: 0 0 24px rgba(79,255,176,.35);
  }
  .btn-primary:hover { background: #6FFFBF; box-shadow: 0 0 36px rgba(79,255,176,.5); }

  .btn-ghost {
    background: transparent;
    color: var(--text);
    border: 1.5px solid var(--border);
  }
  .btn-ghost:hover { border-color: var(--violet-lt); color: #fff; }

  .theme-toggle {
    background: var(--surface);
    border: 1.5px solid var(--border);
    color: var(--text);
    border-radius: 999px;
    padding: .65rem 1rem;
    font-size: 1rem;
    cursor: pointer;
    transition: border-color .15s;
  }
  .theme-toggle:hover { border-color: var(--violet-lt); }

  /* ── UK BANNER ── */
  .uk-banner {
    position: relative;
    background: linear-gradient(135deg, rgba(123,94,167,.2) 0%, rgba(79,255,176,.08) 100%);
    border: 1px solid rgba(79,255,176,.25);
    border-radius: 16px;
    padding: 1.5rem 2rem;
    max-width: 760px;
    margin: 0 auto 0;
    text-align: center;
  }

  .uk-banner-label {
    display: inline-block;
    background: var(--mint);
    color: #0D0F1A;
    font-family: var(--font-head);
    font-weight: 700;
    font-size: .7rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: .25rem .65rem;
    border-radius: 999px;
    margin-bottom: .75rem;
  }

  .uk-banner h2 {
    font-family: var(--font-head);
    font-size: clamp(1.1rem, 2.5vw, 1.5rem);
    font-weight: 600;
    color: #fff;
    margin-bottom: .5rem;
  }

  .uk-banner p {
    color: var(--muted);
    font-size: .9rem;
    line-height: 1.55;
  }

  /* ── SECTIONS SHARED ── */
  section {
    padding: 5rem 1.5rem;
    max-width: 1100px;
    margin: 0 auto;
  }

  .section-label {
    font-family: var(--font-head);
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--violet-lt);
    margin-bottom: .75rem;
  }

  .section-title {
    font-family: var(--font-head);
    font-size: clamp(1.5rem, 3.5vw, 2.2rem);
    font-weight: 700;
    color: #fff;
    letter-spacing: -.02em;
    margin-bottom: 1rem;
    line-height: 1.2;
  }

  .section-desc {
    color: var(--muted);
    font-size: 1rem;
    max-width: 520px;
    margin-bottom: 3rem;
  }

  /* ── FEATURE GRID ── */
  .feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.25rem;
  }

  .feature-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.75rem;
    transition: border-color .2s, transform .2s;
  }
  .feature-card:hover {
    border-color: var(--violet);
    transform: translateY(-3px);
  }

  .feature-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(123,94,167,.3), rgba(79,255,176,.1));
    border: 1px solid rgba(123,94,167,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 1rem;
  }

  .feature-card h3 {
    font-family: var(--font-head);
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: .4rem;
  }

  .feature-card p {
    color: var(--muted);
    font-size: .875rem;
    line-height: 1.55;
  }

  /* ── INTEGRATIONS ── */
  .integrations-section {
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
  }

  .integrations-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 0;
  }

  .integration-pill {
    display: flex;
    align-items: center;
    gap: .6rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 999px;
    padding: .6rem 1.25rem .6rem .75rem;
    font-family: var(--font-head);
    font-size: .875rem;
    font-weight: 500;
    color: var(--text);
    transition: border-color .15s;
  }
  .integration-pill:hover { border-color: var(--violet-lt); color: #fff; }

  .integration-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .dot-live    { background: var(--mint); box-shadow: 0 0 6px var(--mint-dk); }
  .dot-soon    { background: var(--violet-lt); }

  .integration-status {
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
    margin-left: .25rem;
  }
  .status-live { color: var(--mint); }
  .status-soon { color: var(--violet-lt); }

  /* ── CTA ── */
  .cta-section {
    text-align: center;
    border: 1px solid var(--border);
    border-radius: 24px;
    background: var(--surface);
    padding: 4rem 2rem;
    position: relative;
    overflow: hidden;
  }

  .cta-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(123,94,167,.2) 0%, transparent 70%);
    pointer-events: none;
  }

  .cta-section h2 {
    font-family: var(--font-head);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    color: #fff;
    margin-bottom: .75rem;
    position: relative;
  }

  .cta-section p {
    color: var(--muted);
    font-size: 1rem;
    margin-bottom: 2rem;
    position: relative;
  }

  .cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    position: relative;
  }

  /* ── FOOTER ── */
  footer {
    border-top: 1px solid var(--border);
    padding: 2rem 1.5rem;
    text-align: center;
    color: var(--muted);
    font-size: .8rem;
  }

  footer a { color: var(--muted); text-decoration: none; }
  footer a:hover { color: var(--text); }

  /* ── DARK/LIGHT THEME ── */
  .theme-icon { display: none; }
  body:not(.light) .theme-icon-dark { display: inline; }
  body.light .theme-icon-light { display: inline; }

  body.light {
    --bg:      #F5F5FA;
    --surface: #FFFFFF;
    --border:  #D8D8EE;
    --text:    #1A1B2E;
    --muted:   #5C5F7A;
    background: var(--bg);
    color: var(--text);
  }
  body.light .hero::before {
    background:
      radial-gradient(ellipse 80% 60% at 50% 20%, rgba(123,94,167,.15) 0%, transparent 70%),
      radial-gradient(ellipse 60% 40% at 20% 80%, rgba(0,201,122,.1) 0%, transparent 60%);
  }
  body.light .hero-logo path { fill: var(--violet); }
  body.light .hero::after { opacity: 0; }
  body.light .btn-primary { color: #fff; background: var(--violet); box-shadow: 0 0 24px rgba(123,94,167,.3); }
  body.light .btn-primary:hover { background: var(--violet-lt); }

  @media (prefers-reduced-motion: reduce) {
    .hero::before { animation: none; }
    .btn, .feature-card { transition: none; }
  }

  @media (max-width: 600px) {
    .hero { padding: 3rem 1rem 4rem; }
    section { padding: 3.5rem 1rem; }
    .uk-banner { padding: 1.25rem; }
  }
</style>
</head>
<body>

<!-- HERO -->
<header class="hero">
  <div class="hero-inner">
    <div class="hero-logo" role="img" aria-label="a-amusement">
      <svg width="100%" height="auto" viewBox="0 0 3361 338" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M229.888 71.168C234.325 71.168 237.909 72.704 240.64 75.776C243.712 78.848 245.248 82.6027 245.248 87.04V317.44C245.248 321.536 243.712 325.12 240.64 328.192C237.568 331.264 233.984 332.8 229.888 332.8C225.451 332.8 221.696 331.264 218.624 328.192C215.893 325.12 214.528 321.536 214.528 317.44V256.512L223.232 252.416C223.232 261.973 220.501 271.701 215.04 281.6C209.92 291.499 202.752 300.715 193.536 309.248C184.32 317.781 173.397 324.779 160.768 330.24C148.48 335.36 135.168 337.92 120.832 337.92C97.6213 337.92 76.8 331.947 58.368 320C40.2773 308.053 25.9413 291.669 15.36 270.848C5.11999 250.027 -1.14441e-05 226.645 -1.14441e-05 200.704C-1.14441e-05 174.08 5.29066 150.699 15.872 130.56C26.4533 110.08 40.7893 94.0374 58.88 82.432C76.9707 70.8267 97.4507 65.024 120.32 65.024C134.997 65.024 148.651 67.584 161.28 72.704C174.251 77.824 185.515 84.8214 195.072 93.696C204.629 102.571 211.968 112.811 217.088 124.416C222.549 135.68 225.28 147.456 225.28 159.744L214.528 152.064V87.04C214.528 82.6027 215.893 78.848 218.624 75.776C221.696 72.704 225.451 71.168 229.888 71.168ZM123.392 309.248C141.483 309.248 157.525 304.64 171.52 295.424C185.515 285.867 196.437 272.896 204.288 256.512C212.48 239.787 216.576 221.184 216.576 200.704C216.576 180.565 212.48 162.475 204.288 146.432C196.437 130.389 185.515 117.589 171.52 108.032C157.525 98.4747 141.483 93.696 123.392 93.696C105.643 93.696 89.6 98.304 75.264 107.52C61.2693 116.736 50.176 129.365 41.984 145.408C34.1333 161.451 30.208 179.883 30.208 200.704C30.208 221.184 34.1333 239.787 41.984 256.512C50.176 272.896 61.2693 285.867 75.264 295.424C89.2587 304.64 105.301 309.248 123.392 309.248ZM329.956 187.904C325.86 187.904 322.276 186.368 319.204 183.296C316.473 180.224 315.108 176.469 315.108 172.032C315.108 167.936 316.473 164.523 319.204 161.792C322.276 158.72 325.86 157.184 329.956 157.184H440.548C444.644 157.184 448.228 158.72 451.3 161.792C454.372 164.864 455.908 168.619 455.908 173.056C455.908 177.152 454.372 180.736 451.3 183.808C448.228 186.539 444.644 187.904 440.548 187.904H329.956ZM741.424 59.392C748.933 59.392 755.077 61.952 759.856 67.072C764.635 71.8507 767.024 77.9947 767.024 85.504V306.688C767.024 314.197 764.635 320.512 759.856 325.632C755.077 330.411 748.933 332.8 741.424 332.8C734.256 332.8 728.112 330.411 722.992 325.632C718.213 320.512 715.824 314.197 715.824 306.688V269.824L726.576 271.36C726.576 277.504 724.016 284.331 718.896 291.84C714.117 299.349 707.461 306.688 698.928 313.856C690.395 320.683 680.325 326.485 668.72 331.264C657.115 335.701 644.656 337.92 631.344 337.92C607.792 337.92 586.629 331.947 567.856 320C549.083 307.712 534.235 290.987 523.312 269.824C512.389 248.661 506.928 224.597 506.928 197.632C506.928 169.984 512.389 145.749 523.312 124.928C534.235 103.765 548.912 87.2107 567.344 75.264C586.117 63.3174 606.939 57.344 629.808 57.344C644.485 57.344 657.968 59.7334 670.256 64.512C682.885 69.2907 693.808 75.4347 703.024 82.944C712.24 90.4534 719.237 98.4747 724.016 107.008C729.136 115.2 731.696 123.051 731.696 130.56L715.824 132.096V85.504C715.824 78.336 718.213 72.192 722.992 67.072C728.112 61.952 734.256 59.392 741.424 59.392ZM636.976 290.816C653.019 290.816 667.184 286.72 679.472 278.528C691.76 270.336 701.317 259.243 708.144 245.248C714.971 230.912 718.384 215.04 718.384 197.632C718.384 179.883 714.971 164.011 708.144 150.016C701.317 136.021 691.76 124.928 679.472 116.736C667.184 108.544 653.019 104.448 636.976 104.448C621.275 104.448 607.28 108.544 594.992 116.736C583.045 124.928 573.488 136.021 566.32 150.016C559.493 164.011 556.08 179.883 556.08 197.632C556.08 215.04 559.493 230.912 566.32 245.248C573.488 259.243 583.045 270.336 594.992 278.528C607.28 286.72 621.275 290.816 636.976 290.816ZM973.92 58.88C999.179 58.88 1018.63 65.1947 1032.29 77.824C1045.94 90.112 1054.99 106.837 1059.42 128L1051.23 125.44L1054.82 117.248C1059.25 108.373 1065.91 99.4987 1074.78 90.624C1084 81.408 1094.92 73.8987 1107.55 68.096C1120.18 61.952 1133.66 58.88 1148 58.88C1171.55 58.88 1189.81 64 1202.78 74.2401C1215.75 84.1387 1224.8 97.6214 1229.92 114.688C1235.04 131.413 1237.6 150.187 1237.6 171.008V306.688C1237.6 314.197 1235.21 320.512 1230.43 325.632C1225.65 330.411 1219.51 332.8 1212 332.8C1204.83 332.8 1198.69 330.411 1193.57 325.632C1188.79 320.512 1186.4 314.197 1186.4 306.688V171.52C1186.4 159.573 1184.69 148.651 1181.28 138.752C1178.21 128.853 1172.58 121.003 1164.38 115.2C1156.53 109.056 1145.61 105.984 1131.62 105.984C1118.3 105.984 1106.36 109.056 1095.78 115.2C1085.54 121.003 1077.51 128.853 1071.71 138.752C1065.91 148.651 1063.01 159.573 1063.01 171.52V306.688C1063.01 314.197 1060.62 320.512 1055.84 325.632C1051.06 330.411 1044.92 332.8 1037.41 332.8C1029.9 332.8 1023.75 330.411 1018.98 325.632C1014.2 320.512 1011.81 314.197 1011.81 306.688V171.008C1011.81 159.061 1010.1 148.139 1006.69 138.24C1003.62 128.341 998.155 120.491 990.304 114.688C982.453 108.885 971.531 105.984 957.536 105.984C944.224 105.984 932.448 108.885 922.208 114.688C911.968 120.491 903.947 128.341 898.144 138.24C892.683 148.139 889.952 159.061 889.952 171.008V306.688C889.952 314.197 887.392 320.512 882.272 325.632C877.493 330.411 871.52 332.8 864.352 332.8C856.843 332.8 850.699 330.411 845.92 325.632C841.141 320.512 838.752 314.197 838.752 306.688V89.6C838.752 82.0907 841.141 75.9467 845.92 71.168C850.699 66.048 856.843 63.488 864.352 63.488C871.52 63.488 877.493 66.048 882.272 71.168C887.392 75.9467 889.952 82.0907 889.952 89.6V119.808L880.224 125.44C882.613 117.931 886.539 110.251 892 102.4C897.461 94.5494 904.288 87.3814 912.48 80.896C921.013 74.4107 930.4 69.12 940.64 65.024C950.88 60.928 961.973 58.88 973.92 58.88ZM1509.96 63.488C1517.12 63.488 1523.1 66.048 1527.88 71.168C1533 75.9467 1535.56 82.0907 1535.56 89.6V224.256C1535.56 259.755 1525.66 287.573 1505.86 307.712C1486.06 327.509 1458.07 337.408 1421.89 337.408C1386.05 337.408 1358.23 327.509 1338.44 307.712C1318.98 287.573 1309.25 259.755 1309.25 224.256V89.6C1309.25 82.0907 1311.64 75.9467 1316.42 71.168C1321.2 66.048 1327.34 63.488 1334.85 63.488C1342.02 63.488 1347.99 66.048 1352.77 71.168C1357.89 75.9467 1360.45 82.0907 1360.45 89.6V224.256C1360.45 246.443 1365.57 262.997 1375.81 273.92C1386.39 284.843 1401.75 290.304 1421.89 290.304C1442.37 290.304 1457.9 284.843 1468.48 273.92C1479.07 262.997 1484.36 246.443 1484.36 224.256V89.6C1484.36 82.0907 1486.75 75.9467 1491.52 71.168C1496.3 66.048 1502.45 63.488 1509.96 63.488ZM1590.88 290.816C1587.81 286.037 1586.44 280.576 1586.78 274.432C1587.13 267.947 1590.71 262.485 1597.54 258.048C1602.31 254.976 1607.26 253.781 1612.38 254.464C1617.85 254.805 1622.97 257.365 1627.74 262.144C1637.3 272.725 1647.88 281.088 1659.49 287.232C1671.43 293.376 1685.77 296.448 1702.5 296.448C1709.32 296.107 1716.32 295.083 1723.49 293.376C1730.66 291.328 1736.8 287.915 1741.92 283.136C1747.04 278.016 1749.6 270.677 1749.6 261.12C1749.6 252.587 1746.87 245.76 1741.41 240.64C1735.95 235.52 1728.78 231.424 1719.9 228.352C1711.03 224.939 1701.47 221.867 1691.23 219.136C1680.31 216.064 1669.22 212.651 1657.95 208.896C1647.03 205.141 1637.13 200.363 1628.26 194.56C1619.38 188.416 1612.04 180.565 1606.24 171.008C1600.78 161.451 1598.05 149.675 1598.05 135.68C1598.05 119.637 1602.49 105.813 1611.36 94.208C1620.58 82.6027 1632.35 73.5574 1646.69 67.072C1661.37 60.5867 1677.24 57.344 1694.3 57.344C1704.2 57.344 1714.61 58.7094 1725.54 61.44C1736.46 63.8294 1747.04 67.7547 1757.28 73.216C1767.52 78.6774 1776.39 85.8454 1783.9 94.72C1787.32 99.1574 1789.19 104.448 1789.54 110.592C1790.22 116.736 1787.49 122.197 1781.34 126.976C1777.25 130.389 1772.3 131.925 1766.5 131.584C1760.69 131.243 1755.91 129.365 1752.16 125.952C1745.67 117.76 1737.31 111.445 1727.07 107.008C1716.83 102.229 1705.23 99.84 1692.26 99.84C1685.77 99.84 1678.94 100.864 1671.78 102.912C1664.95 104.619 1659.15 107.861 1654.37 112.64C1649.59 117.419 1647.2 124.416 1647.2 133.632C1647.2 142.165 1649.93 148.992 1655.39 154.112C1661.19 159.232 1668.7 163.499 1677.92 166.912C1687.14 170.325 1697.21 173.397 1708.13 176.128C1718.71 178.859 1729.12 182.101 1739.36 185.856C1749.94 189.611 1759.33 194.56 1767.52 200.704C1776.05 206.507 1782.88 214.187 1788 223.744C1793.46 232.96 1796.19 244.736 1796.19 259.072C1796.19 275.797 1791.24 290.133 1781.34 302.08C1771.79 313.685 1759.5 322.56 1744.48 328.704C1729.8 334.848 1714.27 337.92 1697.89 337.92C1678.43 337.92 1658.98 334.507 1639.52 327.68C1620.41 320.512 1604.19 308.224 1590.88 290.816ZM1976.69 337.92C1948.36 337.92 1923.79 332.117 1902.96 320.512C1882.14 308.565 1865.93 292.352 1854.32 271.872C1843.06 251.051 1837.43 227.328 1837.43 200.704C1837.43 170.667 1843.4 145.067 1855.35 123.904C1867.64 102.4 1883.51 86.016 1902.96 74.752C1922.42 63.1467 1943.07 57.344 1964.92 57.344C1981.64 57.344 1997.51 60.7574 2012.53 67.584C2027.55 74.0694 2040.86 83.2854 2052.47 95.232C2064.41 106.837 2073.8 120.661 2080.63 136.704C2087.45 152.405 2091.04 169.643 2091.38 188.416C2091.04 195.243 2088.31 200.875 2083.19 205.312C2078.07 209.749 2072.09 211.968 2065.27 211.968H1866.1L1853.81 168.448H2046.32L2036.6 177.664V164.864C2035.91 152.917 2031.99 142.507 2024.82 133.632C2017.65 124.416 2008.78 117.419 1998.2 112.64C1987.61 107.52 1976.52 104.96 1964.92 104.96C1954.68 104.96 1944.78 106.667 1935.22 110.08C1926 113.152 1917.64 118.272 1910.13 125.44C1902.96 132.608 1897.16 142.165 1892.72 154.112C1888.63 165.717 1886.58 180.224 1886.58 197.632C1886.58 216.405 1890.51 232.789 1898.36 246.784C1906.21 260.779 1916.79 271.701 1930.1 279.552C1943.41 287.061 1958.09 290.816 1974.13 290.816C1986.76 290.816 1997.17 289.621 2005.36 287.232C2013.56 284.501 2020.38 281.259 2025.84 277.504C2031.31 273.749 2036.08 270.165 2040.18 266.752C2045.3 263.68 2050.42 262.144 2055.54 262.144C2061.68 262.144 2066.8 264.363 2070.9 268.8C2075 272.896 2077.04 277.845 2077.04 283.648C2077.04 291.157 2073.29 297.984 2065.78 304.128C2056.56 313.003 2043.76 320.853 2027.38 327.68C2011.34 334.507 1994.44 337.92 1976.69 337.92ZM2284.42 58.88C2309.68 58.88 2329.13 65.1947 2342.79 77.824C2356.44 90.112 2365.49 106.837 2369.92 128L2361.73 125.44L2365.32 117.248C2369.75 108.373 2376.41 99.4987 2385.28 90.624C2394.5 81.408 2405.42 73.8987 2418.05 68.096C2430.68 61.952 2444.16 58.88 2458.5 58.88C2482.05 58.88 2500.31 64 2513.28 74.2401C2526.25 84.1387 2535.3 97.6214 2540.42 114.688C2545.54 131.413 2548.1 150.187 2548.1 171.008V306.688C2548.1 314.197 2545.71 320.512 2540.93 325.632C2536.15 330.411 2530.01 332.8 2522.5 332.8C2515.33 332.8 2509.19 330.411 2504.07 325.632C2499.29 320.512 2496.9 314.197 2496.9 306.688V171.52C2496.9 159.573 2495.19 148.651 2491.78 138.752C2488.71 128.853 2483.08 121.003 2474.88 115.2C2467.03 109.056 2456.11 105.984 2442.12 105.984C2428.8 105.984 2416.86 109.056 2406.28 115.2C2396.04 121.003 2388.01 128.853 2382.21 138.752C2376.41 148.651 2373.51 159.573 2373.51 171.52V306.688C2373.51 314.197 2371.12 320.512 2366.34 325.632C2361.56 330.411 2355.42 332.8 2347.91 332.8C2340.4 332.8 2334.25 330.411 2329.48 325.632C2324.7 320.512 2322.31 314.197 2322.31 306.688V171.008C2322.31 159.061 2320.6 148.139 2317.19 138.24C2314.12 128.341 2308.65 120.491 2300.8 114.688C2292.95 108.885 2282.03 105.984 2268.04 105.984C2254.72 105.984 2242.95 108.885 2232.71 114.688C2222.47 120.491 2214.45 128.341 2208.64 138.24C2203.18 148.139 2200.45 159.061 2200.45 171.008V306.688C2200.45 314.197 2197.89 320.512 2192.77 325.632C2187.99 330.411 2182.02 332.8 2174.85 332.8C2167.34 332.8 2161.2 330.411 2156.42 325.632C2151.64 320.512 2149.25 314.197 2149.25 306.688V89.6C2149.25 82.0907 2151.64 75.9467 2156.42 71.168C2161.2 66.048 2167.34 63.488 2174.85 63.488C2182.02 63.488 2187.99 66.048 2192.77 71.168C2197.89 75.9467 2200.45 82.0907 2200.45 89.6V119.808L2190.72 125.44C2193.11 117.931 2197.04 110.251 2202.5 102.4C2207.96 94.5494 2214.79 87.3814 2222.98 80.896C2231.51 74.4107 2240.9 69.12 2251.14 65.024C2261.38 60.928 2272.47 58.88 2284.42 58.88ZM2745.19 337.92C2716.86 337.92 2692.29 332.117 2671.46 320.512C2650.64 308.565 2634.43 292.352 2622.82 271.872C2611.56 251.051 2605.93 227.328 2605.93 200.704C2605.93 170.667 2611.9 145.067 2623.85 123.904C2636.14 102.4 2652.01 86.016 2671.46 74.752C2690.92 63.1467 2711.57 57.344 2733.42 57.344C2750.14 57.344 2766.01 60.7574 2781.03 67.584C2796.05 74.0694 2809.36 83.2854 2820.97 95.232C2832.91 106.837 2842.3 120.661 2849.13 136.704C2855.95 152.405 2859.54 169.643 2859.88 188.416C2859.54 195.243 2856.81 200.875 2851.69 205.312C2846.57 209.749 2840.59 211.968 2833.77 211.968H2634.6L2622.31 168.448H2814.82L2805.1 177.664V164.864C2804.41 152.917 2800.49 142.507 2793.32 133.632C2786.15 124.416 2777.28 117.419 2766.7 112.64C2756.11 107.52 2745.02 104.96 2733.42 104.96C2723.18 104.96 2713.28 106.667 2703.72 110.08C2694.5 113.152 2686.14 118.272 2678.63 125.44C2671.46 132.608 2665.66 142.165 2661.22 154.112C2657.13 165.717 2655.08 180.224 2655.08 197.632C2655.08 216.405 2659.01 232.789 2666.86 246.784C2674.71 260.779 2685.29 271.701 2698.6 279.552C2711.91 287.061 2726.59 290.816 2742.63 290.816C2755.26 290.816 2765.67 289.621 2773.86 287.232C2782.06 284.501 2788.88 281.259 2794.34 277.504C2799.81 273.749 2804.58 270.165 2808.68 266.752C2813.8 263.68 2818.92 262.144 2824.04 262.144C2830.18 262.144 2835.3 264.363 2839.4 268.8C2843.5 272.896 2845.54 277.845 2845.54 283.648C2845.54 291.157 2841.79 297.984 2834.28 304.128C2825.06 313.003 2812.26 320.853 2795.88 327.68C2779.84 334.507 2762.94 337.92 2745.19 337.92ZM3056.5 57.344C3080.74 57.344 3099.51 62.464 3112.82 72.704C3126.14 82.6027 3135.35 96.0854 3140.47 113.152C3145.93 129.877 3148.66 148.651 3148.66 169.472V306.688C3148.66 314.197 3146.1 320.512 3140.98 325.632C3136.21 330.411 3130.23 332.8 3123.06 332.8C3115.55 332.8 3109.41 330.411 3104.63 325.632C3099.85 320.512 3097.46 314.197 3097.46 306.688V169.984C3097.46 157.696 3095.76 146.773 3092.34 137.216C3088.93 127.317 3082.96 119.467 3074.42 113.664C3066.23 107.52 3054.63 104.448 3039.61 104.448C3025.61 104.448 3013.33 107.52 3002.74 113.664C2992.16 119.467 2983.8 127.317 2977.66 137.216C2971.85 146.773 2968.95 157.696 2968.95 169.984V306.688C2968.95 314.197 2966.39 320.512 2961.27 325.632C2956.49 330.411 2950.52 332.8 2943.35 332.8C2935.84 332.8 2929.7 330.411 2924.92 325.632C2920.14 320.512 2917.75 314.197 2917.75 306.688V89.6C2917.75 82.0907 2920.14 75.9467 2924.92 71.168C2929.7 66.048 2935.84 63.488 2943.35 63.488C2950.52 63.488 2956.49 66.048 2961.27 71.168C2966.39 75.9467 2968.95 82.0907 2968.95 89.6V118.272L2959.74 123.392C2962.13 115.883 2966.22 108.373 2972.02 100.864C2978.17 93.0134 2985.51 85.8454 2994.04 79.36C3002.57 72.5334 3012.13 67.2427 3022.71 63.488C3033.29 59.392 3044.56 57.344 3056.5 57.344ZM3216.17 66.56H3336.49C3343.31 66.56 3348.95 68.9494 3353.38 73.728C3358.16 78.1654 3360.55 83.7974 3360.55 90.624C3360.55 97.1094 3358.16 102.571 3353.38 107.008C3348.95 111.445 3343.31 113.664 3336.49 113.664H3216.17C3209.34 113.664 3203.54 111.445 3198.76 107.008C3194.32 102.229 3192.1 96.5974 3192.1 90.112C3192.1 83.2854 3194.32 77.6534 3198.76 73.216C3203.54 68.7787 3209.34 66.56 3216.17 66.56ZM3269.93 3.05176e-05C3277.44 3.05176e-05 3283.58 2.56003 3288.36 7.68002C3293.14 12.4587 3295.53 18.6027 3295.53 26.112V263.68C3295.53 269.824 3296.55 274.773 3298.6 278.528C3300.65 282.283 3303.38 284.843 3306.79 286.208C3310.55 287.573 3314.3 288.256 3318.06 288.256C3321.81 288.256 3325.05 287.573 3327.78 286.208C3330.86 284.843 3334.44 284.16 3338.54 284.16C3342.63 284.16 3346.39 286.037 3349.8 289.792C3353.21 293.547 3354.92 298.667 3354.92 305.152C3354.92 313.344 3350.48 320 3341.61 325.12C3332.73 330.24 3323.18 332.8 3312.94 332.8C3307.13 332.8 3300.31 332.288 3292.46 331.264C3284.61 330.24 3276.93 327.68 3269.42 323.584C3262.25 319.488 3256.27 313.003 3251.5 304.128C3246.72 294.912 3244.33 282.112 3244.33 265.728V26.112C3244.33 18.6027 3246.72 12.4587 3251.5 7.68002C3256.62 2.56003 3262.76 3.05176e-05 3269.93 3.05176e-05Z" fill="white"/>
</svg>
    </div>

    <p class="hero-tagline">
      The social space that puts <em>you</em> first.
    </p>
    <p class="hero-sub">
      Post photos, share moments, and find your people — without the algorithm deciding who sees you.
    </p>

    <div class="hero-actions">
      <?php if ($user): ?>
      <a href="/home" class="btn btn-primary">Open your feed</a>
      <a href="/profile.php?u=<?= e($user['username']) ?>" class="btn btn-ghost">Your profile</a>
      <?php else: ?>
      <a href="/register.php" class="btn btn-primary">Join for free</a>
      <a href="/login.php" class="btn btn-ghost">Log in</a>
      <?php endif; ?>
      <button type="button" class="theme-toggle" aria-label="Toggle theme" title="Toggle light/dark mode">
        <span class="theme-icon theme-icon-light" aria-hidden="true">☀</span>
        <span class="theme-icon theme-icon-dark" aria-hidden="true">☾</span>
      </button>
    </div>

    <!-- UK Banner — shown below hero CTAs -->
    <div style="margin-top:2.5rem;">
      <div class="uk-banner">
        <span class="uk-banner-label">🇬🇧 UK social media ban</span>
        <h2>Under-16 ban incoming? You still have a place here.</h2>
        <p>When platforms start locking out under-16s, a-amusement stays open — a welcoming community built on safety, not surveillance. No dark patterns. No ad-driven feeds. Just people and their posts.</p>
      </div>
    </div>
  </div>
</header>

<!-- FEATURES -->
<section>
  <p class="section-label">What you get</p>
  <h2 class="section-title">Everything you love about social media,<br>without the stuff you hate</h2>
  <p class="section-desc">a-amusement is built around the things that make online communities great — and nothing else.</p>

  <div class="feature-grid">
    <div class="feature-card">
      <div class="feature-icon">📷</div>
      <h3>Photos &amp; posts</h3>
      <p>Share images or text, with full commenting, liking, and reposting. Your feed, your way — no opaque ranking system pushing content you didn't ask for.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🌗</div>
      <h3>Light &amp; dark mode</h3>
      <p>Toggle between light and dark at any time — on desktop, mobile, and in every third-party client that connects to a-amusement.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🔁</div>
      <h3>Reposts &amp; reactions</h3>
      <p>Amplify the posts that matter to you and react with more than just a thumbs-up. Community interaction is at the core of what a-amusement does.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🛡️</div>
      <h3>Safety-first design</h3>
      <p>Moderation tools built in from day one — not bolted on after the fact. Report, block, and curate your space without jumping through hoops.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🔓</div>
      <h3>Open ecosystem</h3>
      <p>Post and browse from fanmade clients, browser extensions, and third-party apps. a-amusement doesn't try to trap you in one interface.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🎮</div>
      <h3>ReChat support</h3>
      <p>Part of the Lerainzer ReChat community (Rec Room Revival Project)? a-amusement connects seamlessly — bringing your virtual world social life to the web.</p>
    </div>
  </div>
</section>

<!-- INTEGRATIONS -->
<section class="integrations-section">
  <p class="section-label">Works with</p>
  <h2 class="section-title">Post from anywhere</h2>
  <p class="section-desc">a-amusement connects to the tools and communities you already use, with more on the way.</p>

  <div class="integrations-row">
    <div class="integration-pill">
      <span class="integration-dot dot-live"></span>
      Nerimity
      <span class="integration-status status-live">Live</span>
    </div>
    <div class="integration-pill">
      <span class="integration-dot dot-live"></span>
      Discord
      <span class="integration-status status-live">Live</span>
    </div>
    <div class="integration-pill">
      <span class="integration-dot dot-live"></span>
      ReChat (Lerainzer)
      <span class="integration-status status-live">Live</span>
    </div>
    <div class="integration-pill">
      <span class="integration-dot dot-soon"></span>
      Fanmade clients
      <span class="integration-status status-soon">Coming soon</span>
    </div>
    <div class="integration-pill">
      <span class="integration-dot dot-soon"></span>
      More platforms
      <span class="integration-status status-soon">Coming soon</span>
    </div>
  </div>
</section>

<!-- CTA -->
<section>
  <div class="cta-section">
    <h2>Ready to find your people?</h2>
    <p>Join a-amusement — it's free, open, and built for communities that care.</p>
    <div class="cta-actions">
      <?php if ($user): ?>
      <a href="/home" class="btn btn-primary">Go to your feed</a>
      <?php else: ?>
      <a href="/register.php" class="btn btn-primary">Create your account</a>
      <a href="/login.php" class="btn btn-ghost">Already have one? Log in</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<footer>
  <p>© <?= date('Y') ?> The OneLyte Association &nbsp;·&nbsp; <a href="/about.php">About</a> &nbsp;·&nbsp; <a href="/terms.php">Terms</a> &nbsp;·&nbsp; <a href="/privacy.php">Privacy</a></p>
</footer>

<script>
(function() {
  // Theme persistence
  var saved = localStorage.getItem('aa-theme');
  if (saved === 'light') document.body.classList.add('light');

  document.querySelector('.theme-toggle').addEventListener('click', function() {
    document.body.classList.toggle('light');
    localStorage.setItem('aa-theme', document.body.classList.contains('light') ? 'light' : 'dark');
  });
})();
</script>

</body>
</html>
<?php page_end(); ?>
