<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\PageNotFoundException;
use ReflectionFunction;
use ReflectionMethod;
use UnexpectedValueException;

class RouterPathResolver
{
    public function __construct(private Router $router, private ServiceContainer $container, private array $middlewares)
    {
    }

    /**
     * Resolve url path with some controller and its action
     * @return [type]
     */
    public function resolve(TemplateViewRendererInterface $viewRenderer): Response
    {
        $path = $this->router->request->path();
        $method = $this->router->request->method();
        $action = $this->router->routes[$method][$path] ?? null;

        if (is_string($action))
        {
            $this->router->response->setBody($this->router->viewRenderer->renderView($action));
            return $this->router->response;
        }
        if (is_array($action) || $action === null)
        {
            $action = $this->router->match($action, $method, $path);

            if (isset($action) && !isset($action['closure']))
            {
                $action['request'] = $this->router->request;
                $action['controller'] = $this->container->get($action['controller']);
                $action['controller']->setResponse($this->router->response);
                $action['controller']->setViewRenderer($viewRenderer);
                $params = $this->getActionParameters($action['controller']::class, $action['action'], $action);
            }
            else if (isset($action['closure']))
            {
                $params = $this->getActionParameters(null, $action['closure'], $action);
            }
            else
            {
                throw new PageNotFoundException("No route matched for '$path' with method '" . strtoupper($method) . "'");
                $this->router->response->setResponseCode(404);
                return $this->router->response;
            }
        }

        if (isset($action['closure']))
            return call_user_func($action['closure'], ...$params);
        else
        {
            $controllerHandler = new ControllerRequestHandler($action['controller'], $action['action'], $params);

            $middleware = $this->getMiddleware($action);

            $middlewareHandler = new MiddlewareRequestHandler($middleware, $controllerHandler);

            return $middlewareHandler->handle($this->router->request);
        }
    }

    private function getMiddleware(array $params): array
    {
        if(!array_key_exists("middleware", $params))
        {
            return [];
        }

        $middleware = explode(", ", $params["middleware"]);

        array_walk($middleware, function(&$value) {
            if(!array_key_exists($value, $this->middlewares))
            {
                throw new UnexpectedValueException("Middleware '$value' not found in config settings");
            }
            $value = $this->container->get($this->middlewares[$value]);
        });

        return $middleware;
    }

    private function getActionParameters(string|null $controller, string|callable $action, array $values)
    {
        if (isset($controller))
        {
            if (!method_exists($controller, $action) && !(bool)$_ENV['APP_SHOW_ERRORS'])
            {
                throw new PageNotFoundException("No route matched for '{$this->router->request->path()}'");
                $this->router->response->setResponseCode(404);
                return;
            }
            $reflection = new ReflectionMethod($controller, $action);
        }
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
