<?php

use App\Core\Database;
use App\Core\ServiceContainer;

$container = new ServiceContainer;

$container->set(Database::class, function ()
{
    return new Database($_ENV["DB_DRIVER"], $_ENV["DB_HOST"], $_ENV["DB_DBNAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
});

return $container;