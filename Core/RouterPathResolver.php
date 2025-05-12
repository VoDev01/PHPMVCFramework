<?php

namespace App\Core;

use ReflectionFunction;
use ReflectionMethod;

class RouterPathResolver
{
    public function __construct(private Router $router, private ServiceContainer $container)
    {
    }

    /**
     * Resolve url path with some controller and its action
     * @return [type]
     */
    public function resolve()
    {
        $path = $this->router->request->path();
        $method = $this->router->request->method();
        $action = $this->router->routes[$method][$path] ?? null;

        if (is_string($action))
        {
            echo $this->router->viewRenderer->renderView($action);
            return;
        }
        if (is_array($action) || $action === null)
        {
            $action = $this->router->match($action, $method, $path);
        }

        $closure = $action["closure"] ?? null;
        if (isset($action[0]) && isset($action[1]) && $closure === null)
        {
            $action[0] = $this->container->get($action[0]);
            $params = $this->getActionParameters($action[0]::class, $action[1], $action);
        }
        else if(isset($closure))
        {
            $params = $this->getActionParameters(null, $closure, $action);
        }
        else
        {
            echo "Not found";
            $this->router->response->setResponseCode(404);
            return;
        }
        return call_user_func($closure ?? [$action[0], $action[1]], ...$params);
    }

    private function getActionParameters(string|null $controller, string|callable $action, array $values)
    {
        if (isset($controller))
            $reflection = new ReflectionMethod($controller, $action);
        else
            $reflection = new ReflectionFunction($action);

        $params = [];

        $reflectionParameters = $reflection->getParameters();

        foreach ($reflectionParameters as $reflectionParameter)
        {
            $name = $reflectionParameter->getName();
            $params[$name] = $values[$name];
        }

        return $params;
    }
}
