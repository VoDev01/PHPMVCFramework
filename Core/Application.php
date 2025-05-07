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

    protected array $middlewares;

    public Database $database;

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
        $this->database = new Database();
    }

    /**
     * @return [type]
     */
    public function run()
    {
        $this->routeMapper->map();
        foreach(self::$middlewares as $key => $middleware)
        {
            if(array_key_exists($key, self::$middlewares))
                $middleware->handle($this->request, fn(Request $request) => self::$middlewares[$key+1]->handle($request, function() {}));
            else
                break;
        }
        return $this->router->resolve();
    }
}
