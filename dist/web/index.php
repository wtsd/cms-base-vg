<?php

define('ROOT', dirname(__DIR__));

(@require_once(dirname(__DIR__) . '/vendor/autoload.php'))
    or die('File /vendor/autoload.php is missing. Run "$ composer install"');

$kernel = new wtsd\common\AppKernel();
$kernel->runWeb();