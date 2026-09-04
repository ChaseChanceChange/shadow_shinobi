<?php
// Legacy PHP compatibility boundary for the Shadow Shinobi migration.
//
// The original game reads several optional request parameters directly from
// $_GET and also expects these two globals to exist during normal page flow.
// Supplying empty-string defaults preserves the old comparisons without
// emitting PHP 8+ undefined-key/undefined-variable warnings.

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
