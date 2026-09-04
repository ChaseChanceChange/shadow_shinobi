<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-town" aria-labelledby="town-title">
  <header class="ss-town__hero">
    <div class="ss-town__art"><img src="images/town_{{id}}.gif" alt="Welcome to {{name}}" title="Welcome to {{name}}"></div>
    <div class="ss-town__heading">
      <span class="ss-eyebrow">SAFE ZONE</span>
      <h2 id="town-title">{{name}}</h2>
      <p>Rest, resupply, manage your character and interact with other players.</p>
    </div>
  </header>
  {{indexconteudo}}
  {{htmlnapag}}
  <div class="ss-town__content">{{fimconteudo}}</div>
  <div class="ss-town__community">
    <section class="ss-community-card"><span class="ss-eyebrow">WORLD STATUS</span>{{news}}</section>
    <section class="ss-community-card"><span class="ss-eyebrow">PLAYERS</span>{{whosonline}}</section>
    <section class="ss-community-card ss-community-card--chat"><span class="ss-eyebrow">COMMUNICATION</span>{{babblebox}}</section>
  </div>
</section>
THEVERYENDOFYOU;
?>
