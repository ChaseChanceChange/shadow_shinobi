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

    // Legacy source contains both UTF-8 text and older mojibake/unaccented text.
    // Normalize common player-facing messages before the canonical terminology pass.
    $legacyMessages = array(
        '/Voc[\xC3\xA9?]\\s+nao\\s+pode\\s+fazer\\s+nenhum\\s+movimento\\s+enquanto\\s+estiver\\s+em\\s+um\\s+duelo/i' => 'You cannot move while a Challenge is active',
        '/Voce\\s+nao\\s+pode\\s+fazer\\s+nenhum\\s+movimento\\s+enquanto\\s+estiver\\s+em\\s+um\\s+duelo/i' => 'You cannot move while a Challenge is active',
        '/Voce\\s+so\\s+pode\\s+acessar\\s+essa\\s+funcao\\s+dentro\\s+de\\s+uma\\s+cidade/i' => 'This service is only available inside a Settlement',
        '/Voc[\\xC3\\xA9?]\\s+so\\s+pode\\s+acessar\\s+essa\\s+funcao\\s+dentro\\s+de\\s+uma\\s+cidade/i' => 'This service is only available inside a Settlement',
        '/Voce\\s+nao\\s+pode\\s+retirar\\s+mais\\s+que\\s+99999\\s+Ryou/i' => 'You cannot withdraw more than 99,999 Coin',
        '/Voce\\s+nao\\s+pode\\s+retirar\\s+mais\\s+que\\s+seu\\s+dinheiro\\s+no\\s+banco/i' => 'You cannot withdraw more Coin than your Vault balance',
        '/Voce\\s+depositou\\s+(.+?)\\s+Ryou\\s+e\\s+retirou\\s+(.+?)\\s+Ryou\\s+do\\s+Banco/i' => 'You deposited $1 Coin and withdrew $2 Coin from the Vault',
        '/Nao\\s+existe\\s+nenhum\\s+Jogador\\s+com\\s+esse\\s+Nome/i' => 'No operative with that name exists',
        '/Voce\\s+nao\\s+pode\\s+doar\\s+item\\s+para\\s+voce\\s+mesmo/i' => 'You cannot transfer an item to yourself',
        '/Voce\\s+nao\\s+pode\\s+doar\\s+Itens/i' => 'You cannot transfer Gear',
        '/Voce\\s+nao\\s+possui\\s+espacos\\s+livres\\s+na\\s+sua\\s+Mochila/i' => 'You have no free Pack space',
        '/Voce\\s+nao\\s+possui\\s+Slots\\s+Livres\\s+para\\s+Equipar/i' => 'You have no free Gear slots',
        '/Voce\\s+deve\\s+selecionar\\s+um\\s+Jutsu\\s+primeiro/i' => 'Select an Art first',
        '/Voce\\s+ainda\\s+nao\\s+aprendeu\\s+esse\\s+Jutsu/i' => 'You have not learned this Art yet',
        '/Voce\\s+nao\\s+tem\\s+chakra\\s+suficiente\\s+para\\s+usar/i' => 'You do not have enough Essence to use',
        '/Voce\\s+morreu/i' => 'You were defeated',
        '/Voce\\s+aprendeu\\s+um\\s+novo\\s+Jutsu/i' => 'You learned a new Art',
        '/Voce\\s+passou\\s+de\\s+n.vel/i' => 'You advanced your Standing',
        '/Voce\\s+usou:/i' => 'You used:',
        '/Seu\\s+jutsu\\s+foi\\s+super\\s+efetivo/i' => 'Your Art was highly effective',
        '/Seu\\s+jutsu\\s+foi\\s+pouco\\s+efetivo/i' => 'Your Art was less effective',
        '/Escolha\\s+um\\s+Jutsu/i' => 'Choose an Art',
        '/Pontos\\s+de\\s+Vi.?a/i' => 'Health',
        '/Pontos\\s+de\\s+Chakra/i' => 'Essence',
        '/Pontos\\s+de\\s+Viagem/i' => 'Travel Points',
        '/Pontos\\s+de\\s+Distribui.?/i' => 'Allocation Points',
        '/Mochila\\s+Slot/i' => 'Pack Slot',
        '/Nenhum\\s+Item/i' => 'No Gear',
        '/Itens\\s+no\\s+Banco/i' => 'Gear in Vault',
        '/Deletar\\s+Item/i' => 'Delete Gear',
        '/Nao\\s+Deletar\\s+Item/i' => 'Keep Gear',
        '/Depositar\\s+Item/i' => 'Deposit Gear',
        '/Retirar\\s+Item/i' => 'Withdraw Gear',
        '/Enviar\\s+Para\\s+Mochila/i' => 'Send to Pack',
        '/Doar\\s+Item\\s+Para\\s*\\(Jogador\\)/i' => 'Transfer Gear To (Operative)',
        '/Ryou\\s+no\\s+Banco/i' => 'Coin in Vault',
        '/Mochila/i' => 'Pack',
        '/Banco/i' => 'Vault',
        '/Jogador/i' => 'Operative',
        '/Jutsu/i' => 'Art',
        '/Chakra/i' => 'Essence',
        '/Ryou/i' => 'Coin',
        '/Duelo/i' => 'Challenge',
        '/Vila\\s+da\\s+Folha/i' => 'Blackleaf Enclave',
        '/Vila\\s+da\\s+Areia/i' => 'Duneshade Enclave',
        '/Vila\\s+da\\s+N.v..?o/i' => 'Mist Enclave',
        '/Konoha/i' => 'Blackleaf Enclave',
        '/Hokage/i' => 'Warden',
        '/Mizukage/i' => 'Warden',
        '/Senjutsu/i' => 'Essence Discipline',
        '/Byakugan/i' => 'Essence Sight',
    );

    foreach ($legacyMessages as $pattern => $replacement) {
        $buffer = preg_replace($pattern, $replacement, $buffer);
    }

    // Remove legacy franchise-specific presentation hooks and branding from
    // the player response without renaming internal compatibility code yet.
    $buffer = preg_replace(
        '#<img[^>]+naruto[.]jpg[^>]*>#i',
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
