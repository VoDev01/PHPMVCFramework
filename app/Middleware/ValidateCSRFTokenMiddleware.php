<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Response;

class ValidateCSRFTokenMiddleware extends Middleware
{
    public function handle(Request $request, callable $next): Response
    {
        return $next($request);
    }
}