<?php
// Shadow route alias: map English actions onto the legacy training/contract handlers.
$requested = isset($_GET['do']) ? $_GET['do'] : '';
if ($requested === 'discipline') {
    $_GET['do'] = 'treinamento';
} elseif ($requested === 'contracts') {
    $_GET['do'] = 'quests';
}

// The legacy quest screen loops when no optional contracts exist. Give the
// player a proper Shadow empty state instead of redirecting through index.php.
if ($requested === 'quests' || $requested === 'contracts') {
    require_once __DIR__ . '/lib.php';
    require_once __DIR__ . '/cookies.php';
    global $link, $userrow;
    if (!isset($link) || !$link) {
        $link = opendb();
    }
    $userrow = checkcookies();
    if ($userrow === false) {
        display('Please <a href="login.php?do=login">log in</a> before opening Contracts.', 'Error', false, false, false);
        die();
    }
    $questState = isset($userrow['questsaux']) ? trim((string)$userrow['questsaux']) : '';
    if ($questState === '' || strcasecmp($questState, 'None') === 0) {
        display(
            '<div class="ss-empty-state"><div class="ss-eyebrow">CONTRACTS</div><h2>No Active Contracts</h2><p>There are no optional Contracts available for this Operative yet. Explore the world, advance your Standing, and return when new contracts become available.</p><a class="ss-action" href="index.php">Return to World</a></div>',
            'Contracts'
        );
        die();
    }
}

require_once __DIR__ . '/treinamentoequests.php';
