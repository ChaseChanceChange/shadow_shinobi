<?php // login.php :: Handles logins and cookies.

include('lib.php');

$do = isset($_GET['do']) ? (string) $_GET['do'] : '';
if ($do === 'login' || $do === '') {
    login();
} elseif ($do === 'logout') {
    logout();
} elseif ($do === 'dev') {
    devlogin();
} else {
    login();
}

function login() {
    include('config.php');
    $link = opendb();

    if (isset($_POST['submit'])) {
        $username = isset($_POST['username']) ? trim((string) $_POST['username']) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';

        if ($username === '' || $password === '') {
            header('Location: login.php?do=login&conteudo=Please enter your account name and password.');
            die();
        }

        $passwordHash = md5($password);
        $query = doquery("SELECT * FROM {{table}} WHERE username='".$username."' AND password='".$passwordHash."' LIMIT 1", 'users');
        if (mysqli_num_rows($query) != 1) {
            header('Location: login.php?do=login&conteudo=Invalid username or password. Please try again.');
            die();
        }

        $usersqueryd = doquery("SELECT * FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '".(time()-61)."' AND username='".$username."' LIMIT 1", 'users');
        $row = mysqli_fetch_array($query);
        if ((mysqli_num_rows($usersqueryd) == 1) && (strtolower($username) != '220292') && ($row['ipadress'] != $_SERVER['REMOTE_ADDR'])) {
            header('Location: login.php?do=login&conteudo=Someone is already logged into your account. Please wait a minute and try again. If this persists, report it to a staff member.');
            die();
        }

        if (isset($_POST['rememberme'])) { $expiretime = time()+31536000; $rememberme = 1; } else { $expiretime = 0; $rememberme = 0; }
        $cookie = $row['id'] . ' ' . $row['username'] . ' ' . md5($row['password'] . '--' . $dbsettings['secretword']) . ' ' . $rememberme;
        setcookie('dkgame', $cookie, [
            'expires' => $expiretime,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $GLOBALS['_SS_RAW_DKGAME'] = $cookie;
        $_COOKIE['dkgame'] = $cookie;

        doquery("UPDATE {{table}} SET ipadress='".$_SERVER['REMOTE_ADDR']."' WHERE username='".$username."' AND password='".$passwordHash."' LIMIT 1", 'users');
        header('Location: index.php');
        die();
    }

    global $conteudouser, $devlogin;
    $conteudouser = isset($_GET['conteudo']) ? (string) $_GET['conteudo'] : '';
    $conteudouser = '<font color=brown><center>'.strip_tags($conteudouser).'</font></center><br>';
    $devlogin = getenv('DEV_MODE') === '1' ? '<p class="ss-login__dev"><a href="login.php?do=dev">Developer login · local only</a></p>' : '';
    $page = gettemplate('login');
    $title = 'Log In';
    display($page, $title, false, false, false, false);
}

function devlogin() {
    if (getenv('DEV_MODE') !== '1') {
        http_response_code(404);
        die('Not found.');
    }

    $devUsername = trim((string) (getenv('DEV_USERNAME') ?: 'Oyatsumi'));
    if ($devUsername === '') {
        http_response_code(500);
        die('Developer login is misconfigured.');
    }

    include('config.php');
    $link = opendb();
    $safeUsername = addslashes($devUsername);
    $query = doquery("SELECT * FROM {{table}} WHERE username='".$safeUsername."' LIMIT 1", 'users');
    $row = mysqli_fetch_array($query);

    if (!$row) {
        http_response_code(503);
        die('Configured developer character was not found in the database.');
    }

    // Session cookie (expires 0) is valid for the browser process; also keep
    // an in-request copy so the immediate redirect target can authenticate
    // even if a client mishandles Set-Cookie on 302.
    $cookie = $row['id'] . ' ' . $row['username'] . ' ' . md5($row['password'] . '--' . $dbsettings['secretword']) . ' 0';
    setcookie('dkgame', $cookie, [
        'expires' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    $GLOBALS['_SS_RAW_DKGAME'] = $cookie;
    $_COOKIE['dkgame'] = $cookie;

    doquery("UPDATE {{table}} SET ipadress='".addslashes($_SERVER['REMOTE_ADDR'])."' WHERE id='".(int) $row['id']."' LIMIT 1", 'users');
    header('Location: index.php');
    die();
}

function logout() {
    setcookie('dkgame', '', [
        'expires' => time()-100000,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    unset($GLOBALS['_SS_RAW_DKGAME'], $_COOKIE['dkgame'], $HTTP_SESSION_VARS);
    header('Location: login.php?do=login');
    die();
}

?>
