<?php

namespace App\Core;

/**
 * Base class of controller
 */
class Controller extends ViewRenderer
{
    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    /**
     * Renders specified view with key value data that will be passed to view. Key is the name of parameter which will be used in view
     * @param mixed $view
     * @param array $params
     * 
     * @return [type]
     */
    public function render($view, array $params = [])
    {
        echo $this->renderView($view, $params);
        return;
    }
}