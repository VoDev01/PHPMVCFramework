<?php

namespace App\Core;

use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

class RouterPathResolver
{
    public function __construct(private Router $router)
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
        $action = $this->router->routes[$method][$path];

        if (is_string($action))
        {
            echo $this->router->viewRenderer->renderView($action);
            return;
        }
        if (is_array($action) || $action === null)
        {
            $action = $this->router->match($action, $method, $path);
        }

        $closure = $action["closure"];
        if (isset($action[0]) && isset($action[1]))
        {
            $action[0] = $this->getObject($action[0]);
            $params = $this->getActionParameters($action[0]::class, $action[1], $action);
        }
        else
        {
            $params = $this->getActionParameters(null, $closure, $action);
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

    private function getObject(string $className): object
    {
        $reflector = new ReflectionClass($className);
        $constructor = $reflector->getConstructor();
        $dependecies = [];

        if ($constructor === null)
        {
            return new $className;
        }

        foreach ($constructor->getParameters() as $parameter)
        {
            $type = (string) $parameter->getType();

            $dependecies[] = $this->getObject($type);
        }

        return new $className(...$dependecies);
    }
}
