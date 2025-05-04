<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new \App\Core\Application(dirname(__DIR__));

$app->run();