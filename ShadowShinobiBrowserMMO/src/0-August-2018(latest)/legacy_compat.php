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
];

foreach ($legacyGetDefaults as $legacyGetKey) {
    if (!array_key_exists($legacyGetKey, $_GET)) {
        $_GET[$legacyGetKey] = '';
    }
}

// Keep the browser clean while preserving the full diagnostics in the
// container's PHP error log. Fatal errors still terminate the request normally.
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');

if (!isset($indexconteudo)) {
    $indexconteudo = '';
}

if (!isset($htmlnapag)) {
    $htmlnapag = '';
}
