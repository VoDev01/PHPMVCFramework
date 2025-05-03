<?php

namespace App\Core;

class Controller extends ViewRenderer
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function render($view, array $params = [])
    {
        echo $this->renderView($view, $params);
        return;
    }
}