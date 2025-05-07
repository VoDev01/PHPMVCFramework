<?php

use App\Controllers\HomeController;

$this->app->router->get('/', [HomeController::class, 'home']);
$this->app->router->get('/catalog', [HomeController::class, 'catalog']);
$this->app->router->post('/catalog', [HomeController::class, 'catalogPost']);
$this->app->router->get('/register', [HomeController::class, 'register']);
$this->app->router->post('/register', [HomeController::class, 'registerPost']);