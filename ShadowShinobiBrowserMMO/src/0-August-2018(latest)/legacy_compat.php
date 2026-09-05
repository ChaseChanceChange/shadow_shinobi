<?php
// Shadow Shinobi PHP 8 compatibility boundary.
// Keep legacy entry points running while we modernize the game incrementally.

// The old engine reads many optional GET keys directly. Normalizing them here
// prevents PHP 8 undefined-key warnings from leaking into the rendered game.
$legacyGetDefaults = [
    'do',
    'conteudo',
    'latitude',
    'longitude',
    're',
    'resp',
    'lugarazul',
    'monstro',
    'item',
    'tamanho',
    'jogador',
    'id',
    'action',
    'ord',
    'nomechar',
    'page',
];

foreach ($legacyGetDefaults as $legacyGetKey) {
    if (!array_key_exists($legacyGetKey, $_GET)) {
        $_GET[$legacyGetKey] = '';
    }
}

$legacyPostDefaults = [
    'submit',
    'username',
    'password',
    'rememberme',
    'fala',
];

foreach ($legacyPostDefaults as $legacyPostKey) {
    if (!array_key_exists($legacyPostKey, $_POST)) {
        $_POST[$legacyPostKey] = '';
    }
}

// Keep browser output clean while preserving diagnostics in the PHP error log.
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');

if (!isset($indexconteudo)) {
    $indexconteudo = '';
}

if (!isset($htmlnapag)) {
    $htmlnapag = '';
}
