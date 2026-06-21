<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = current_user();
page_start('About — a-amusement', 'about', $user);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About — a-amusement & The OneLyte Association</title>
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

  /* ── NAV ── */
  .topnav {
    position: sticky;
    top: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 2rem;
    background: rgba(13,15,26,.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
  }

  .topnav-brand {
    font-family: var(--font-head);
    font-weight: 700;
    font-size: 1rem;
    color: #fff;
    text-decoration: none;
    letter-spacing: -.01em;
    display: flex;
    align-items: center;
    gap: .5rem;
  }
  .topnav-brand span { color: var(--mint); }

  .topnav-right {
    display: flex;
    align-items: center;
    gap: .75rem;
  }

  .nav-link {
    font-family: var(--font-head);
    font-size: .875rem;
    font-weight: 500;
    color: var(--muted);
    text-decoration: none;
    padding: .4rem .75rem;
    border-radius: 8px;
    transition: color .15s, background .15s;
  }
  .nav-link:hover, .nav-link.active { color: #fff; background: rgba(123,94,167,.15); }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .55rem 1.25rem;
    border-radius: 999px;
    font-family: var(--font-head);
    font-weight: 600;
    font-size: .875rem;
    text-decoration: none;
    transition: transform .15s, box-shadow .15s, background .15s;
    cursor: pointer;
    border: none;
  }
  .btn:hover { transform: translateY(-1px); }
  .btn-primary {
    background: var(--mint);
    color: #0D0F1A;
    box-shadow: 0 0 20px rgba(79,255,176,.3);
  }
  .btn-primary:hover { background: #6FFFBF; box-shadow: 0 0 30px rgba(79,255,176,.45); }
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
    padding: .45rem .85rem;
    font-size: .9rem;
    cursor: pointer;
    font-family: var(--font-body);
    transition: border-color .15s;
  }
  .theme-toggle:hover { border-color: var(--violet-lt); }

  /* ── PAGE HERO ── */
  .page-hero {
    position: relative;
    padding: 7rem 1.5rem 5rem;
    text-align: center;
    overflow: hidden;
  }

  .page-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 70% 80% at 50% 0%, rgba(123,94,167,.28) 0%, transparent 65%),
      radial-gradient(ellipse 40% 40% at 80% 100%, rgba(79,255,176,.08) 0%, transparent 60%);
    pointer-events: none;
    animation: auroraPulse 9s ease-in-out infinite alternate;
  }

  @keyframes auroraPulse {
    from { opacity: .7; }
    to   { opacity: 1; }
  }

  .page-hero-inner {
    position: relative;
    z-index: 1;
    max-width: 720px;
    margin: 0 auto;
  }

  .eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(123,94,167,.15);
    border: 1px solid rgba(123,94,167,.3);
    border-radius: 999px;
    padding: .35rem 1rem;
    font-family: var(--font-head);
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--violet-lt);
    margin-bottom: 1.5rem;
  }

  .page-hero h1 {
    font-family: var(--font-head);
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 700;
    color: #fff;
    letter-spacing: -.03em;
    line-height: 1.1;
    margin-bottom: 1.25rem;
  }

  .page-hero h1 em {
    font-style: normal;
    color: var(--mint);
  }

  .page-hero p {
    color: var(--muted);
    font-size: clamp(.95rem, 1.8vw, 1.1rem);
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.7;
  }

  /* ── DIVIDER ── */
  .divider {
    height: 1px;
    background: var(--border);
    max-width: 1100px;
    margin: 0 auto;
  }

  /* ── SHARED SECTION ── */
  .container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 5rem 1.5rem;
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
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    color: #fff;
    letter-spacing: -.02em;
    margin-bottom: 1rem;
    line-height: 1.2;
  }

  .section-body {
    color: var(--muted);
    font-size: 1rem;
    line-height: 1.75;
    max-width: 640px;
  }

  .section-body + .section-body {
    margin-top: .85rem;
  }

  /* ── MISSION SPLIT ── */
  .mission-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: start;
  }

  .mission-values {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-top: .5rem;
  }

  .value-row {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
  }

  .value-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: linear-gradient(135deg, rgba(123,94,167,.25), rgba(79,255,176,.08));
    border: 1px solid rgba(123,94,167,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    margin-top: .1rem;
  }

  .value-text h4 {
    font-family: var(--font-head);
    font-size: .95rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: .2rem;
  }

  .value-text p {
    color: var(--muted);
    font-size: .875rem;
    line-height: 1.55;
  }

  /* ── ORG CARDS ── */
  .org-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.25rem;
    margin-top: 3rem;
  }

  .org-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    transition: border-color .2s, transform .2s;
    position: relative;
    overflow: hidden;
  }
  .org-card:hover {
    transform: translateY(-3px);
  }

  /* Colour accent strip per card */
  .org-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 20px 20px 0 0;
  }

  .org-card.card-aa::before    { background: linear-gradient(90deg, var(--violet), var(--mint)); }
  .org-card.card-aa:hover      { border-color: var(--violet); }

  .org-card.card-nr::before    { background: linear-gradient(90deg, #E05EBF, #F5A623); }
  .org-card.card-nr:hover      { border-color: #E05EBF; }

  .org-card.card-tel::before   { background: linear-gradient(90deg, #3AB7F5, #4FFFB0); }
  .org-card.card-tel:hover     { border-color: #3AB7F5; }

  .org-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    background: rgba(255,255,255,.04);
    border: 1px solid var(--border);
  }

  .org-card-meta {
    flex: 1;
  }

  .org-card-label {
    font-family: var(--font-head);
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: .3rem;
  }

  .org-card h3 {
    font-family: var(--font-head);
    font-size: 1.15rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: .5rem;
    letter-spacing: -.01em;
  }

  .org-card p {
    color: var(--muted);
    font-size: .875rem;
    line-height: 1.6;
  }

  .org-card-tag {
    display: inline-block;
    font-family: var(--font-head);
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .2rem .65rem;
    border-radius: 999px;
    background: rgba(123,94,167,.15);
    color: var(--violet-lt);
    border: 1px solid rgba(123,94,167,.25);
    align-self: flex-start;
  }

  .org-card.card-nr .org-card-tag  { background: rgba(224,94,191,.12); color: #E8A0D8; border-color: rgba(224,94,191,.25); }
  .org-card.card-tel .org-card-tag { background: rgba(58,183,245,.12); color: #96D9F7; border-color: rgba(58,183,245,.25); }

  /* ── ASSOCIATION HERO BLOCK ── */
  .assoc-hero {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 3.5rem;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2.5rem;
    align-items: center;
    position: relative;
    overflow: hidden;
  }

  .assoc-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 60% 80% at 100% 50%, rgba(123,94,167,.12) 0%, transparent 65%);
    pointer-events: none;
  }

  .assoc-hero-text { position: relative; }

  .assoc-hero-text .eyebrow { margin-bottom: 1rem; }

  .assoc-hero-text h2 {
    font-family: var(--font-head);
    font-size: clamp(1.4rem, 2.8vw, 1.9rem);
    font-weight: 700;
    color: #fff;
    letter-spacing: -.02em;
    margin-bottom: .85rem;
    line-height: 1.2;
  }

  .assoc-hero-text p {
    color: var(--muted);
    font-size: .95rem;
    line-height: 1.7;
    max-width: 520px;
  }

  .assoc-hero-text p + p { margin-top: .75rem; }

  .assoc-monogram {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 24px;
    background: linear-gradient(135deg, rgba(123,94,167,.3) 0%, rgba(79,255,176,.1) 100%);
    border: 1px solid rgba(123,94,167,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-family: var(--font-head);
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--violet-lt);
    letter-spacing: -.04em;
  }

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
    background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(123,94,167,.18) 0%, transparent 70%);
    pointer-events: none;
  }

  .cta-section h2 {
    font-family: var(--font-head);
    font-size: clamp(1.4rem, 3vw, 1.9rem);
    font-weight: 700;
    color: #fff;
    margin-bottom: .7rem;
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

  /* ── THEME ── */
  .theme-icon { display: none; }
  body:not(.light) .theme-icon-dark  { display: inline; }
  body.light      .theme-icon-light  { display: inline; }

  body.light {
    --bg:      #F5F5FA;
    --surface: #FFFFFF;
    --border:  #D8D8EE;
    --text:    #1A1B2E;
    --muted:   #5C5F7A;
  }
  body.light .topnav { background: rgba(245,245,250,.9); }
  body.light .assoc-monogram { color: var(--violet); }
  body.light .page-hero::before { opacity: .6; }

  @media (prefers-reduced-motion: reduce) {
    .page-hero::before { animation: none; }
    .org-card, .btn { transition: none; }
  }

  @media (max-width: 820px) {
    .mission-layout  { grid-template-columns: 1fr; gap: 2.5rem; }
    .assoc-hero      { grid-template-columns: 1fr; }
    .assoc-monogram  { width: 72px; height: 72px; font-size: 1.5rem; border-radius: 16px; }
  }

  @media (max-width: 600px) {
    .topnav  { padding: .85rem 1rem; }
    .container { padding: 3.5rem 1rem; }
    .assoc-hero { padding: 2rem; }
    .page-hero  { padding: 5rem 1rem 3.5rem; }
  }
</style>
</head>
<body>

<!-- NAV -->
<nav class="topnav" aria-label="Site navigation">
  <a href="/" class="topnav-brand">a&#x2011;amusement <span>·</span> About</a>
  <div class="topnav-right">
    <a href="/" class="nav-link">Home</a>
    <a href="/about" class="nav-link active">About</a>
    <?php if ($user): ?>
    <a href="/home" class="btn btn-primary">Feed</a>
    <?php else: ?>
    <a href="/register.php" class="btn btn-primary">Join</a>
    <a href="/login.php" class="btn btn-ghost">Log in</a>
    <?php endif; ?>
    <button type="button" class="theme-toggle" aria-label="Toggle theme">
      <span class="theme-icon theme-icon-light" aria-hidden="true">☀</span>
      <span class="theme-icon theme-icon-dark" aria-hidden="true">☾</span>
    </button>
  </div>
</nav>

<!-- PAGE HERO -->
<header class="page-hero">
  <div class="page-hero-inner">
    <div class="eyebrow">About us</div>
    <h1>Built by people who care about <em>the open web</em></h1>
    <p>a-amusement is one part of something bigger — The OneLyte Association, a group dedicated to digital rights, creative media, and a healthier internet for everyone.</p>
  </div>
</header>

<div class="divider"></div>

<!-- ABOUT a-amusement -->
<div class="container">
  <div class="mission-layout">
    <div>
      <p class="section-label">What is a-amusement</p>
      <h2 class="section-title">A social space that belongs to its community</h2>
      <p class="section-body">
        a-amusement is a photo and text social platform designed around what people actually want from social media: real connections, genuine discovery, and the freedom to post without worrying about hidden algorithms or hostile monetisation schemes.
      </p>
      <p class="section-body">
        You can post, comment, like, and repost across web, mobile, and through third-party clients — including Nerimity, Discord, ReChat (part of the Lerainzer Rec Room Revival Project), and more on the way. Light mode or dark mode, always your choice.
      </p>
      <p class="section-body">
        We think the internet is better when communities set the rules, not advertisers. That's why a-amusement has no ads, no data selling, and no engagement traps.
      </p>
    </div>
    <div class="mission-values">
      <div class="value-row">
        <div class="value-icon">🌐</div>
        <div class="value-text">
          <h4>Open by design</h4>
          <p>Fanmade clients and third-party integrations are a feature, not a threat. We build with interoperability in mind.</p>
        </div>
      </div>
      <div class="value-row">
        <div class="value-icon">🔒</div>
        <div class="value-text">
          <h4>Private by default</h4>
          <p>Your data is yours. We don't sell it, profile you, or monetise your attention.</p>
        </div>
      </div>
      <div class="value-row">
        <div class="value-icon">⚖️</div>
        <div class="value-text">
          <h4>Safe for everyone</h4>
          <p>Built-in moderation tools and thoughtful defaults — especially important as the UK's under-16 social media ban comes into effect.</p>
        </div>
      </div>
      <div class="value-row">
        <div class="value-icon">🎮</div>
        <div class="value-text">
          <h4>Community-rooted</h4>
          <p>From ReChat to Discord, a-amusement is part of the communities that use it — not above them.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="divider"></div>

<!-- THE ONELYTE ASSOCIATION -->
<div class="container">
  <div class="assoc-hero">
    <div class="assoc-hero-text">
      <div class="eyebrow">Parent organisation</div>
      <h2>The OneLyte Association</h2>
      <p>
        The OneLyte Association is a digital media and rights advocacy group that builds and supports projects at the intersection of community, creativity, and a fair internet. a-amusement is one of its flagship projects.
      </p>
      <p>
        The Association's work spans social platforms, music distribution, and transparency advocacy — united by the belief that digital spaces should serve the people who use them, not the other way around.
      </p>
    </div>
    <div class="assoc-monogram" aria-hidden="true">OL</div>
  </div>

  <p class="section-label" style="margin-top:3.5rem;">Projects under The OneLyte Association</p>
  <h2 class="section-title">One organisation, three projects</h2>

  <div class="org-grid">

    <!-- a-amusement -->
    <div class="org-card card-aa">
      <div class="org-card-icon">💬</div>
      <div class="org-card-meta">
        <p class="org-card-label">Social platform</p>
        <h3>a-amusement</h3>
        <p>A photo and text social space built for real communities. Post, comment, repost, and connect — with open integrations, no ads, and a strong focus on safety and accessibility.</p>
      </div>
      <span class="org-card-tag">You're here</span>
    </div>

    <!-- Nightedge Recordings -->
    <div class="org-card card-nr">
      <div class="org-card-icon">🎵</div>
      <div class="org-card-meta">
        <p class="org-card-label">Music distribution</p>
        <h3>Nightedge Recordings</h3>
        <p>A music distribution label under The OneLyte Association, helping artists get their work onto streaming platforms and into listeners' hands — with fair terms and artist-first values.</p>
      </div>
      <span class="org-card-tag">Music</span>
    </div>

    <!-- Team ElectroLiner -->
    <div class="org-card card-tel">
      <div class="org-card-icon">🔍</div>
      <div class="org-card-meta">
        <p class="org-card-label">Transparency group</p>
        <h3>Team ElectroLiner</h3>
        <p>The Association's transparency and accountability arm — investigating, documenting, and publishing findings to keep digital platforms and online organisations honest and accountable to their users.</p>
      </div>
      <span class="org-card-tag">Transparency</span>
    </div>

  </div>
</div>

<div class="divider"></div>

<!-- CTA -->
<div class="container">
  <div class="cta-section">
    <h2>Come be part of it</h2>
    <p>a-amusement is free, open, and backed by people who believe in a better internet.</p>
    <div class="cta-actions">
      <?php if ($user): ?>
      <a href="/home" class="btn btn-primary">Back to your feed</a>
      <?php else: ?>
      <a href="/register.php" class="btn btn-primary">Create an account</a>
      <a href="/login.php" class="btn btn-ghost">Log in</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<footer>
  <p>© <?= date('Y') ?> a-amusement &nbsp;·&nbsp; Part of <strong>The OneLyte Association</strong> &nbsp;·&nbsp; <a href="/">Home</a> &nbsp;·&nbsp; <a href="/about">About</a> &nbsp;·&nbsp; <a href="/terms">Terms</a> &nbsp;·&nbsp; <a href="/privacy">Privacy</a></p>
</footer>

<script>
(function() {
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
