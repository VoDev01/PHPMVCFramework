<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;
use ErrorException;
use App\Core\Exceptions\PageNotFoundException;

class ErrorHandler
{
    public static function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleException(Throwable $e): void
    {
        if ($e instanceof PageNotFoundException)
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
    }
}
