<?php
// Shadow route alias: map English actions onto the legacy training/contract handlers.
if (isset($_GET['do'])) {
    if ($_GET['do'] === 'discipline') {
        $_GET['do'] = 'treinamento';
    } elseif ($_GET['do'] === 'contracts') {
        $_GET['do'] = 'quests';
    }
}
require_once __DIR__ . '/treinamentoequests.php';
