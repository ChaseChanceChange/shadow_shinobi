<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-combat" aria-labelledby="ss-combat-title">
  <div class="ss-combat__header">
    <div>
      <h1 class="ss-combat__title" id="ss-combat-title">Battle</h1>
      <div class="ss-combat__sub">Combat results and commands are driven by the legacy game engine.</div>
    </div>
    <div class="small">{{monstername}}</div>
  </div>

  {{indexconteudo}}

  <div class="ss-combat__arena">
    <div class="ss-combat__panel">
      <div class="ss-combat__fighter">
        <div>
          {{dados}}
          <p><strong>You are fighting:</strong> {{monstername}}</p>
          {{yourturn}}
          {{monsterturn}}
          {{monsterhp}}
          {{command}}
        </div>
        <div class="ss-combat__graphic" aria-label="Battle illustration">
          <table width="165" height="175" background="layoutnovo/graficos/fundo.png" style="background-repeat:no-repeat;background-position:left top"><tr height="{{porcent}}"><td></td></tr><tr><td valign="bottom"><center><img src="layoutnovo/graficos/{{grafico}}" alt="Battle opponent" /></center></td></tr><tr><td></td></tr></table>
        </div>
      </div>
    </div>
  </div>
</section>
THEVERYENDOFYOU;
?>
