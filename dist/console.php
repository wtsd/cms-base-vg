<?php

define('DEBUG_MEMORY_START', memory_get_usage());
define('DEBUG_TIME_START', microtime(true));

define('ROOT', __DIR__);

(@require_once(__DIR__ . '/vendor/autoload.php'))
    or die('File /vendor/autoload.php is missing. Run "$ composer install"');


$kernel = new wtsd\common\AppKernel();
$kernel->runConsole($_SERVER['argv']);