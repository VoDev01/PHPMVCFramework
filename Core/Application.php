<?php

namespace App\Core;

class Application 
{
    public Request $request;
    public Router $router;

    public function __construct(Request $request)
    {
        $this->router = new Router($request);
    }

    public function run()
    {
        return $this->router->resolve();
    }
}