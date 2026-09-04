<?php // cookies.php :: Handles cookies. (Mmm, tasty!)

function checkcookies() {

    include('config.php');

    $row = false;

    if (!isset($_COOKIE['dkgame'])) {
        return $row;
    }

    // COOKIE FORMAT:
    // {ID} {USERNAME} {PASSWORDHASH} {REMEMBERME}
    // Keep the legacy format for compatibility with existing accounts/sessions.
    $cookieParts = preg_split('/\s+/', trim((string) $_COOKIE['dkgame']));
    if (count($cookieParts) < 4) {
        return false;
    }

    $userId = filter_var($cookieParts[0], FILTER_VALIDATE_INT);
    $username = (string) $cookieParts[1];
    $cookieHash = (string) $cookieParts[2];
    $rememberMe = (int) $cookieParts[3];

    if ($userId === false || $userId < 1 || $username === '' || $cookieHash === '') {
        return false;
    }

    // The legacy database abstraction quotes/escapes input globally, so preserve
    // that compatibility contract here rather than changing query semantics yet.
    $query = doquery("SELECT * FROM {{table}} WHERE id='$userId' AND username='$username' LIMIT 1", "users");
    if (mysqli_num_rows($query) !== 1) {
        return false;
    }

    $row = mysqli_fetch_array($query);
    if (!$row) {
        return false;
    }

    $expectedHash = md5($row['password'] . '--' . $dbsettings['secretword']);
    if (!hash_equals($expectedHash, $cookieHash)) {
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

    doquery("UPDATE {{table}} SET onlinetime=NOW() WHERE id='$userId' LIMIT 1", "users");

    return $row;
}

?>