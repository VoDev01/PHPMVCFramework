<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\PageNotFoundException;
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
            $action['request'] = new Request;

            if (isset($action) && !isset($action['closure']))
            {
                $action['controller'] = $this->container->get($action['controller']);
                $params = $this->getActionParameters($action['controller']::class, $action['action'], $action);
            }
            else if (isset($action['closure']))
            {
                $params = $this->getActionParameters(null, $action['closure'], $action);
            }
            else
            {
                throw new PageNotFoundException("No route matched for '$path'");
                $this->router->response->setResponseCode(404);
                return;
            }
        }
        return call_user_func($action['closure'] ?? [$action['controller'], $action['action']], ...$params);
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
