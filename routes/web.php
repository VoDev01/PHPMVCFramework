<?php

use App\Controllers\HomeController;
use App\Controllers\ProductsController;

$this->app->router->get('/', [HomeController::class, 'home']);
$this->app->router->get('/catalog', [HomeController::class, 'catalog']);
$this->app->router->post('/catalog', [HomeController::class, 'catalogPost']);
$this->app->router->get('/register', [HomeController::class, 'register']);
$this->app->router->post('/register', [HomeController::class, 'registerPost']);
$this->app->router->pattern('/products/{id:\d+}/delete', [ProductsController::class, 'delete', 'delete']);
$this->app->router->pattern("/home/catalog/{name:\w+}",[HomeController::class, 'catalog']);
$this->app->router->pattern("/{controller}/{id:\d+}/{action}");
$this->app->router->pattern('/{controller}/{action}');