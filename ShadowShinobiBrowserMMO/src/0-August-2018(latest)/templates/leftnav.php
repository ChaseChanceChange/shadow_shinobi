<?php
include('minimap.php');
include('historico.php');
$monstro = explode(",",$userrow["ultmonstro"]);
$imagem = ($userrow["pmsnovas"] == 1) ? "lergif.gif" : "ler1.jpg";
$template = <<<THEVERYENDOFYOU
<aside class="ss-sidebar ss-navigation-sidebar" aria-label="World navigation">
  <section class="ss-side-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">NAVIGATION</span><h2>Coordinates</h2></div>
    <div class="ss-minimap">$minimap</div>
    <a class="ss-side-link ss-side-link--primary" href="javascript:openmappopup()">Open world map</a>
    <form action="index.php?do=move" method="post" class="ss-movement-grid" aria-label="Move operative">
      <button name="north" type="submit">North</button>
      <button name="west" type="submit">West</button>
      <button name="east" type="submit">East</button>
      <button name="south" type="submit">South</button>
    </form>
    <div class="ss-coordinates"><span>Latitude <b>{{latitude}}</b></span><span>Longitude <b>{{longitude}}</b></span></div>
  </section>

  <section class="ss-side-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">WORLD</span><h2>Destinations</h2></div>
    <div class="ss-destination-list">{{townslist}}</div>
    <div class="ss-side-actions">
      {{adminlink}}
      <button type="button" class="ss-side-action" onclick="procurarjogador()">Find operative</button>
      <a class="ss-side-action" href="pm.php?do=ler">Messages <img src="images/$imagem" alt="Messages"></a>
      <a class="ss-side-action" href="mainmsg.php?do2=enviarpm">New message <img src="images/enviar1.jpg" alt="New message"></a>
    </div>
  </section>

  <section class="ss-side-card ss-history-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">HISTORY</span><h2>Recent activity</h2></div>
    <div class="ss-history-preview"><span class="ss-history-preview__label">Last threat defeated</span><strong>$monstro[0]</strong><span>$monstro[1] time(s)</span></div>
    <div class="ss-history-content">$fimh</div>
  </section>
  <div id="procurarjog"></div>
</aside>
THEVERYENDOFYOU;
?>
