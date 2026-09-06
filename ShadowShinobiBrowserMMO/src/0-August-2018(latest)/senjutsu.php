<?php
// Essence Discipline compatibility route. Legacy URL/state names remain internal.

if (($valorlib2 ?? '') === '') {
    include('lib.php');
    $link = opendb();
    include('cookies.php');
    $userrow = checkcookies();
}

if (isset($_GET['do'])) {
    switch ($_GET['do']) {
        case 'jutsu': jutsu(); break;
        case 'aprendendo2': aprendendo2(); break;
        case 'usar': usar(); break;
        case 'cancelar': cancelar(); break;
        case 'chamar': chamar(); break;
    }
}

function ssEssenceAccess(): void {
    global $userrow;
    if ($userrow === false) {
        display('Please log in before using the Essence Discipline.', 'Error', false, false, false);
        die();
    }
    if (($userrow['currentaction'] ?? '') === 'Fighting') {
        header('Location: ./index.php?do=fight&conteudo=You cannot use the Essence Discipline during combat.');
        die();
    }
    if (($userrow['batalha_timer2'] ?? 0) == 5) {
        display('You cannot change actions while a duel is active.', 'Error', false, false, false);
        die();
    }
}

function jutsu() {
    ssEssenceAccess();
    global $userrow;

    $townquery = doquery("SELECT * FROM {{table}} WHERE latitude='" . $userrow['latitude'] . "' AND longitude='" . $userrow['longitude'] . "' LIMIT 1", 'towns');
    if (mysqli_num_rows($townquery) === 0) {
        display('Your settlement data could not be located. Please try again.', 'Error');
        die();
    }
    $townrow = mysqli_fetch_array($townquery);
    if ((int)$townrow['id'] !== 2) {
        header('Location: ./treinamentoequests.php?do=treinamento&conteudo=The Essence Discipline is only available at its designated site.');
        die();
    }

    $page = '<section class="ss-card ss-codex">'
        . '<div class="ss-action-card__header"><span class="ss-eyebrow">ESSENCE DISCIPLINE</span><h2>Essence Discipline</h2>'
        . '<p>A focused combat discipline that draws on the world\'s latent energy to temporarily amplify offensive and defensive power.</p></div>'
        . '<div class="ss-detail-grid">'
        . '<div><span>Development</span>12 sessions</div>'
        . '<div><span>Cooldown</span>15 hours between sessions</div>'
        . '<div><span>Activation cost</span>World Essence</div>'
        . '<div><span>Effect</span>+20% Attack and Defense</div>'
        . '</div>';

    if (!empty($userrow['senjutsuhtml'])) {
        $page .= '<p class="ss-message"><strong>Essence Discipline unlocked.</strong> Activation consumes available World Essence over time.</p>'
            . '<a class="ss-side-action" href="senjutsu.php?do=usar">Activate / refresh</a>';
    } else {
        $page .= '<p class="ss-message">This discipline requires Elite Standing or higher.</p>'
            . '<a class="ss-side-action" href="senjutsu.php?do=aprendendo2">Begin development</a>';
    }

    display($page, 'Essence Discipline', false, false, false);
}

function aprendendo2(){
    ssEssenceAccess();
    global $userrow, $link;

    $townquery = doquery("SELECT * FROM {{table}} WHERE latitude='" . $userrow['latitude'] . "' AND longitude='" . $userrow['longitude'] . "' LIMIT 1", 'towns');
    if (mysqli_num_rows($townquery) === 0 || (int)mysqli_fetch_array($townquery)['id'] !== 2) {
        header('Location: ./treinamentoequests.php?do=treinamento&conteudo=The Essence Discipline is only available at its designated site.');
        die();
    }

    // Preserve the legacy standing gate as an internal compatibility rule.
    if (($userrow['graduacao'] ?? '') === 'Estudante da Academia') {
        header('Location: ./treinamentoequests.php?do=treinamento&conteudo=You need at least Initiate Standing before developing this discipline.');
        die();
    }

    $today = date('j/n/Y');
    $todayhour = date('H:i:s');
    $trainingKey = 'Essence Discipline';
    $legacyKeys = ['Senjutsu', 'Sentechnique'];
    $totalSessions = 12;
    $cooldownMinutes = 900;

    $training = (string)($userrow['treinamento'] ?? 'None');
    $entries = ($training === '' || $training === 'None') ? [] : array_values(array_filter(explode(';', $training), 'strlen'));
    $found = false;
    $currentValue = 0;
    $totalValue = $totalSessions;
    $rebuilt = [];

    foreach ($entries as $entry) {
        $parts = explode(',', $entry);
        $key = $parts[0] ?? '';
        if ($key !== $trainingKey && !in_array($key, $legacyKeys, true)) {
            $rebuilt[] = $entry;
            continue;
        }

        $found = true;
        $current = isset($parts[1]) ? (int)$parts[1] : 0;
        $total = isset($parts[2]) ? max(1, (int)$parts[2]) : $totalSessions;
        $totalValue = $total;

        include('funcoesinclusas.php');
        $lastDate = $parts[3] ?? $today;
        $lastTime = $parts[4] ?? $todayhour;
        $wait = isset($parts[5]) ? (int)$parts[5] : $cooldownMinutes;
        $ready = tempojutsu($lastDate, $lastTime, $wait);
        if ($ready !== 'ok') {
            header('Location: ./treinamentoequests.php?do=treinamento&conteudo=Essence Discipline remains on cooldown. Wait ' . $ready . ' minute(s).');
            die();
        }

        $current++;
        $currentValue = $current;
        $rebuilt[] = $trainingKey . ',' . $current . ',' . $total . ',' . $today . ',' . $todayhour . ',' . $cooldownMinutes;
    }

    if (!$found) {
        $currentValue = 1;
        $rebuilt[] = $trainingKey . ',1,' . $totalSessions . ',' . $today . ',' . $todayhour . ',0';
    }

    $userrow['treinamento'] = implode(';', $rebuilt) . ';';
    $safeTraining = mysqli_real_escape_string($link, $userrow['treinamento']);
    $safeChar = mysqli_real_escape_string($link, $userrow['charname']);
    doquery("UPDATE {{table}} SET treinamento='$safeTraining' WHERE charname='$safeChar' LIMIT 1", 'users');

    if ($currentValue >= $totalValue) {
        doquery("UPDATE {{table}} SET senjutsuhtml='fechado',atkdefsenjutsu='0,0',senjutsutimer='None' WHERE charname='$safeChar' LIMIT 1", 'users');
        header('Location: ./treinamentoequests.php?do=treinamento&conteudo=Essence Discipline development is complete.');
        die();
    }

    header('Location: ./treinamentoequests.php?do=treinamento&conteudo=Essence Discipline advanced to ' . $currentValue . '/' . $totalValue . '.');
    die();
}

function usar() {
    ssEssenceAccess();
    global $userrow, $dir;

    if (empty($userrow['senjutsuhtml'])) {
        header('Location: ./index.php?conteudo=You must develop the Essence Discipline before activating it.');
        die();
    }

    $secondsPerTick = 3;
    $mainmsg2 = 'None';

    if ($userrow['senjutsuhtml'] === 'fechado') {
        $today = date('j/n/Y');
        $todayhour = date('H:i:s');
        $bonusAttack = floor(((int)$userrow['attackpower'] * 20) / 100);
        $bonusDefense = floor(((int)$userrow['defensepower'] * 20) / 100);
        $userrow['atkdefsenjutsu'] = $bonusAttack . ',' . $bonusDefense;
        $userrow['attackpower'] += $bonusAttack;
        $userrow['defensepower'] += $bonusDefense;
        if ((int)$userrow['currentnp'] !== 0) {
            $mainmsg2 = 3;
        }
        $userrow['senjutsuhtml'] = 'senjutsu';
        $userrow['senjutsutimer'] = $today . ',' . $todayhour . ',1';
    } else {
        include('funcoesinclusas.php');
        $timer = explode(',', (string)$userrow['senjutsutimer']);
        $elapsed = tempopassarsg($timer[0] ?? '', $timer[1] ?? '', $timer[2] ?? 1);
        $elapsedParts = explode('-', $elapsed);
        $ticks = (int)floor(((int)($elapsedParts[1] ?? 0)) / $secondsPerTick);
        $userrow['currentnp'] -= $ticks;
        if ($userrow['currentnp'] < 0) {
            $userrow['currentnp'] = 0;
        }

        if ($userrow['currentnp'] === 0) {
            $userrow['senjutsutimer'] = 'None';
            $bonus = explode(',', (string)$userrow['atkdefsenjutsu']);
            $userrow['attackpower'] -= (int)($bonus[0] ?? 0);
            $userrow['defensepower'] -= (int)($bonus[1] ?? 0);
            $userrow['senjutsuhtml'] = 'fechado';
            $userrow['atkdefsenjutsu'] = '0,0';
        } elseif ((int)($elapsedParts[1] ?? 0) > $secondsPerTick) {
            $today = date('j/n/Y');
            $todayhour = date('H:i:s');
            $userrow['senjutsutimer'] = $today . ',' . $todayhour . ',1';
        }
    }

    $safeChar = mysqli_real_escape_string($GLOBALS['link'], $userrow['charname']);
    doquery("UPDATE {{table}} SET atkdefsenjutsu='" . mysqli_real_escape_string($GLOBALS['link'], $userrow['atkdefsenjutsu']) . "',attackpower='" . (int)$userrow['attackpower'] . "',defensepower='" . (int)$userrow['defensepower'] . "',senjutsuhtml='" . mysqli_real_escape_string($GLOBALS['link'], $userrow['senjutsuhtml']) . "',senjutsutimer='" . mysqli_real_escape_string($GLOBALS['link'], $userrow['senjutsutimer']) . "',currentnp='" . (int)$userrow['currentnp'] . "',mainmsg='" . mysqli_real_escape_string($GLOBALS['link'], $mainmsg2) . "' WHERE charname='$safeChar' LIMIT 1", 'users');

    if ($dir === '') {
        header('Location: ./' . $userrow['pagina']);
        die();
    }
}

function cancelar() {
    ssEssenceAccess();
    global $userrow, $link;

    if (($userrow['senjutsuhtml'] ?? '') !== 'senjutsu') {
        header('Location: ./index.php?conteudo=The Essence Discipline is already inactive, or you do not have enough World Essence.');
        die();
    }

    include('funcoesinclusas.php');
    $timer = explode(',', (string)$userrow['senjutsutimer']);
    $elapsed = tempopassarsg($timer[0] ?? '', $timer[1] ?? '', $timer[2] ?? 1);
    $elapsedParts = explode('-', $elapsed);
    $ticks = (int)floor(((int)($elapsedParts[1] ?? 0)) / 3);
    $userrow['currentnp'] = max(0, (int)$userrow['currentnp'] - $ticks);
    $bonus = explode(',', (string)$userrow['atkdefsenjutsu']);

    $userrow['attackpower'] -= (int)($bonus[0] ?? 0);
    $userrow['defensepower'] -= (int)($bonus[1] ?? 0);
    $userrow['senjutsuhtml'] = 'fechado';
    $userrow['senjutsutimer'] = 'None';
    $userrow['atkdefsenjutsu'] = '0,0';

    $safeChar = mysqli_real_escape_string($link, $userrow['charname']);
    doquery("UPDATE {{table}} SET atkdefsenjutsu='0,0',attackpower='" . (int)$userrow['attackpower'] . "',defensepower='" . (int)$userrow['defensepower'] . "',senjutsuhtml='fechado',senjutsutimer='None',currentnp='" . (int)$userrow['currentnp'] . "' WHERE charname='$safeChar' LIMIT 1", 'users');
    header('Location: ./' . $userrow['pagina']);
    die();
}

function chamar() {
    ssEssenceAccess();
    global $userrow, $link;

    $safeChar = mysqli_real_escape_string($link, $userrow['charname']);
    doquery("UPDATE {{table}} SET avatar='16' WHERE charname='$safeChar' LIMIT 1", 'users');

    $page = '<section class="ss-card ss-codex">'
        . '<div class="ss-action-card__header"><span class="ss-eyebrow">COMPANION</span><h2>Field Companion</h2>'
        . '<p>A trusted field companion has joined your operative profile.</p></div>'
        . '<a class="ss-side-action" href="index.php">Return to the field</a>'
        . '</section>';

    display($page, 'Field Companion', false, false, false);
}
?>
