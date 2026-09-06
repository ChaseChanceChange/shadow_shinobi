<?php
global $userrow;
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
<link rel="stylesheet" href="shadow-modern.css">
<link rel="stylesheet" href="shadow-explore.css">
<style>.ss-popup{width:min(100%,1100px);min-height:100vh;margin:auto;padding:14px;background:#07090d;color:#edf2f7;font-family:Inter,system-ui,-apple-system,"Segoe UI",Tahoma,sans-serif}.ss-popup__head{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:11px 13px;border:1px solid #303947;border-radius:9px;background:linear-gradient(180deg,#171c24,#0f141b)}.ss-popup__brand{font-weight:800;letter-spacing:.12em;font-size:.78rem}.ss-popup__title{color:#98a4b2;font-size:.7rem}.ss-popup__body{margin-top:12px;padding:12px;border:1px solid #303947;border-radius:9px;background:#0b0f15;overflow:auto}.ss-popup__footer{padding:10px;color:#687585;font-size:.65rem;text-align:center}</style>
</head>
<body>
<main class="ss-popup">
  <header class="ss-popup__head"><span class="ss-popup__brand">SHADOW SHINOBI</span><span class="ss-popup__title">{{title}}</span></header>
  <section class="ss-popup__body">{{content}}</section>
  <footer class="ss-popup__footer">Shadow Shinobi · {{title}}</footer>
  <div id="mainmsg">$mainmsg</div>
</main>
</body>
</html>
THEVERYENDOFYOU;
?>
