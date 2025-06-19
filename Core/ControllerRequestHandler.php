<?php

namespace App\Core;

class ControllerRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private Controller $controller,
        private string $action,
        private array $args
    )
    {
    }
    public function handle(Request $request): Response
    {
        $this->controller->setRequest($request);

        return call_user_func([$this->controller, $this->action], ...$this->args);
    }
}
