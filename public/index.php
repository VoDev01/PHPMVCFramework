<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use App\Core\Database;
use App\Core\ServiceContainer;

require_once __DIR__ . '/../vendor/autoload.php';

set_error_handler(function (
    int $errno,
    string $errstr,
    string $errfile,
    int $errline
): bool
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


set_exception_handler(function (Throwable $e)
{
    if ($e instanceof \App\Core\Exceptions\PageNotFoundException)
    {
        http_response_code(404);

        $template = "404.php";
    }
    else
    {
        http_response_code(500);

        $template = "500.php";
    }
    if ($_ENV["APP_SHOW_ERRORS"] === "1" || $_ENV["APP_SHOW_ERRORS"] === true)
        ini_set("display_errors", "1");
    else if ($_ENV["APP_SHOW_ERRORS"] === "0" || $_ENV["APP_SHOW_ERRORS"] === false)
    {
        ini_set("display_errors", "0");

        ini_set("log_errors", "1");

        echo ini_get("error_log");

        require __DIR__ . "/../views/errors/$template";
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
