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

    public function __get(string $name)
    {
        if(isset($this->{$name}))
            return $this->{$name};
        else
            return null;
    }

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

    public function matchPathWithPattern(string $method, string $path): array|bool
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
    public function getPatternFromPath(string $path): string
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