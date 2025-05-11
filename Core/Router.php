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
    protected array $routes = [
        'patterns' => [],
        'get' => [],
        'post' => []
    ];

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

    public function pattern(string $pattern)
    {
        array_push($this->routes['patterns'], $pattern);
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
     * Resolve url path with some controller and its action
     * @return [type]
     */
    public function resolve()
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $action = $this->routes[$method][$path];

        if (is_string($action))
        {
            echo $this->viewRenderer->renderView($action);
            return;
        }
        $actionMatch = $this->matchPathWithPattern($method, $path);
        if (!$actionMatch)
        {
            if (!isset($action))
            {
                echo "Not found";
                $this->response->setResponseCode(404);
                return;
            }
            $action[0] = new $action[0]($this->request, $this->response);
        }
        else
        {
            $controllerName = "\\App\\Controllers\\" . ucwords(strtolower($actionMatch['controller'])) . "Controller";
            $action[0] = new $controllerName($this->request, $this->response);
            $action[1] = strtolower($actionMatch['action']);
        }
        return call_user_func($action, $this->request);
    }
    protected function matchPathWithPattern(string $method, string $path): array|bool
    {
        $path = trim($path, "/");
        foreach ($this->routes['patterns'] as $route)
        {
            $pattern = $this->getPatternFromPath($route);

            if (preg_match($pattern, $path, $matches))
            {
                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);

                $bind = array_merge($matches, $this->routes[$method]);

                return $bind;
            }
        }
        return false;
    }
    protected function getPatternFromPath(string $path): string
    {
        $path = rawurldecode($path);

        $path = trim($path, "/");

        $segments = explode("/", $path);

        $segments = array_map(function (string $segment): string
        {
            if (preg_match("/^\{([a-z][a-z0-9]*)\}$/", $segment, $matches))
            {
                $segment = "(?<" . $matches[1] . ">[^\/]*)";
            }
            if (preg_match("/^\{([a-z][a-z0-9]*):(.+)\}$/", $segment, $matches))
            {
                $segment = "(?<" . $matches[1] . ">" . $matches[2] . ")";
            }
            return $segment;

        }, $segments);

        return "/^" . implode("\/", $segments) . "$/iu";
    }
}