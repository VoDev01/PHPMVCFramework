<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new \App\Core\Application(new \App\Core\Request());

$app->router->get('/catalog', 'catalog');

$app->run();