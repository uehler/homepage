<?php
require_once('config.php');

if (ENV == 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

require_once('autoloader.php');

$piwikTracker = 'https://piwik.ehlertainment.de';
header("Content-Security-Policy: default-src 'self'; script-src 'self' " . $piwikTracker . " 'unsafe-inline'; style-src 'unsafe-inline'; img-src 'self' " . $piwikTracker);
header('Strict-Transport-Security: max-age=31536000');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1');
header('X-Content-Type-Options: nosniff');

session_start();

$kernel = new \App\Components\Kernel();
$kernel->load();