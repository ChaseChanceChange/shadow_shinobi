<?php
// Source-level PHP 8 compatibility boundary for the legacy game.
// Optional request keys used by the original entry points are normalized to
// empty strings so missing GET parameters do not emit PHP 8 undefined-key warnings.

$legacyGetDefaults = [
    'conteudo',
    'latitude',
    'longitude',
    're',
    'resp',
    'lugarazul',
    'monstro',
    'item',
    'tamanho',
];

foreach ($legacyGetDefaults as $legacyGetKey) {
    if (!array_key_exists($legacyGetKey, $_GET)) {
        $_GET[$legacyGetKey] = '';
    }
}

if (!isset($indexconteudo)) {
    $indexconteudo = '';
}

if (!isset($htmlnapag)) {
    $htmlnapag = '';
}
