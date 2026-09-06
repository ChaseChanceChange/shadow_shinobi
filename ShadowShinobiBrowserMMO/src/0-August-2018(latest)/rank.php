<?php
include('lib.php');
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", 'control');
$controlrow = mysqli_fetch_array($controlquery) ?: array();

include('cookies.php');
$userrow = checkcookies();

$allowedOrders = array('level', 'attackpower', 'defensepower');
$ord = $_GET['ord'] ?? 'level';
if (!in_array($ord, $allowedOrders, true)) {
    $ord = 'level';
}

$labels = array(
    'level' => 'Standing',
    'attackpower' => 'Attack Power',
    'defensepower' => 'Defense Power',
);

$page = '<section class="ss-card ss-rankings">'
    . '<div class="ss-action-card__header"><span class="ss-eyebrow">SHADOW SHINOBI</span><h2>Standing Rankings</h2>'
    . '<p>Track the field by progression, offensive power and defensive power.</p></div>'
    . '<div class="ss-ranking-toolbar">'
    . '<a href="rank.php?ord=level">Standing</a>'
    . '<a href="rank.php?ord=attackpower">Attack Power</a>'
    . '<a href="rank.php?ord=defensepower">Defense Power</a>'
    . '</div>'
    . '<table class="ss-data-table"><thead><tr><th>Place</th><th>' . $labels[$ord] . '</th><th>Attack Power</th><th>Defense Power</th><th>Operative</th></tr></thead><tbody>';

$count = 1;
$rankPosition = 0;
$usersquery = doquery("SELECT * FROM {{table}} ORDER BY $ord DESC, level DESC LIMIT 103", 'users');
while ($usersrow = mysqli_fetch_array($usersquery)) {
    if (($usersrow['authlevel'] ?? 0) != 1) {
        $rankPosition++;
        $charname = htmlspecialchars((string)($usersrow['charname'] ?? ''), ENT_QUOTES, 'UTF-8');
        $page .= '<tr><td>' . $rankPosition . '</td><td>' . (int)($usersrow['level'] ?? 0) . '</td><td>'
            . (int)($usersrow['attackpower'] ?? 0) . '</td><td>' . (int)($usersrow['defensepower'] ?? 0) . '</td><td>'
            . '<a href="javascript:mostrarchar(\'' . addslashes($charname) . '\');">' . $charname . '</a></td></tr>';
    }
}

$page .= '</tbody></table></section>';
display($page, 'Standing Rankings', false, false, false);
?>
