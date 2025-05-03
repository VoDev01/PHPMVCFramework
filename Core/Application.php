<?php

namespace App\Core;

class Application 
{
    public static string $ROOT_DIR;
    public Request $request;
    public Response $response;
    public Router $router;

    public function __construct(string $ROOT_DIR)
    {
        self::$ROOT_DIR = $ROOT_DIR;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
        return $this->router->resolve();
    }
}