<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Response;
use App\Core\RequestHandlerInterface;


class ChangeRequestExample extends Middleware
{
    public function handle(Request $request, RequestHandlerInterface $next): Response
    {
        $request->body = array_map("trim", $request->body);

        $response = $next->handle($request);

        return $response;
    }
}