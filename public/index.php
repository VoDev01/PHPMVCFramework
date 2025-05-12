<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use App\Core\Database;
use App\Core\ServiceContainer;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = new ServiceContainer;

$container->set(Database::class, function(){
    return new Database($_ENV["DB_DRIVER"], $_ENV["DB_HOST"], $_ENV["DB_DBNAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
});

$app = new \App\Core\Application(dirname(__DIR__), $container);

$app->run();