<?php
/**
 * Global player-language output boundary.
 *
 * This file runs before legacy entry points. It preserves the existing PHP 8
 * compatibility prepend, then catches old HTML-entity encoded Portuguese and
 * runs the canonical Shadow terminology pass over the final response buffer.
 */
require_once __DIR__ . '/legacy_compat.php';
require_once __DIR__ . '/i18n.php';

ob_start(function ($buffer) {
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

    return ui_en(strtr($buffer, $entityMap));
});
?>
