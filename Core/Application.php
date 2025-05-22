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
    protected RouterPathResolver $routerPathResolver;
    
    public Database $database;

    protected ServiceContainer $container;

    /**
     * @param string $ROOT_DIR
     */
    public function __construct(string $ROOT_DIR, ServiceContainer $container)
    {
        self::$ROOT_DIR = $ROOT_DIR;
        $this->request = Request::createFromGlobals();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->routeMapper = new RouteMapper($this);
        $this->database = $container->get(Database::class);
        $this->container = $container;
        $this->routerPathResolver = new RouterPathResolver($this->router, $this->container);
    }

    /**
     * @return [type]
     */
    public function run()
    {
        $this->routeMapper->map();
        return $this->routerPathResolver->resolve($this->request);
    }
}
