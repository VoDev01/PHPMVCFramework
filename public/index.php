<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use App\Core\Database;
use App\Core\ServiceContainer;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

set_exception_handler(function (Throwable $e)
{
    if ($_ENV["APP_SHOW_ERRORS"] === "1")
        ini_set("display_errors", "1");
    else if ($_ENV["APP_SHOW_ERRORS"] === "0")
    {
        ini_set("display_errors", "0");

        ini_set("log_errors", "1");

        echo ini_get("error_log");

        require __DIR__ . "/../views/errors/500.php";
    }

    throw $e;
});

$container = new ServiceContainer;

$container->set(Database::class, function ()
{
    return new Database($_ENV["DB_DRIVER"], $_ENV["DB_HOST"], $_ENV["DB_DBNAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
});

$app = new \App\Core\Application(dirname(__DIR__), $container);

$app->run();
