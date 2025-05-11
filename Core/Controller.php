<?php

namespace App\Core;

/**
 * Base class of controller
 */
class Controller
{
    private ViewRenderer $viewRenderer;

    public function __construct(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
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
        echo $this->viewRenderer->renderView($view, $params);
        return;
    }
}