<?php
// Search Art compatibility route. The legacy URL and persistent field names remain for engine compatibility.

include('lib.php');
$link = opendb();
include('cookies.php');
$userrow = checkcookies();

if (isset($_GET['do'])) {
    switch ($_GET['do']) {
        case 'jutsu':
            searchArtInfo();
            break;
        case 'aprendendo2':
            developSearchArt();
            break;
        case 'usar':
            useSearchArt();
            break;
    }
}

function ssSearchArtRedirect(string $message, string $route = 'index.php'): void {
    $query = http_build_query(['conteudo' => $message], '', '&', PHP_QUERY_RFC3986);
    header('Location: ' . $route . ($query !== '' ? '?' . $query : ''));
    die();
}

function ssRequireFieldAccess(): void {
    global $userrow;
    if ($userrow === false) {
        ssSearchArtRedirect('Please log in before using the Search Art.');
    }
    if (($userrow['currentaction'] ?? '') === 'Fighting') {
        ssSearchArtRedirect('You cannot use this system during combat.', 'index.php?do=fight');
    }
    if (($userrow['batalha_timer2'] ?? 0) == 5) {
        ssSearchArtRedirect('You cannot change actions while a duel is active.');
    }
}

function ssSearchArtIsDeveloped(): bool {
    global $userrow;
    return (string)($userrow['jutsudebuscahtml'] ?? '0') === '1';
}

function searchArtInfo(): void {
    ssRequireFieldAccess();
    global $userrow;

    $page = '<section class="ss-card ss-codex">'
        . '<div class="ss-action-card__header"><span class="ss-eyebrow">FIELD ART</span>'
        . '<h2>Search Art</h2>'
        . '<p>A trained perception discipline that reveals an operative\'s last known coordinates and online state.</p></div>'
        . '<div class="ss-detail-grid">'
        . '<div><span>Training</span>10 development sessions</div>'
        . '<div><span>Cooldown</span>120 minutes between sessions</div>'
        . '<div><span>Use cost</span>30 Essence</div>'
        . '<div><span>Function</span>Locate an operative by name</div>'
        . '</div>';

    if (ssSearchArtIsDeveloped()) {
        $page .= '<p class="ss-message"><strong>Search Art is active.</strong> Enter an operative name to locate them.</p>'
            . '<a class="ss-side-action" href="jutsudebusca.php?do=usar">Use Search Art</a>';
    } else {
        $page .= '<p class="ss-message">Search Art has not been developed yet.</p>'
            . '<a class="ss-side-action" href="jutsudebusca.php?do=aprendendo2&inicio=true">Begin development</a>';
    }

    display($page, 'Search Art', false, false, false);
}

function developSearchArt(): void {
    ssRequireFieldAccess();
    global $userrow;

    $today = date('j/n/Y');
    $todayHour = date('H:i:s');
    $trainingKey = 'Search Art';
    $legacyKey = 'Jutsu de Busca';
    $totalSessions = 10;
    $cooldownMinutes = 120;
    $start = isset($_GET['inicio']) && $_GET['inicio'] === 'true';

    $training = (string)($userrow['treinamento'] ?? 'None');
    $entries = ($training === '' || $training === 'None') ? [] : array_values(array_filter(explode(';', $training), 'strlen'));
    $found = false;
    $valueNow = 0;
    $valueTotal = $totalSessions;
    $rebuilt = [];

    foreach ($entries as $entry) {
        $parts = explode(',', $entry);
        $entryKey = $parts[0] ?? '';
        if ($entryKey !== $trainingKey && $entryKey !== $legacyKey) {
            $rebuilt[] = $entry;
            continue;
        }

        $found = true;
        $current = isset($parts[1]) ? (int)$parts[1] : 0;
        $total = isset($parts[2]) ? max(1, (int)$parts[2]) : $totalSessions;
        $valueTotal = $total;

        if ($start) {
            ssSearchArtRedirect('Search Art is already in your Discipline queue.', 'treinamentoequests.php?do=treinamento');
        }
        if ($current >= $total) {
            ssSearchArtRedirect('Search Art development is already complete.', 'treinamentoequests.php?do=treinamento');
        }

        include('funcoesinclusas.php');
        $lastDate = $parts[3] ?? $today;
        $lastTime = $parts[4] ?? $todayHour;
        $wait = isset($parts[5]) ? (int)$parts[5] : $cooldownMinutes;
        $ready = tempojutsu($lastDate, $lastTime, $wait);
        if ($ready !== 'ok') {
            ssSearchArtRedirect('Search Art is still on cooldown. Wait ' . (string)$ready . ' minute(s).', 'treinamentoequests.php?do=treinamento');
        }

        $current++;
        $valueNow = $current;
        $rebuilt[] = $trainingKey . ',' . $current . ',' . $total . ',' . $today . ',' . $todayHour . ',' . $cooldownMinutes;
    }

    if (!$found) {
        $valueNow = 1;
        $rebuilt[] = $trainingKey . ',1,' . $totalSessions . ',' . $today . ',' . $todayHour . ',0';
    }

    $userrow['treinamento'] = implode(';', $rebuilt) . ';';
    doquery("UPDATE {{table}} SET treinamento='" . mysqli_real_escape_string($link, $userrow['treinamento']) . "' WHERE charname='" . mysqli_real_escape_string($link, $userrow['charname']) . "' LIMIT 1", 'users');

    if ($valueNow >= $valueTotal) {
        doquery("UPDATE {{table}} SET jutsudebuscahtml='1' WHERE charname='" . mysqli_real_escape_string($link, $userrow['charname']) . "' LIMIT 1", 'users');
        ssSearchArtRedirect('Search Art development is complete. The discipline is now active.', 'treinamentoequests.php?do=treinamento');
    }

    ssSearchArtRedirect('Search Art development advanced to ' . $valueNow . '/' . $valueTotal . '.', 'treinamentoequests.php?do=treinamento');
}

function useSearchArt(): void {
    ssRequireFieldAccess();
    global $userrow, $link;

    if (!ssSearchArtIsDeveloped()) {
        ssSearchArtRedirect('Search Art must be developed before it can be used.');
    }

    if (!isset($_POST['submit'])) {
        $page = '<section class="ss-card ss-codex">'
            . '<div class="ss-action-card__header"><span class="ss-eyebrow">FIELD ART</span><h2>Search Art</h2>'
            . '<p>Spend 30 Essence to locate an operative.</p></div>'
            . '<form class="ss-search-inline" action="jutsudebusca.php?do=usar" method="post">'
            . '<label for="nomedaprocura">Operative name</label>'
            . '<input id="nomedaprocura" name="nomedaprocura" maxlength="30" required>'
            . '<button type="submit" name="submit">Locate</button>'
            . '</form></section>';
        display($page, 'Search Art', false, false, false);
        return;
    }

    $targetName = isset($_POST['nomedaprocura']) && is_string($_POST['nomedaprocura']) ? trim($_POST['nomedaprocura']) : '';
    if ($targetName === '') {
        ssSearchArtRedirect('Enter an operative name to search.');
    }

    $safeName = mysqli_real_escape_string($link, $targetName);
    $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$safeName' LIMIT 1", 'users');
    if (mysqli_num_rows($userquery) !== 1) {
        ssSearchArtRedirect('No operative with that name exists.');
    }

    $target = mysqli_fetch_array($userquery);
    $mp = (int)($userrow['currentmp'] ?? 0);
    if ($mp < 30) {
        ssSearchArtRedirect('Search Art requires 30 Essence.');
    }

    $remaining = $mp - 30;
    doquery("UPDATE {{table}} SET currentmp='$remaining' WHERE charname='" . mysqli_real_escape_string($link, $userrow['charname']) . "' LIMIT 1", 'users');

    $lat = (int)$target['latitude'];
    $long = (int)$target['longitude'];
    $latLabel = $lat === 0 ? '0' : abs($lat) . ($lat > 0 ? 'N' : 'S');
    $longLabel = $long === 0 ? '0' : abs($long) . ($long > 0 ? 'E' : 'W');

    $onlineQuery = doquery("SELECT id FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '" . (time() - 61) . "' AND charname='$safeName' LIMIT 1", 'users');
    $online = mysqli_num_rows($onlineQuery) === 1;

    $page = '<section class="ss-card ss-codex"><div class="ss-action-card__header">'
        . '<span class="ss-eyebrow">SEARCH RESULT</span><h2>' . htmlspecialchars($target['charname'], ENT_QUOTES, 'UTF-8') . '</h2></div>'
        . '<div class="ss-detail-grid">'
        . '<div><span>Latitude</span>' . htmlspecialchars($latLabel, ENT_QUOTES, 'UTF-8') . '</div>'
        . '<div><span>Longitude</span>' . htmlspecialchars($longLabel, ENT_QUOTES, 'UTF-8') . '</div>'
        . '<div><span>Status</span>' . ($online ? 'Online' : 'Offline') . '</div>'
        . '<div><span>Essence spent</span>30</div>'
        . '</div>'
        . '<a class="ss-side-action" href="jutsudebusca.php?do=usar">Search again</a></section>';

    display($page, 'Search Art', false, false, false);
}
?>
