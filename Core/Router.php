<?php

declare(strict_types=1);

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
     * @var PHPTemplateViewRenderer
     */
    protected PHPTemplateViewRenderer $viewRenderer;

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
        if (isset($this->{$name}))
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
        $this->viewRenderer = new PHPTemplateViewRenderer($request, $response);
    }

    public function pattern(string $pattern, callable|array $action = null)
    {
        if (is_array($action))
            $this->routes['patterns'][$pattern] = [
                "controller" => $action[0] ?? null,
                "action" => $action[1] ?? null,
                "method" => $action[2] ?? null,
                "middleware" => $action["middleware"] ?? null
            ];
        else if (is_callable($action))
            $this->routes['patterns'][$pattern] = ["closure" => $action, "middleware" => $action["middleware"] ?? null];
        else
            array_push($this->routes['patterns'], $pattern);
    }

    private function actionToAssociativeArray(mixed $action): array|null
    {
        $namedAction = [];
        if (is_array($action))
        {
            $namedAction['controller'] = $action[0];
            $namedAction['action'] = $action[1];
        }
        else
            $namedAction = null;
        return $namedAction;
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
        $this->routes['get'][$uri] = $this->actionToAssociativeArray($action) ?? $action;
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
        $this->routes['post'][$uri] = $this->actionToAssociativeArray($action) ?? $action;
    }

    public function put(string $uri, callable|array $action)
    {
        $this->routes['put'][$uri] = $this->actionToAssociativeArray($action) ?? $action;
    }

    public function patch(string $uri, callable|array $action)
    {
        $this->routes['patch'][$uri] = $this->actionToAssociativeArray($action) ?? $action;
    }

    public function delete(string $uri, callable|array $action)
    {
        $this->routes['delete'][$uri] = $this->actionToAssociativeArray($action) ?? $action;
    }

    public function match(array|null $action, string $method, string $path)
    {
        $actionMatch = $this->matchPathWithPattern($method, $path);
        if (!$actionMatch)
        {
            return $action;
        }
        else
        {
            if (isset($actionMatch["closure"]))
            {
                $action["closure"] = $actionMatch["closure"];
                $action = array_merge($action, $actionMatch);
                return $action;
            }

            $matchNameToClassName = "App\\Controllers\\" . ucwords($actionMatch['controller']) . "Controller";
            $matchNameExists = class_exists($matchNameToClassName);

            $action = $action == null ? $actionMatch : array_merge($action, $actionMatch);
            $action['controller'] = $matchNameExists ? $matchNameToClassName : $actionMatch['controller'];
            $action['action'] = lcfirst(ucwords($actionMatch['action']));
            return $action;
        }
    }
    protected function matchPathWithPattern(string $method, string $path): array|bool
    {
        $path = trim($path, "/");
        foreach ($this->routes['patterns'] as $patternKey => $patternAction)
        {
            $pattern = $this->getPatternFromPath(is_array($patternAction) ? $patternKey : $patternAction);

            if (preg_match($pattern, $path, $matches))
            {
                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);

                $bind = array_merge($matches, $this->routes[$method], is_array($patternAction) ? array_filter($patternAction) : []);

                if (array_key_exists($method, $this->routes))
                {
                    if (is_array($patternAction))
                    {
                        if(isset($patternAction["method"]))
                        {
                            if ($method !== $patternAction["method"])
                                continue;
                        }
                    }
                    if (array_key_exists($path, $this->routes[$method]))
                    {
                        continue;
                    }
                }

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
