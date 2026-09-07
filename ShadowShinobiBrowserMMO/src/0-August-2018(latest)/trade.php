<?php
// Shadow route alias: map the English trade action to the legacy handler.
if (isset($_GET['do']) && $_GET['do'] === 'trade') {
    $_GET['do'] = 'troca';
}
require_once __DIR__ . '/troca.php';
