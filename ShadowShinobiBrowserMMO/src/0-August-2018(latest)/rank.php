<?php 
include('lib.php'); 
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysqli_fetch_array($controlquery) ?: array();

include('cookies.php');
$userrow = checkcookies();

$allowedOrders = array('level', 'attackpower', 'defensepower');
$ord = $_GET['ord'] ?? 'level';
if (!in_array($ord, $allowedOrders, true)) {
    $ord = 'level';
}

$page = "
<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/rank.gif\" /></center></td></tr></table>
<center>[ <a href=\"index.php\">Return to Game</a> ]</center>

<br />

<table width=\"100%\" style=\"border: solid 1px black\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td colspan=\"5\" bgcolor=\"#ffffff\"><center><b>Rankings by Standing</b></center></td></tr>
<tr><td><b>Rank</b></td><td><b><a href=\"rank.php?ord=level\" title=\"Sort by Standing\">Standing</a></b></td><td><a href=\"rank.php?ord=attackpower\" title=\"Sort by Attack Power\">Attack Power</a></td><td><a href=\"rank.php?ord=defensepower\" title=\"Sort by Defense Power\">Defense Power</a></td><td><b>Name</b></td></tr>
";

$count = 1;
$contagemrank = 0;
$usersquery = doquery("SELECT * FROM {{table}} ORDER BY $ord DESC, level DESC LIMIT 103", "users");
while ($usersrow = mysqli_fetch_array($usersquery)) {
    if (($usersrow["authlevel"] ?? 0) != 1) {
        if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
        $contagemrank += 1;
        $charname = htmlspecialchars((string)($usersrow["charname"] ?? ''), ENT_QUOTES, 'UTF-8');
        $page .= "<tr><td $color width=\"15%\">$contagemrank</td><td $color width=\"15%\">".($usersrow["level"] ?? 0)."</td><td $color width=\"15%\">".($usersrow["attackpower"] ?? 0)."</td><td $color width=\"15%\">".($usersrow["defensepower"] ?? 0)."</td><td $color width=\"*\"><a href=\"javascript: mostrarchar('".$charname."');\">".$charname."</a></td></tr>\n";
    }
}

$page .= "
</table>";
display($page, "Rankings by Standing", false, false, false);
?>
