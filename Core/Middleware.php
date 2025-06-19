<?php

namespace App\Core;

use App\Core\RequestHandlerInterface;

abstract class Middleware
{
    public abstract function handle(Request $request, RequestHandlerInterface $next): Response;
}