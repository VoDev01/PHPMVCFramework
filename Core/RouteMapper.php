<?php

namespace App\Core;

/**
 * Loads routes are specified in routes folder
 */
class RouteMapper
{
    /**
     * @var Application
     */
    protected Application $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Defines route files that need to be included
     * @return [type]
     */
    public function map()
    {
        $this->resolveRoutePath("web");
    }

    /**
     * Includes route files located in routes directory
     * @param string $routeName
     * 
     * @return [type]
     */
    private function resolveRoutePath(string $routeName)
    {
        include_once Application::$ROOT_DIR."/routes/$routeName.php";
        return;
    }
}