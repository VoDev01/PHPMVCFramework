<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\RequestHandlerInterface;
use App\Core\Response;

class RedirectExample extends Middleware
{
    public function __construct(private Response $response)
    {
    }

    public function handle(Request $request, RequestHandlerInterface $next): Response
    {
        $this->response->redirect("/products/index");

        return $this->response;
    }
}