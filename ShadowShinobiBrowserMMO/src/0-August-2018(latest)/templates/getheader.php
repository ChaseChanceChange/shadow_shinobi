<?php
// Tracks the current page for online-player/status displays.
function curPageURL() {
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
    $port = (int) ($_SERVER['SERVER_PORT'] ?? ($https ? 443 : 80));
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

    $defaultPort = $https ? 443 : 80;
    if ($port !== $defaultPort && strpos($host, ':') === false) {
        $host .= ':' . $port;
    }

    return $scheme . '://' . $host . $requestUri;
}

global $userrow;

if (is_array($userrow) && isset($userrow['charname'])) {
    $currentUrl = curPageURL();
    $path = parse_url($currentUrl, PHP_URL_PATH);
    $query = parse_url($currentUrl, PHP_URL_QUERY);
    $page = ltrim((string) $path, '/');
    if ($query !== null && $query !== '') {
        $page .= '?' . $query;
    }

    // The legacy abstraction is retained here so existing online-status behavior
    // remains unchanged while request-server variables are PHP 8 safe.
    doquery("UPDATE {{table}} SET pagina='" . $page . "' WHERE charname='" . $userrow['charname'] . "' LIMIT 1", "users");
}

?>
