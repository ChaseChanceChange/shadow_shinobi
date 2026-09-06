<?php
// register.php :: Shadow Shinobi account creation endpoint.

include('lib.php');
$link = opendb();
include('cookies.php');
$userrow = checkcookies();

function shadow_register_render(array $controlrow, array $values = array(), string $message = '') {
    $page = gettemplate('register');

    $defaults = array(
        'usernamevalue' => '',
        'password1value' => '',
        'password2value' => '',
        'email1value' => '',
        'email2value' => '',
        'charnamevalue' => '',
        'message' => ''
    );

    foreach ($defaults as $key => $default) {
        if (!array_key_exists($key, $values)) {
            $values[$key] = $default;
        }
    }

    if ($message !== '') {
        $values['message'] = '<div class="ss-form-message ss-form-message--error">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>';
    }

    $controlrow['verifytext'] = '';

    $page = parsetemplate($page, array_merge($controlrow, $values));
    display($page, 'Create Account', false, false, false);
}

function register_account() {
    global $link;

    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", 'control');
    $controlrow = mysqli_fetch_array($controlquery);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register_submit'])) {
        shadow_register_render($controlrow);
        return;
    }

    $username = trim(isset($_POST['username']) ? (string) $_POST['username'] : '');
    $password1 = isset($_POST['password1']) ? (string) $_POST['password1'] : '';
    $password2 = isset($_POST['password2']) ? (string) $_POST['password2'] : '';
    $email1 = trim(isset($_POST['email1']) ? (string) $_POST['email1'] : '');
    $email2 = trim(isset($_POST['email2']) ? (string) $_POST['email2'] : '');
    $charname = trim(isset($_POST['charname']) ? (string) $_POST['charname'] : '');
    $charclass = isset($_POST['charclass']) ? (int) $_POST['charclass'] : 1;
    $difficulty = isset($_POST['difficulty']) ? (int) $_POST['difficulty'] : 1;

    $values = array(
        'usernamevalue' => htmlspecialchars($username, ENT_QUOTES, 'UTF-8'),
        'password1value' => '',
        'password2value' => '',
        'email1value' => htmlspecialchars($email1, ENT_QUOTES, 'UTF-8'),
        'email2value' => htmlspecialchars($email2, ENT_QUOTES, 'UTF-8'),
        'charnamevalue' => htmlspecialchars($charname, ENT_QUOTES, 'UTF-8'),
    );

    $errors = array();

    if ($username === '') $errors[] = 'Account name is required.';
    if (strlen($username) > 30) $errors[] = 'Account name cannot contain more than 30 characters.';
    if ($username !== '' && preg_match('/[^A-Za-z0-9_-]/', $username)) $errors[] = 'Account name may only contain letters, numbers, underscores, and hyphens.';

    $usernameEscaped = addslashes($username);
    $usernamequery = doquery("SELECT username FROM {{table}} WHERE username='$usernameEscaped' LIMIT 1", 'users');
    if (mysqli_num_rows($usernamequery) > 0) $errors[] = 'That account name is already in use.';

    if ($charname === '') $errors[] = 'Operative Record Name is required.';
    if (strlen($charname) > 30) $errors[] = 'Operative Record Name cannot contain more than 30 characters.';
    if ($charname !== '' && preg_match('/[^A-Za-z0-9_-]/', $charname)) $errors[] = 'Operative Record Name may only contain letters, numbers, underscores, and hyphens.';

    $charnameEscaped = addslashes($charname);
    $characternamequery = doquery("SELECT charname FROM {{table}} WHERE charname='$charnameEscaped' LIMIT 1", 'users');
    if (mysqli_num_rows($characternamequery) > 0) $errors[] = 'That Operative Record Name is already in use.';

    if ($email1 === '' || $email2 === '') $errors[] = 'Email address is required.';
    if ($email1 !== $email2) $errors[] = 'Email addresses do not match.';
    if ($email1 !== '' && !is_email($email1)) $errors[] = 'Please enter a valid email address.';

    $emailEscaped = addslashes($email1);
    if ($email1 !== '') {
        $emailquery = doquery("SELECT email FROM {{table}} WHERE email='$emailEscaped' LIMIT 1", 'users');
        if (mysqli_num_rows($emailquery) > 0) $errors[] = 'That email address is already in use.';
    }

    if ($password1 === '') $errors[] = 'Password is required.';
    if (strlen($password1) > 10) $errors[] = 'Password cannot contain more than 10 characters.';
    if ($password1 !== '' && preg_match('/[^A-Za-z0-9_-]/', $password1)) $errors[] = 'Password may only contain letters, numbers, underscores, and hyphens.';
    if ($password1 !== $password2) $errors[] = 'Passwords do not match.';

    if (!in_array($charclass, array(1, 2, 3), true)) $charclass = 1;
    if (!in_array($difficulty, array(1, 2, 3), true)) $difficulty = 1;

    if (count($errors) > 0) {
        shadow_register_render($controlrow, $values, implode(' ', $errors));
        return;
    }

    $password = md5($password1);

    // Local Shadow Shinobi development is intentionally self-contained.
    // Verification remains represented by the legacy field, but no external
    // mail service is required to create a usable local development account.
    $verifycode = '1';

    $query = doquery(
        "INSERT INTO {{table}} SET id='',regdate=NOW(),verify='$verifycode',username='$usernameEscaped',password='$password',email='$emailEscaped',charname='$charnameEscaped',charclass='$charclass',difficulty='$difficulty'",
        'users'
    );

    if (!$query) {
        shadow_register_render($controlrow, $values, 'The account could not be created. Please check the database connection and try again.');
        return;
    }

    $safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $page = '<section class="ss-card ss-action-card">'
        . '<div class="ss-action-card__header">'
        . '<span class="ss-eyebrow">REGISTRATION COMPLETE</span>'
        . '<h2>Operative record created</h2>'
        . '<p>Your Shadow Shinobi account <strong>' . $safeUsername . '</strong> is ready for local play.</p>'
        . '</div>'
        . '<div class="ss-action-card__actions"><a href="login.php?do=login">Enter the world</a></div>'
        . '</section>';

    display($page, 'Registration Complete', false, false, false);
}

register_account();
