<?php
// Local development diagnostics. Never expose this endpoint with DEV_MODE=0.
if (getenv('DEV_MODE') !== '1') {
    http_response_code(404);
    exit('Not found.');
}

require_once __DIR__ . '/lib.php';
require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=UTF-8');

$link = opendb();

$checks = [];

function dev_check_db($label, $sql, $link) {
    $result = mysqli_query($link, $sql);
    return [
        'label' => $label,
        'ok' => $result !== false,
        'detail' => $result !== false ? 'OK' : mysqli_error($link),
    ];
}

$checks[] = dev_check_db('Database connection', 'SELECT 1', $link);
$checks[] = dev_check_db('Users table', 'SELECT id FROM dk_users LIMIT 1', $link);
$checks[] = dev_check_db('Towns table', 'SELECT id FROM dk_towns LIMIT 1', $link);
$checks[] = dev_check_db('Control table', 'SELECT id FROM dk_control WHERE id=1 LIMIT 1', $link);
$checks[] = dev_check_db('Drops table', 'SELECT id FROM dk_drops LIMIT 1', $link);

$devUsername = trim((string) (getenv('DEV_USERNAME') ?: 'Oyatsumi'));
$userResult = mysqli_query($link, "SELECT id, username, charname, level, currentaction FROM dk_users WHERE username='" . addslashes($devUsername) . "' LIMIT 1");
$user = $userResult ? mysqli_fetch_assoc($userResult) : null;
$checks[] = [
    'label' => 'Developer character',
    'ok' => $user !== null,
    'detail' => $user ? ($user['username'] . ' / ' . ($user['charname'] ?: '(unnamed)') . ' / level ' . $user['level']) : 'Configured developer user was not found',
];

$allOk = true;
foreach ($checks as $check) {
    if (!$check['ok']) {
        $allOk = false;
        break;
    }
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Shadow Shinobi · Developer Health</title>
<style>
body{font-family:system-ui,-apple-system,Segoe UI,sans-serif;background:#0b0e13;color:#e9edf4;margin:0;padding:32px}
main{max-width:820px;margin:auto;background:#151a22;border:1px solid #2a3340;border-radius:14px;padding:28px}
h1{margin-top:0}.status{font-size:1.1rem;margin-bottom:22px}
table{width:100%;border-collapse:collapse}td{padding:12px 10px;border-top:1px solid #2a3340}
.ok{font-weight:700}.bad{font-weight:700}.meta{color:#9aa6b5}
a{color:#a9c7ff}
</style>
</head>
<body>
<main>
<h1>Shadow Shinobi · Developer Health</h1>
<div class="status"><?php echo $allOk ? '✓ Core checks passing' : '✗ One or more core checks failed'; ?></div>
<table>
<?php foreach ($checks as $check): ?>
<tr>
<td class="<?php echo $check['ok'] ? 'ok' : 'bad'; ?>"><?php echo $check['ok'] ? 'PASS' : 'FAIL'; ?></td>
<td><?php echo htmlspecialchars($check['label'], ENT_QUOTES, 'UTF-8'); ?></td>
<td class="meta"><?php echo htmlspecialchars($check['detail'], ENT_QUOTES, 'UTF-8'); ?></td>
</tr>
<?php endforeach; ?>
</table>
<p><a href="login.php?do=dev">Developer login</a> · <a href="index.php">Game</a></p>
</main>
</body>
</html>
