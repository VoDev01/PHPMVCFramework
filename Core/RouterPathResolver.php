<?php

namespace App\Core;

use ReflectionMethod;

class RouterPathResolver
{
    public function __construct(private Router $router)
    {}

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
        $actionMatch = $this->router->matchPathWithPattern($method, $path);
        if (!$actionMatch)
        {
            if (!isset($action))
            {
                echo "Not found";
                $this->router->response->setResponseCode(404);
                return;
            }
            $action[0] = new $action[0](new ViewRenderer);
        }
        else
        {
            $controllerName = "\\App\\Controllers\\" . ucwords(strtolower($actionMatch['controller'])) . "Controller";
            $action[0] = new $controllerName(new ViewRenderer);
            $action[1] = strtolower($actionMatch['action']);
        }
        $params = $this->getControllerActionParameters($action[0]::class, $action[1], $this->router->request->body());
        return call_user_func($action, $this->router->request);
    }

    private function getControllerActionParameters(string $controller, string $action, array $values)
    {
        $reflection = new ReflectionMethod($controller, $action);

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