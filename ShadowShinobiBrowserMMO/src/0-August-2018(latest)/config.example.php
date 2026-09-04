<?php
// Shadow Shinobi - local/server configuration.
// Keep credentials OUT of source control. Environment variables are preferred.

$dbsettings = array(
    'server'     => getenv('DB_HOST') ?: '127.0.0.1',
    'user'       => getenv('DB_USER') ?: 'shadow',
    'pass'       => getenv('DB_PASSWORD') ?: 'shadow_dev_password',
    'name'       => getenv('DB_NAME') ?: 'shadow_shinobi',
    'prefix'     => getenv('DB_PREFIX') ?: 'dk',
    'secretword' => getenv('GAME_SECRET') ?: 'CHANGE_THIS_SECRET_BEFORE_PUBLIC_HOSTING'
);
?>
