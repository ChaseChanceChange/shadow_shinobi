<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-town" aria-labelledby="town-title">
  <header class="ss-town__hero">
    <div class="ss-town__sigil" aria-hidden="true"><span>SS</span></div>
    <div class="ss-town__heading">
      <span class="ss-eyebrow">SAFE ZONE // SECTOR</span>
      <h2 id="town-title">{{name}}</h2>
      <p>Recover, resupply, manage your operative and prepare for the next Contract.</p>
    </div>
  </header>
  {{indexconteudo}}
  {{htmlnapag}}
  <div class="ss-town__content">{{fimconteudo}}</div>
  <div class="ss-town__community">
    <section class="ss-community-card"><span class="ss-eyebrow">WORLD STATUS</span>{{news}}</section>
    <section class="ss-community-card"><span class="ss-eyebrow">OPERATIVE NETWORK</span>{{whosonline}}</section>
    <section class="ss-community-card ss-community-card--chat"><span class="ss-eyebrow">COMMS CHANNEL</span>{{babblebox}}</section>
  </div>
</section>
THEVERYENDOFYOU;
?>
