<?php
/**
 * Shadow Shinobi compatibility helper for legacy NPC/guide cards.
 *
 * The original engine expected personagemgeral() to exist globally. Several
 * legacy pages still call it, so keep the contract while rendering a native
 * Shadow-styled card instead of restoring the old franchise presentation.
 */
if (!function_exists('personagemgeral')) {
    function personagemgeral($message, $image = '', $speaker = 'Enclave Warden', $footer = '') {
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
