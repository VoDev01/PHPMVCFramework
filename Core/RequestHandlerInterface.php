<?php

namespace App\Core;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}