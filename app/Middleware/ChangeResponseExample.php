<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Response;
use App\Core\RequestHandlerInterface;

class ChangeResponseExample extends Middleware
{
    public function handle(Request $request, RequestHandlerInterface $next): Response
    {
        $response = $next->handle($request);
        $response->setBody($response->getBody() . " Hello from middleware!");
        return $response;
    }
}