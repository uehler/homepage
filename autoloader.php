<?php
$composerAutoloader = __DIR__ . '/vendor/autoload.php';
if (!file_exists($composerAutoloader)) {
    die('run <b>compser install</b>');
}

require_once($composerAutoloader);

spl_autoload_register(function ($className) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
});