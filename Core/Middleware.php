<?php

namespace App\Core;

use Closure;

abstract class Middleware
{
    public abstract function handle(Request $request, Closure $next): Response;
}
