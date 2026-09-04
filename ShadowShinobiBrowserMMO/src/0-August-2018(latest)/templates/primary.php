<?php

global $userrow;

$widthall = ($userrow == false) ? 700 : 990;
$widthp = ($userrow == false) ? 0 : 204;
$widths = ($userrow == false) ? 0 : 204;

global $mainmsg;
include('mostrarmainmsg.php');
include('getheader.php');

$template = <<<THEVERYENDOFYOU
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#07090d">
<title>Shadow Shinobi :: {{title}}</title>
<link rel="stylesheet" href="novobotao.css">
<link rel="stylesheet" href="shadow-modern.css">
<script>
(function(){
  'use strict';
  var tempX = 0, tempY = 0;

  function mouseXY(e){
    e = e || window.event;
    tempX = typeof e.pageX === 'number' ? e.pageX : e.clientX + window.scrollX;
    tempY = typeof e.pageY === 'number' ? e.pageY : e.clientY + window.scrollY;
    return true;
  }
  window.addEventListener('mousemove', mouseXY, {passive:true});

  window.opencharpopup = function(){ window.open('index.php?do=showchar','','width=520,height=760,scrollbars=yes,resizable=yes'); };
  window.mostrarchar = function(nome){ window.open('mostrarchar.php?nomechar=' + encodeURIComponent(nome || ''),'','width=360,height=700,scrollbars=yes,resizable=yes'); };
  window.openmappopup = function(azul){ var url='index.php?do=showmap'; if (azul) url += '&lugarazul=' + encodeURIComponent(azul); window.open(url,'','width=680,height=680,scrollbars=yes,resizable=yes'); };
  window.openmaprespawn = function(item){ var url='index.php?do=showmap'; if (item) url += '&item=' + encodeURIComponent(item); window.open(url,'','width=680,height=680,scrollbars=yes,resizable=yes'); };
  window.openmapmonster = function(nome){ var url='index.php?do=showmap'; if (nome) url += '&monstro=' + encodeURIComponent(nome); window.open(url,'','width=680,height=680,scrollbars=yes,resizable=yes'); };
  window.openchatpopup = function(){ window.open('index.php?do=babblebox&tamanho=55','','width=480,height=560,scrollbars=yes,resizable=yes'); };
  window.mostrartreino = function(numero,recompensa,requerimento,localtreino){ var el=document.getElementById('elemento'+numero); if(!el)return; el.innerHTML='<div class="ss-detail-grid"><div><span>Requirement</span>'+requerimento+'</div><div><span>Reward</span>'+recompensa+'</div><div><span>Training location</span>'+localtreino+'</div></div>'; };
  window.mostrarquest = function(numero,recompensa,requerimento,localtreino,nivel,conclusao,itemprocurado,local,monstroprocurado){ var el=document.getElementById('elemento'+numero); if(!el)return; var links=''; if(local)links+=' <button type="button" class="ss-inline-link" onclick="openmappopup(\\''+String(local).replace(/\\'/g,'')+'\\')">Map</button>'; if(monstroprocurado)links+=' <button type="button" class="ss-inline-link" onclick="openmapmonster(\\''+String(monstroprocurado).replace(/\\'/g,'')+'\\')">Enemy</button>'; if(itemprocurado)links+=' <button type="button" class="ss-inline-link" onclick="openmaprespawn(\\''+String(itemprocurado).replace(/\\'/g,'')+'\\')">Drop</button>'; el.innerHTML='<div class="ss-detail-grid"><div><span>Requirement</span>'+requerimento+links+'</div><div><span>Reward</span>'+recompensa+'</div><div><span>Completion</span>'+conclusao+'</div><div><span>Level</span>'+nivel+'</div><div><span>Location</span>'+localtreino+'</div></div>'; };
  window.mostrargraduacao = function(nomeelemento,requerimento,ganhos,itemprocurado){ var el=document.getElementById(nomeelemento); if(!el)return; el.innerHTML='<div class="ss-detail-grid"><div><span>Requirement</span>'+requerimento+'</div><div><span>Bonus</span>'+ganhos+'</div>'+(itemprocurado?'<div><span>Required item</span>'+itemprocurado+'</div>':'')+'</div>'; };
  window.escondertreino = function(numero){ var el=document.getElementById('elemento'+numero); if(el)el.innerHTML=''; };
  window.sumirbotao = function(id){ var el=document.getElementById(id); if(el)el.style.visibility='hidden'; };

  function getNode(id){ return document.getElementById(id); }
  window.menudrop = function(objeto,titulo,conteudo){ var el=getNode('dropmenu'); if(!el)return; el.innerHTML='<div class="ss-popover"><div class="ss-popover__title">'+titulo+'</div><div>'+conteudo+'</div></div>'; el.hidden=false; };
  window.menudropdir = window.menudrop;
  window.menuprincipal = function(conteudo){ var el=getNode('menuprincipal'); if(!el)return; el.innerHTML='<div class="ss-popover">'+conteudo+'</div>'; el.hidden=false; };
  window.explicdrop = function(objeto,titulo,conteudo){ var el=getNode('explicmenu'); if(!el)return; el.innerHTML='<div class="ss-popover"><div class="ss-popover__title">'+titulo+'</div><div>'+conteudo+'</div></div>'; el.hidden=false; };
  window.yesorno = function(objeto,conteudo,yes,no){ var el=getNode('explicmenu'); if(!el)return; el.innerHTML='<div class="ss-popover"><div class="ss-popover__title">'+conteudo+'</div><div class="ss-confirm"><a href="'+yes+'">Yes</a><a href="'+no+'">No</a></div></div>'; el.hidden=false; };
  window.fecharexplic = function(){ var el=getNode('explicmenu'); if(el){el.innerHTML='';el.hidden=true;} };
  window.fecharmenuprincipal = function(){ var el=getNode('menuprincipal'); if(el){el.innerHTML='';el.hidden=true;} };
  window.fechardrop = function(){ var el=getNode('dropmenu'); if(el){el.innerHTML='';el.hidden=true;} };
  window.fechargrande = function(){ var el=getNode('mainmsg'); if(el)el.innerHTML=''; };
  window.mostrarpass = function(id){ var el=getNode(id); if(el)el.type = el.type === 'password' ? 'text' : 'password'; };
  window.mostrarjogadores = function(html){ var el=getNode('jogadoresmapa'); if(el)el.innerHTML=html; };
  window.fecharjogadores = function(){ var el=getNode('jogadoresmapa'); if(el)el.innerHTML=''; };
  window.opcaochar = function(nome){
    var safe = String(nome || '').replace(/[<>"']/g,'');
    menuprincipal('<a href="javascript:mostrarchar(\\''+safe+'\\')">View profile</a><br><a href="mainmsg.php?do2=enviarpm&nomedochar='+encodeURIComponent(safe)+'">Send message</a>');
  };
  window.procurarjogador = function(){ var el=getNode('procurarjog'); if(!el)return; el.innerHTML='<form class="ss-search-inline" onsubmit="event.preventDefault();mostrarchar(this.nome.value)"><input id="ss-player-search" name="nome" maxlength="30" placeholder="Character name"><button type="submit">View</button></form>'; };

  document.addEventListener('click', function(e){
    if(!e.target.closest('.ss-popover') && !e.target.closest('[data-menu-trigger]')){
      window.fecharmenuprincipal(); window.fechardrop();
    }
  });
})();
</script>
</head>
<body>
<div class="ss-app">
  <header class="ss-header">
    <div class="ss-header__brand">
      <a href="index.php" class="ss-brand-link" aria-label="Shadow Shinobi home">
        <span class="ss-brand-mark">SS</span>
        <span><strong>SHADOW SHINOBI</strong><small>Browser MMO</small></span>
      </a>
    </div>
    <nav class="ss-header__nav" aria-label="Primary navigation">
      <a href="index.php">Home</a>
      <a href="rank.php">Rankings</a>
      <a href="help.php">Codex</a>
    </nav>
    <div class="ss-header__actions">
      <a href="help.php" title="Help">?</a>
      <a href="login.php?do=logout" title="Log out">Exit</a>
    </div>
  </header>

  <main class="ss-main">
    <section class="ss-main__content" aria-labelledby="page-title">
      <div class="ss-page-heading">
        <span class="ss-eyebrow">SHADOW SHINOBI</span>
        <h1 id="page-title">{{title}}</h1>
      </div>
      <div class="ss-content-card">
        {{content}}
      </div>
    </section>
    <aside class="ss-main__side ss-main__side--left">{{leftnav}}</aside>
    <aside class="ss-main__side ss-main__side--right">{{rightnav}}</aside>
  </main>

  <footer class="ss-footer">
    <span>Shadow Shinobi</span>
    <span>{{totaltime}}s · {{numqueries}} queries</span>
    <span>Build {{version}} {{build}}</span>
  </footer>
</div>

<div id="dropmenu" class="ss-overlay" hidden></div>
<div id="explicmenu" class="ss-overlay" hidden></div>
<div id="char" class="ss-overlay" hidden></div>
<div id="menuprincipal" class="ss-overlay ss-overlay--menu" hidden></div>
<div id="mainmsg" class="ss-modal-host">$mainmsg</div>
</body>
</html>
THEVERYENDOFYOU;
?>