<?php
error_reporting(-1);
date_default_timezone_set('UTC');

$autoloader = require __DIR__ . '/../vendor/autoload.php';
$autoloader->add('PagarmeV2\\Tests\\', __DIR__);
