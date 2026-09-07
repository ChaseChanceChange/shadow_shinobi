<?php
/**
 * Legacy compatibility shim for the retired Portuguese helper name.
 *
 * The maintained implementation now lives in operative_dialogue_helper.php
 * under the English renderOperativeDialogue() contract.
 */
require_once __DIR__ . '/operative_dialogue_helper.php';

if (!function_exists('personagemgeral')) {
    function personagemgeral($message, $image = '', $speaker = 'Enclave Warden', $footer = ''): string
    {
        return renderOperativeDialogue($message, $image, $speaker, $footer);
    }
}
?>
