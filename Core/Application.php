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

    protected array $middleware;

    /**
     * @param string $ROOT_DIR
     */
    public function __construct(string $ROOT_DIR, ServiceContainer $container, array $middleware)
    {
        self::$ROOT_DIR = $ROOT_DIR;
        $this->request = Request::createFromGlobals();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->routeMapper = new RouteMapper($this);
        $this->database = $container->get(Database::class);
        $this->container = $container;
        $this->middleware = $middleware;
        $this->routerPathResolver = new RouterPathResolver($this->router, $this->container, $this->middleware);
    }

    /**
     * @return [type]
     */
    public function run()
    {
        $this->routeMapper->map();
        $response = $this->routerPathResolver->resolve($this->container->get(TemplateViewRendererInterface::class));
        $response->send();
    }
}
