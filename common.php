<?php
defined('IN')?:exit('not access');
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define('DEBUG', true);
define('PHP_DEV_VERSION', '5.5.28');
define('ENVIRONMENT', 'developed');
define('SYSTEM_DIR', getcwd());
define('ERROR_LOG_FILE', SYSTEM_DIR.'/logs/logs');

if (version_compare(PHP_VERSION, PHP_DEV_VERSION) < 0) {
    exit('I am at least PHP version '.PHP_DEV_VERSION.', my version: ' . PHP_VERSION . "\n");
}

function __autoload($class) {
    set_include_path("./api");
    $class = str_replace('\\', '/', $class) . '.php';
    require_once($class);
}

$wx['appId'] = '';
$wx['appSecret'] =  '';
$wx['token'] = '';
$wx['encodingAESKey'] = '';
