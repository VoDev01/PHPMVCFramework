<?php

namespace App\Core;

class MiddlewareRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private array $middlewareStack,
        private ControllerRequestHandler $controllerHandler
    )
    {
    }

    public function handle(Request $request): Response
    {
        $middleware = array_shift($this->middlewareStack);

        if($middleware === null)
        {
            return $this->controllerHandler->handle($request);
        }

        return $middleware->handle($request, $this);
    }
}
