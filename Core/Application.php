<?php

namespace App\Core;

/**
 * Base class for running all services
 */
class Application 
{
    /**
     * @var string Specifies root directory of the project
     */
    public static string $ROOT_DIR;
    /**
     * @var Request
     */
    public Request $request;
    /**
     * @var Response
     */
    public Response $response;

    /**
     * @var Router
     */
    public Router $router;
    /**
     * @var RouteMapper
     */
    protected RouteMapper $routeMapper;

    /**
     * @param string $ROOT_DIR
     */
    public function __construct(string $ROOT_DIR)
    {
        self::$ROOT_DIR = $ROOT_DIR;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->routeMapper = new RouteMapper($this);
    }

    /**
     * @return [type]
     */
    public function run()
    {
        $this->routeMapper->map();
        return $this->router->resolve();
    }
}