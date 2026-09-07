<?php
/**
 * Shadow route alias for the legacy standing/graduation implementation.
 *
 * Direct visits to /standing.php should open the standing screen instead of
 * falling through because the legacy endpoint requires ?do=graduacao.
 */
if (!isset($_GET['do']) || $_GET['do'] === '') {
    $_GET['do'] = 'graduacao';
}

require_once __DIR__ . '/graduacao.php';
