<?php
global $userrow;
include('funcoesinclusas.php');

// Backpack slots.
for ($i = 1; $i < 5; $i ++) {
    if ($userrow["bp".$i] != "None") {
        $varbackpack[$i] = explode(",",$userrow["bp".$i]);
        $pastadoslot = "";
        if ($varbackpack[$i][2] > 3) {$pastadoslot = "drops/";}
        if ($varbackpack[$i][3] == "X") {$varbackpack[$i][3] = "*";}
        $varbackpack[$i][0] = conteudoexplic($varbackpack[$i][1], $varbackpack[$i][2], 'bp'.$i.'atr', $varbackpack[$i][3]);
        if (!is_numeric($varbackpack[$i][1])) {$varbackpack[$i][1] .= $varbackpack[$i][2]; $pastadoslot = "";}
        $bpcodigo[$i] = "<a href=\"backpack.php?qual=$i\"><img src=\"layoutnovo/equipamentos/$pastadoslot".$varbackpack[$i][1].".gif\" width=\"34\" height=\"34\" alt=\"Backpack item $i\" onmouseover=\"".$varbackpack[$i][0]."\" onmouseout=\"fecharexplic();\" id=\"bp".$i."atr\"/></a>";
    } else {
        $bpcodigo[$i] = "<img src=\"images/gif.gif\" width=\"34\" height=\"34\" alt=\"Empty backpack slot\"/>";
    }
}

$jutsudebuscahtml = "";
if (($userrow['jutsudebuscahtml'] ?? 0) == 1) {
    $jutsudebuscahtml = "<a href=\"mainmsg.php?do2=usarjutsubusca\" class=\"ss-side-link\" title=\"Jutsu Ocular\">Search Technique</a>";
}

$durabm = explode(",", (string)($userrow["durabilidade"] ?? ""));
for ($i = 1; $i < 7; $i ++) {
    if (!isset($durabm[$i])) { $durabm[$i] = "0"; }
    if ($durabm[$i] == "X") {$durabm[$i] = "*";}
}

if (($userrow["magiclist"] ?? "") == "" || ($userrow["magiclist"] ?? "") == "None") {
    $userrow["magiclist"] = "Nenhum Jutsu.";
}

$olhosenjutsu = "";
$senjutsuhtml = $userrow["senjutsuhtml"] ?? "";
if ($senjutsuhtml == "fechado") {
    $olhosenjutsu = "<a class=\"ss-side-focus\" href=\"senjutsu.php?do=usar\"><img src=\"images/olhos/".$senjutsuhtml.".jpg\" alt=\"Activate Senjutsu\" title=\"Ativar Senjutsu (1NP/3s)\"></a>";
} elseif ($senjutsuhtml == "senjutsu") {
    include('funcoesinclusas.php');
    senjutsu();
    if (($userrow["currentnp"] ?? 0) == 0) {$titulo = "Ativar Senjutsu (1NP/3s)";} else {$titulo = "Desativar Senjutsu";}
    $olhosenjutsu = "<a class=\"ss-side-focus\" href=\"senjutsu.php?do=cancelar\"><img src=\"images/olhos/".$senjutsuhtml.".jpg\" alt=\"Senjutsu\" title=\"$titulo\"></a>";
}

$armaatr = conteudoexplic($userrow["weaponid"], '1', 'armaatr', $durabm[1] ?? '*');
$shieldatr = conteudoexplic($userrow["shieldid"], '3', 'shieldatr', $durabm[3] ?? '*');
$armoratr = conteudoexplic($userrow["armorid"], '2', 'armoratr', $durabm[2] ?? '*');
$slot1atr = conteudoexplic($userrow["slot1id"], '4', 'slot1atr', $durabm[4] ?? '*');
$slot2atr = conteudoexplic($userrow["slot2id"], '4', 'slot2atr', $durabm[5] ?? '*');
$slot3atr = conteudoexplic($userrow["slot3id"], '4', 'slot3atr', $durabm[6] ?? '*');

$lvlquery = doquery("SELECT ".$userrow['charclass']."_exp FROM {{table}} WHERE id='".$userrow['level']."'", "levels");
$lvlquery2 = doquery("SELECT ".$userrow['charclass']."_exp FROM {{table}} WHERE id='".($userrow['level'] + 1)."'", "levels");
$lvlrow = mysqli_fetch_array($lvlquery) ?: array();
$lvlrow2 = mysqli_fetch_array($lvlquery2) ?: array();
$classExpKey = $userrow['charclass']."_exp";
$xpCurrent = (int)($lvlrow[$classExpKey] ?? 0);
$xpNext = (int)($lvlrow2[$classExpKey] ?? 0);
$xpproxlvl = $xpNext - $xpCurrent;
$porcconcluida = $xpproxlvl > 0 ? floor((($userrow['experience'] - $xpCurrent) * 100) / $xpproxlvl) : 0;
$porcconcluida = max(0, min(100, $porcconcluida));
$quantofaltaxp = max(0, $xpproxlvl - ($userrow['experience'] - $xpCurrent));
$widthbar = round((155 * $porcconcluida)/100);
$barrahtml = "<div class=\"ss-levelbar\" onmouseover=\"explicdrop('qualquer', 'Dados do Level', 'XP Atual: ".$userrow['experience']."<br>XP P/ Lvl Up: ".$quantofaltaxp."<br>Porc. Conclu&iacute;da: ".$porcconcluida."%','1','1');\" onmouseout=\"fecharexplic();\"><div class=\"ss-levelbar__fill\" style=\"width:{$widthbar}px\"><img src=\"images/levelbarin.jpg\" width=\"$widthbar\" height=\"36\" alt=\"\"></div><img class=\"ss-levelbar__frame\" src=\"images/levelbar.png\" id=\"qualquer\" alt=\"Level {$userrow['level']}\"><span class=\"ss-levelbar__level\">".$userrow['level']."</span></div>";

$segundaarmaimagem = '';
if (file_exists('layoutnovo/equipamentos/'.$userrow['weaponid'].'d.gif')) {
    $segundaarmaimagem = '<a href="desequipar.php?qual=1"><img src="layoutnovo/equipamentos/'.$userrow['weaponid'].'d.gif" alt="Secondary weapon" onmouseover="'.$armaatr.'" onmouseout="fecharexplic();" id="armaatr-secondary"></a>';
}

$template = <<<THEVERYENDOFYOU
<aside class="ss-sidebar" aria-label="Player status and equipment">
  <section class="ss-side-card ss-player-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">PLAYER</span><h2>{{charname}}</h2></div>
    <div class="ss-player-card__avatar"><a href="outros.php?do=avatar"><img src="layoutnovo/avatares/{{avatar}}.jpg" alt="Choose avatar" title="Selecionar Avatar"></a>$olhosenjutsu</div>
    $barrahtml
    <div class="ss-statbars" title="Player status">{{statbars}}</div>
    <nav class="ss-side-nav">
      <a href="javascript:mostrarchar('{{charname}}')">Character sheet</a>
      <a href="outroseatributos.php?do=atributos">Attributes</a>
      <a href="treinamentoequests.php?do=quests">Missions</a>
      <a href="treinamentoequests.php?do=treinamento">Training</a>
    </nav>
  </section>

  <section class="ss-side-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">LOADOUT</span><h2>Equipment</h2></div>
    <div class="ss-equipment-grid">
      <div class="ss-equipment-slot ss-equipment-slot--shield"><a href="desequipar.php?qual=3"><img src="layoutnovo/equipamentos/{{shieldid}}.gif" alt="{{shieldname}}" onmouseover="$shieldatr" onmouseout="fecharexplic();" id="shieldatr"></a></div>
      <div class="ss-equipment-slot ss-equipment-slot--weapon"><a href="desequipar.php?qual=1"><img src="layoutnovo/equipamentos/{{weaponid}}.gif" alt="{{weaponname}}" onmouseover="$armaatr" onmouseout="fecharexplic();" id="armaatr"></a></div>
      <div class="ss-equipment-slot ss-equipment-slot--secondary">$segundaarmaimagem</div>
      <div class="ss-equipment-slot ss-equipment-slot--armor"><a href="desequipar.php?qual=2"><img src="layoutnovo/equipamentos/{{armorid}}.gif" alt="{{armorname}}" onmouseover="$armoratr" onmouseout="fecharexplic();" id="armoratr"></a></div>
    </div>
    <div class="ss-slot-row">
      <a href="desequipar.php?qual=4"><img src="layoutnovo/equipamentos/drops/{{slot1id}}.gif" alt="{{slot1name}}" onmouseover="$slot1atr" onmouseout="fecharexplic();" id="slot1atr"></a>
      <a href="desequipar.php?qual=5"><img src="layoutnovo/equipamentos/drops/{{slot2id}}.gif" alt="{{slot2name}}" onmouseover="$slot2atr" onmouseout="fecharexplic();" id="slot2atr"></a>
      <a href="desequipar.php?qual=6"><img src="layoutnovo/equipamentos/drops/{{slot3id}}.gif" alt="{{slot3name}}" onmouseover="$slot3atr" onmouseout="fecharexplic();" id="slot3atr"></a>
    </div>
    <div class="ss-item-list">
      <div><img src="images/icon_weapon.gif" alt="Weapon"><span>Weapon</span><b>{{weaponname}}</b><small>Durability: $durabm[1]</small></div>
      <div><img src="images/icon_armor.gif" alt="Armor"><span>Armor</span><b>{{armorname}}</b><small>Durability: $durabm[2]</small></div>
      <div><img src="images/orb.gif" alt="Slot 1"><span>Slot 1</span><b>{{slot1name}}</b><small>Durability: $durabm[4]</small></div>
      <div><img src="images/orb.gif" alt="Slot 2"><span>Slot 2</span><b>{{slot2name}}</b><small>Durability: $durabm[5]</small></div>
      <div><img src="images/orb.gif" alt="Slot 3"><span>Slot 3</span><b>{{slot3name}}</b><small>Durability: $durabm[6]</small></div>
    </div>
  </section>

  <section class="ss-side-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">INVENTORY</span><h2>Backpack</h2></div>
    <a class="ss-backpack__image" href="backpack.php"><img src="images/{{bpimagem}}.jpg" alt="Open backpack"></a>
    <div class="ss-backpack__slots">$bpcodigo[1]$bpcodigo[2]$bpcodigo[3]$bpcodigo[4]</div>
  </section>

  <section class="ss-side-card">
    <div class="ss-side-card__header"><span class="ss-eyebrow">ABILITIES</span><h2>Techniques</h2></div>
    <div class="ss-technique-icon"><img src="layoutnovo/menuslados/jutsu.png" alt="Techniques"></div>
    $jutsudebuscahtml
    <div class="ss-technique-list">{{magiclist}}</div>
  </section>
</aside>
THEVERYENDOFYOU;
?>
