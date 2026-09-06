<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-combat" aria-labelledby="ss-combat-title">
  <div class="ss-combat__header">
    <div>
      <span class="ss-eyebrow">FIELD ENGAGEMENT</span>
      <h1 class="ss-combat__title" id="ss-combat-title">Combat Encounter</h1>
      <div class="ss-combat__sub">Assess the threat, choose an Art, or break contact.</div>
    </div>
    <div class="ss-combat__threat"><span class="ss-eyebrow">TARGET</span><strong>{{monstername}}</strong></div>
  </div>

  {{indexconteudo}}

  <div class="ss-combat__arena">
    <div class="ss-combat__panel">
      <div class="ss-combat__fighter">
        <div>
          {{dados}}
          <div class="ss-combat__status"><span>Engagement status</span><strong>ACTIVE</strong></div>
          <p class="ss-combat__target"><strong>Current Threat</strong> {{monstername}}</p>
          <div class="ss-combat__log">
            {{yourturn}}
            {{monsterturn}}
          </div>
          {{monsterhp}}
          {{command}}
        </div>
        <div class="ss-combat__graphic" aria-label="Combat illustration">
          <table width="165" height="175" background="layoutnovo/graficos/fundo.png" style="background-repeat:no-repeat;background-position:left top"><tr height="{{porcent}}"><td></td></tr><tr><td valign="bottom"><center><img src="layoutnovo/graficos/{{grafico}}" alt="Combat opponent" /></center></td></tr><tr><td></td></tr></table>
        </div>
      </div>
    </div>
  </div>
</section>
THEVERYENDOFYOU;
?>
