<?php

namespace App\Core;

/**
 * Maps views urls to some logic
 */
class Router
{
    /**
     * @var Request
     */
    public Request $request;
    /**
     * @var Response
     */
    public Response $response;

    /**
     * @var ViewRenderer
     */
    protected ViewRenderer $viewRenderer;

    /**
     * Stores all routes
     * @var array
     */
    protected array $routes = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->viewRenderer = new ViewRenderer($request, $response);
    }

    /**
     * Register route with http GET method
     * @param string $uri
     * @param callable|array|string $action
     * 
     * @return [type]
     */
    public function get(string $uri, callable|array|string $action)
    {
        $this->routes['get'][$uri] = $action;
    }

    /**
     * Register route with http POST method
     * @param string $uri
     * @param callable|array $action
     * 
     * @return [type]
     */
    public function post(string $uri, callable|array $action)
    {
        $this->routes['post'][$uri] = $action;
    }

    /**
     * 
     * @return [type]
     */
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
            echo $this->viewRenderer->renderView($action);
            return;
        }
        if(is_array($action))
        {
            $action[0] = new $action[0]($this->request, $this->response);
        }
        return call_user_func($action);
    }
}