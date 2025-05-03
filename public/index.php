<?php

use App\Controllers\HomeController;

require_once __DIR__.'/../vendor/autoload.php';

$app = new \App\Core\Application(dirname(__DIR__));

$app->router->get('/', [HomeController::class, 'home']);
$app->router->get('/catalog', 'catalog');

$app->run();