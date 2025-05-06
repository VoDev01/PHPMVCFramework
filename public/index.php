<?php

use Dotenv\Dotenv;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = new \App\Core\Application(dirname(__DIR__));

$app->run();