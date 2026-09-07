<?php
// Shadow Shinobi PHP 8 compatibility boundary.
// Keep legacy entry points running while we modernize the game incrementally.

// Capture the auth cookie before any entry point mutates $_COOKIE
// (lib.php applies addslashes/htmlspecialchars to superglobals).
if (isset($_COOKIE['dkgame']) && is_string($_COOKIE['dkgame'])) {
    $GLOBALS['_SS_RAW_DKGAME'] = $_COOKIE['dkgame'];
}

// The maintained implementation is English-first, but the active legacy
// pages still call the historical entry point. Load the shim, which in turn
// loads renderOperativeDialogue().
require_once __DIR__ . '/personagemgeral.php';

// Some legacy templates expect this item-hover helper to exist globally.
// The original helper was not part of the current active include chain, so
// provide a safe compatibility fallback until that legacy presentation layer
// is fully retired. Returning an empty handler preserves page functionality
// without inventing item data or executing unsafe JavaScript.
if (!function_exists('conteudoexplic')) {
    function conteudoexplic($itemId, $itemType, $targetId, $durability = ''): string
    {
        return '';
    }
}

$legacyGetDefaults = [
    'do2',
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
    'qual',
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

// Common legacy template / include variables that are often written only on some branches.
if (!isset($indexconteudo)) {
    $indexconteudo = '';
}

if (!isset($htmlnapag)) {
    $htmlnapag = '';
}

if (!isset($fim)) {
    $fim = '';
}

if (!isset($fimh)) {
    $fimh = '';
}

if (!isset($conteudo)) {
    $conteudo = '';
}

if (!isset($opcoesnovas)) {
    $opcoesnovas = '';
}

if (!isset($drop)) {
    $drop = '';
}
