<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-avatar-console" aria-labelledby="ss-avatar-title">
  <style>
    .ss-avatar-console{padding:24px 0 10px;color:var(--ss-ink,#e9eef5)}
    .ss-avatar-head{display:flex;justify-content:space-between;gap:18px;align-items:flex-end;margin-bottom:22px;padding:20px 22px;border:1px solid var(--ss-line,#263140);background:linear-gradient(180deg,rgba(20,28,39,.94),rgba(8,12,18,.98));box-shadow:0 18px 44px rgba(0,0,0,.28)}
    .ss-avatar-head h1{margin:4px 0 5px;font-size:clamp(1.55rem,3vw,2.25rem);letter-spacing:.03em}
    .ss-avatar-head p{margin:0;color:var(--ss-muted,#8e9bad);max-width:650px;line-height:1.6}
    .ss-avatar-count{min-width:110px;padding:11px 13px;border:1px solid #344152;background:#090e15;text-align:right}
    .ss-avatar-count strong{display:block;color:var(--ss-amber-hot,#e5c982);font-size:1.25rem}
    .ss-avatar-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
    .ss-avatar-card{position:relative;display:flex;flex-direction:column;min-width:0;border:1px solid var(--ss-line,#263140);background:linear-gradient(180deg,#111925,#080c12);overflow:hidden;box-shadow:0 16px 34px rgba(0,0,0,.24)}
    .ss-avatar-card:before{content:"";position:absolute;top:-1px;left:14px;width:38px;height:1px;background:var(--ss-amber,#c7a45d);z-index:2}
    .ss-avatar-card a{display:block;color:inherit;text-decoration:none}
    .ss-avatar-card img{display:block;width:100%;height:210px;object-fit:cover;object-position:center top;background:#06090d;filter:saturate(.82) contrast(1.04);transition:transform .18s ease,filter .18s ease}
    .ss-avatar-card:hover img{transform:scale(1.025);filter:saturate(1) contrast(1.06)}
    .ss-avatar-meta{padding:13px 14px 15px;border-top:1px solid rgba(255,255,255,.05)}
    .ss-avatar-code{display:inline-block;margin-bottom:6px;color:var(--ss-amber,#c7a45d);font-size:.65rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase}
    .ss-avatar-name{display:block;font-size:1rem;font-weight:800;letter-spacing:.02em}
    .ss-avatar-note{display:block;margin-top:5px;color:var(--ss-muted,#8e9bad);font-size:.72rem;line-height:1.45}
    .ss-avatar-card--elite{border-color:#5b4c2f}
    .ss-avatar-card--elite .ss-avatar-code{color:var(--ss-amber-hot,#e5c982)}
    @media (max-width:900px){.ss-avatar-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.ss-avatar-card img{height:190px}}
    @media (max-width:600px){.ss-avatar-head{align-items:flex-start;flex-direction:column}.ss-avatar-grid{grid-template-columns:1fr}.ss-avatar-card img{height:240px}}
  </style>

  <header class="ss-avatar-head">
    <div>
      <span class="ss-eyebrow">OPERATIVE ARCHIVE // IDENTITY</span>
      <h1 id="ss-avatar-title">Select your field appearance</h1>
      <p>Choose the silhouette that represents your operative in the Shadow network. Elite frames are marked and remain mechanically unchanged.</p>
    </div>
    <div class="ss-avatar-count" aria-label="Available avatar count">
      <span class="ss-avatar-code">Available</span>
      <strong>15</strong>
      <span class="ss-avatar-note">identity frames</span>
    </div>
  </header>

  <div class="ss-avatar-grid" role="list">
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=1"><img src="layoutnovo/avatares/1.jpg" title="Ashfall" alt="Ashfall operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 01</span><span class="ss-avatar-name">Ashfall</span><span class="ss-avatar-note">Scorched field profile</span></div></a></article>
    <article class="ss-avatar-card ss-avatar-card--elite" role="listitem"><a href="outros.php?action=2"><img src="layoutnovo/avatares/2p.jpg" title="Warden - Elite Avatar" alt="Warden elite operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 02 // ELITE</span><span class="ss-avatar-name">Warden</span><span class="ss-avatar-note">Elite identity frame</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=3"><img src="layoutnovo/avatares/3.jpg" title="Graves" alt="Graves operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 03</span><span class="ss-avatar-name">Graves</span><span class="ss-avatar-note">Grave-line profile</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=4"><img src="layoutnovo/avatares/4.jpg" title="Veil" alt="Veil operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 04</span><span class="ss-avatar-name">Veil</span><span class="ss-avatar-note">Low-signature profile</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=5"><img src="layoutnovo/avatares/5.jpg" title="Vesper" alt="Vesper operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 05</span><span class="ss-avatar-name">Vesper</span><span class="ss-avatar-note">Dusk-line profile</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=6"><img src="layoutnovo/avatares/6.jpg" title="Riftborn" alt="Riftborn operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 06</span><span class="ss-avatar-name">Riftborn</span><span class="ss-avatar-note">Rift-adapted profile</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=7"><img src="layoutnovo/avatares/7.jpg" title="Hollow Crown" alt="Hollow Crown operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 07</span><span class="ss-avatar-name">Hollow Crown</span><span class="ss-avatar-note">Crownless profile</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=8"><img src="layoutnovo/avatares/8.jpg" title="Cipher" alt="Cipher operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 08</span><span class="ss-avatar-name">Cipher</span><span class="ss-avatar-note">Encrypted profile</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=9"><img src="layoutnovo/avatares/9.jpg" title="Sable" alt="Sable operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 09</span><span class="ss-avatar-name">Sable</span><span class="ss-avatar-note">Black-ops profile</span></div></a></article>
    <article class="ss-avatar-card ss-avatar-card--elite" role="listitem"><a href="outros.php?action=10"><img src="layoutnovo/avatares/10p.jpg" title="Night Warden - Elite Avatar" alt="Night Warden elite operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 10 // ELITE</span><span class="ss-avatar-name">Night Warden</span><span class="ss-avatar-note">Elite identity frame</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=11"><img src="layoutnovo/avatares/11.jpg" title="Ash Viper" alt="Ash Viper operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 11</span><span class="ss-avatar-name">Ash Viper</span><span class="ss-avatar-note">Venom-line profile</span></div></a></article>
    <article class="ss-avatar-card" role="listitem"><a href="outros.php?action=12"><img src="layoutnovo/avatares/12.jpg" title="Deep Current" alt="Deep Current operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 12</span><span class="ss-avatar-name">Deep Current</span><span class="ss-avatar-note">Flow-state profile</span></div></a></article>
    <article class="ss-avatar-card ss-avatar-card--elite" role="listitem"><a href="outros.php?action=13"><img src="layoutnovo/avatares/13p.jpg" title="Black Halo - Elite Avatar" alt="Black Halo elite operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 13 // ELITE</span><span class="ss-avatar-name">Black Halo</span><span class="ss-avatar-note">Elite identity frame</span></div></a></article>
    <article class="ss-avatar-card ss-avatar-card--elite" role="listitem"><a href="outros.php?action=14"><img src="layoutnovo/avatares/14p.jpg" title="Veiled Oracle - Elite Avatar" alt="Veiled Oracle elite operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 14 // ELITE</span><span class="ss-avatar-name">Veiled Oracle</span><span class="ss-avatar-note">Elite identity frame</span></div></a></article>
    <article class="ss-avatar-card ss-avatar-card--elite" role="listitem"><a href="outros.php?action=15"><img src="layoutnovo/avatares/15p.jpg" title="Gravewalker - Elite Avatar" alt="Gravewalker elite operative frame"><div class="ss-avatar-meta"><span class="ss-avatar-code">FRAME 15 // ELITE</span><span class="ss-avatar-name">Gravewalker</span><span class="ss-avatar-note">Elite identity frame</span></div></a></article>
  </div>
</section>
THEVERYENDOFYOU;
?>
