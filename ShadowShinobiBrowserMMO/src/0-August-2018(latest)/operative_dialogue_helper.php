<?php
/**
 * Canonical Shadow Shinobi operative-dialogue renderer.
 *
 * The English function name is the maintained implementation contract.
 * Legacy callers are supported through the personagemgeral.php compatibility shim.
 */
if (!function_exists('renderOperativeDialogue')) {
    function renderOperativeDialogue($message, $image = '', $speaker = 'Enclave Warden', $footer = ''): string
    {
        $speaker = htmlspecialchars((string)$speaker, ENT_QUOTES, 'UTF-8');
        $footer = (string)$footer;
        $message = (string)$message;

        return '<div class="ss-npc-card">'
            . '<div class="ss-npc-card__portrait">'
            . '<img src="layoutnovo/operative record/1.png" alt="Operative Record" />'
            . '</div>'
            . '<div class="ss-npc-card__body">'
            . '<div class="ss-eyebrow">SHADOW INTEL</div>'
            . '<strong class="ss-npc-card__speaker">' . $speaker . '</strong>'
            . '<div class="ss-npc-card__message">' . $message . '</div>'
            . ($footer !== '' ? '<div class="ss-npc-card__footer">' . $footer . '</div>' : '')
            . '</div>'
            . '</div>';
    }
}
?>
