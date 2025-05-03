<?php

namespace App\Core;

class Router
{
    public Request $request;
    public Response $response;

    protected ViewRenderer $viewRenderer;

    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->viewRenderer = new ViewRenderer($request, $response);
    }

    public function get(string $uri, callable|array|string $action)
    {
        $this->routes['get'][$uri] = $action;
    }

    public function post(string $uri, callable|array $action)
    {
        $this->routes['post'][$uri] = $action;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $action = $this->routes[strtolower($method)][$path];
        if(!$action)
        {
            echo "Not found";
            $this->response->setResponseCode(404);
            return;
        }
        if(is_string($action))
        {
            return $this->viewRenderer->renderView($action);
        }
        if(is_array($action))
        {
            $instance = new $action[0];
            return call_user_func([$instance, $action[1]]);
        }
        return call_user_func($action);
    }
}