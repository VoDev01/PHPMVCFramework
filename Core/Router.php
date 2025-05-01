<?php

namespace App\Core;

class Router
{

    public Request $request;
    protected array $routes = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(string $uri, callable|array|string $action)
    {
        $this->routes['get'][$uri] = $action;
    }

    public function post(string $uri, callable|array $action)
    {

    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $action = $this->routes[strtolower($method)][$path];
        if(!$action)
        {
            echo "Not found";
            http_response_code(404);
            return;
        }
        if(is_string($action))
        {
            include_once __DIR__."/../Views/$action.php";
            return;
        }
        call_user_func($action);
    }
}