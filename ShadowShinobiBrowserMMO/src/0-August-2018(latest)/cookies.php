<?php // cookies.php :: Handles cookies. (Mmm, tasty!)

function checkcookies() {

    include('config.php');

    $row = false;

    // Prefer the raw cookie captured before lib.php mutates $_COOKIE.
    $rawCookie = null;
    if (isset($GLOBALS['_SS_RAW_DKGAME']) && is_string($GLOBALS['_SS_RAW_DKGAME'])) {
        $rawCookie = $GLOBALS['_SS_RAW_DKGAME'];
    } elseif (isset($_COOKIE['dkgame'])) {
        $rawCookie = (string) $_COOKIE['dkgame'];
    }

    if ($rawCookie === null || $rawCookie === '') {
        return null;
    }

    // COOKIE FORMAT:
    // {ID} {USERNAME} {PASSWORDHASH} {REMEMBERME}
    // Keep the legacy format for compatibility with existing accounts/sessions.
    $cookieParts = preg_split('/\s+/', trim($rawCookie));
    if (count($cookieParts) < 4) {
        ss_clear_dkgame_cookie();
        return false;
    }

    $userId = filter_var($cookieParts[0], FILTER_VALIDATE_INT);
    $username = (string) $cookieParts[1];
    $cookieHash = (string) $cookieParts[2];
    $rememberMe = (int) $cookieParts[3];

    if ($userId === false || $userId < 1 || $username === '' || $cookieHash === '') {
        ss_clear_dkgame_cookie();
        return false;
    }

    // The legacy database abstraction quotes/escapes input globally, so preserve
    // that compatibility contract here rather than changing query semantics yet.
    $query = doquery("SELECT * FROM {{table}} WHERE id='$userId' AND username='$username' LIMIT 1", "users");
    if (mysqli_num_rows($query) !== 1) {
        ss_clear_dkgame_cookie();
        return false;
    }

    $row = mysqli_fetch_array($query);
    if (!$row) {
        ss_clear_dkgame_cookie();
        return false;
    }

    $expectedHash = md5($row['password'] . '--' . $dbsettings['secretword']);
    if (!hash_equals($expectedHash, $cookieHash)) {
        ss_clear_dkgame_cookie();
        return false;
    }

    // Refresh the existing legacy cookie format, but set modern cookie attributes.
    $newcookie = $userId . ' ' . $username . ' ' . $cookieHash . ' ' . $rememberMe;
    $expiretime = $rememberMe === 1 ? time() + 31536000 : 0;

    setcookie('dkgame', $newcookie, [
        'expires'  => $expiretime,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    $GLOBALS['_SS_RAW_DKGAME'] = $newcookie;

    doquery("UPDATE {{table}} SET onlinetime=NOW() WHERE id='$userId' LIMIT 1", "users");

    return $row;
}

function ss_clear_dkgame_cookie() {
    setcookie('dkgame', '', [
        'expires'  => time() - 100000,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    unset($GLOBALS['_SS_RAW_DKGAME'], $_COOKIE['dkgame']);
}

?>
