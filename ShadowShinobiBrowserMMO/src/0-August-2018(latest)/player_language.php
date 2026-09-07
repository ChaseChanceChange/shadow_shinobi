<?php
/**
 * Global player-language output boundary.
 *
 * This file runs before legacy entry points. It preserves the existing PHP 8
 * compatibility prepend, catches old HTML-entity encoded Portuguese, and
 * removes remaining legacy presentation markers before the Shadow pass.
 */
require_once __DIR__ . '/legacy_compat.php';
require_once __DIR__ . '/i18n.php';

ob_start(function ($buffer) {
    // The legacy admin renderer may gzip its output. Never reinterpret binary data.
    if (strncmp($buffer, chr(31) . chr(139), 2) === 0) {
        return $buffer;
    }

    $entityMap = array(
        'Voc&ecirc; est&aacute; explorando o mapa.' => 'You are exploring the world.',
        'voc&ecirc; est&aacute; explorando o mapa.' => 'you are exploring the world.',
        'Voc&ecirc; est&aacute; em uma batalha.' => 'You are in combat.',
        'voc&ecirc; est&aacute; em uma batalha.' => 'you are in combat.',
        'Voc&ecirc; chegou em seu destino.' => 'You reached your destination.',
        'voc&ecirc; chegou em seu destino.' => 'you reached your destination.',
        'Bem-vindo a(o) ' => 'Welcome to ',
        'bem-vindo a(o) ' => 'welcome to ',
        'Pontos de Vida' => 'Health',
        'Pontos de Chakra' => 'Essence',
        'Pontos de Experi&ecirc;ncia' => 'Insight',
        'Pontos de Experiência' => 'Insight',
        'Poder de Ataque' => 'Attack Power',
        'Poder de Defesa' => 'Defense Power',
        'N&iacute;vel' => 'Standing',
        'N&iacute;veis' => 'Standing',
        'Informa&ccedil;&otilde;es' => 'Details',
        'Informa&ccedil;&atilde;o' => 'Details',
    );

    $buffer = strtr($buffer, $entityMap);

    // Remove legacy franchise-specific presentation hooks and branding from
    // the player response without renaming internal compatibility code yet.
    $buffer = preg_replace(
        '#<img\\b[^>]*\\bsrc=["\\\'](?:[^"\\\']*/)?images/naruto\\.jpg["\\\'][^>]*>#i',
        '',
        $buffer
    );
    $buffer = str_replace(
        array(
            'xml:lang="pt" lang="pt"',
            'xml:lang="pt-br" lang="pt-br"',
            'id="naruto"'
        ),
        array(
            'xml:lang="en" lang="en"',
            'xml:lang="en" lang="en"',
            'id="shadow-status"'
        ),
        $buffer
    );

    return ui_en($buffer);
});
?>
