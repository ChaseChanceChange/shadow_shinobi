<?php
include('minimap.php');
include('historico.php');
$monstro = explode(",",$userrow["ultmonstro"]);
$template = <<<THEVERYENDOFYOU
<aside class="ss-sidebar ss-navigation-sidebar" aria-label="World navigation">
  <section class="ss-side-card ss-nav-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">NAVIGATION</span><h2>Sector Position</h2></div>
    <div class="ss-minimap">$minimap</div>
    <a class="ss-side-link ss-side-link--primary" href="javascript:openmappopup()">Open world map</a>
    <form action="index.php?do=move" method="post" class="ss-movement-grid" aria-label="Move operative">
      <button name="north" type="submit" aria-label="Move north">North</button>
      <button name="west" type="submit" aria-label="Move west">West</button>
      <button name="east" type="submit" aria-label="Move east">East</button>
      <button name="south" type="submit" aria-label="Move south">South</button>
    </form>
    <div class="ss-coordinates"><span>Latitude <b>{{latitude}}</b></span><span>Longitude <b>{{longitude}}</b></span></div>
  </section>

  <section class="ss-side-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">WORLD ACCESS</span><h2>Destinations</h2></div>
    <div class="ss-destination-list">{{townslist}}</div>
    <div class="ss-side-actions">
      {{adminlink}}
      <button type="button" class="ss-side-action" onclick="procurarjogador()"><span>Find operative</span><b>⌕</b></button>
      <a class="ss-side-action" href="pm.php?do=ler"><span>Messages</span><b>→</b></a>
      <a class="ss-side-action" href="mainmsg.php?do2=enviarpm"><span>New message</span><b>+</b></a>
    </div>
  </section>

  <section class="ss-side-card ss-history-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">FIELD LOG</span><h2>Recent activity</h2></div>
    <div class="ss-history-preview"><span class="ss-history-preview__label">Last threat defeated</span><strong>$monstro[0]</strong><span>$monstro[1] encounter(s)</span></div>
    <div class="ss-history-content">$fimh</div>
  </section>
  <div id="procurarjog"></div>
</aside>
THEVERYENDOFYOU;
?>
