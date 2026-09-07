<?php
// Shadow route alias: map English actions onto the legacy training/contract handlers.
$requested = isset($_GET['do']) ? $_GET['do'] : '';
if ($requested === 'discipline') {
    $_GET['do'] = 'treinamento';
} elseif ($requested === 'contracts') {
    $_GET['do'] = 'quests';
}

// Legacy training/contract screens redirect when their corresponding data is
// empty. Provide clean Shadow empty states instead of redirect loops.
if ($requested === 'quests' || $requested === 'contracts' || $requested === 'discipline') {
    require_once __DIR__ . '/lib.php';
    require_once __DIR__ . '/cookies.php';
    global $link, $userrow;
    if (!isset($link) || !$link) {
        $link = opendb();
    }
    $userrow = checkcookies();
    if ($userrow === false) {
        display('Please <a href="login.php?do=login">log in</a> before opening this screen.', 'Error', false, false, false);
        die();
    }

    if ($requested === 'quests' || $requested === 'contracts') {
        $questState = isset($userrow['questsaux']) ? trim((string)$userrow['questsaux']) : '';
        if ($questState === '' || strcasecmp($questState, 'None') === 0) {
            display(
                '<div class="ss-empty-state"><div class="ss-eyebrow">CONTRACTS</div><h2>No Active Contracts</h2><p>There are no optional Contracts available for this Operative yet. Explore the world, advance your Standing, and return when new contracts become available.</p><a class="ss-action" href="index.php">Return to World</a></div>',
                'Contracts'
            );
            die();
        }
    }

    if ($requested === 'discipline') {
        $disciplineState = isset($userrow['treinamento']) ? trim((string)$userrow['treinamento']) : '';
        if ($disciplineState === '' || strcasecmp($disciplineState, 'None') === 0) {
            display(
                '<div class="ss-empty-state"><div class="ss-eyebrow">DISCIPLINE</div><h2>No Active Disciplines</h2><p>This Operative has no active Discipline programs yet. Advance through the world and return when a new Discipline becomes available.</p><a class="ss-action" href="index.php">Return to World</a></div>',
                'Discipline'
            );
            die();
        }
    }
}

require_once __DIR__ . '/treinamentoequests.php';
