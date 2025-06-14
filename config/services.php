<?php

use App\Core\Database;
use App\Core\MVCTemplateViewRenderer;
use App\Core\PHPTemplateViewRenderer;
use App\Core\ServiceContainer;
use App\Core\TemplateViewRendererInterface;

$container = new ServiceContainer;

$container->set(Database::class, function ()
{
    return new Database($_ENV["DB_DRIVER"], $_ENV["DB_HOST"], $_ENV["DB_DBNAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
});

$container->set(TemplateViewRendererInterface::class, function(){
    return new MVCTemplateViewRenderer;
});

return $container;