<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

set_error_handler("App\Core\ErrorHandler::handleError");

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

set_exception_handler("App\Core\ErrorHandler::handleException");

$container = require_once __DIR__ . '/../config/services.php';

$app = new \App\Core\Application(dirname(__DIR__), $container);

$app->run();
