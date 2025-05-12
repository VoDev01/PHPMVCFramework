<?php

namespace App\Core;

use App\Models\UserModel;

/**
 * Base class of controller
 */
class Controller
{
    
    public function __construct(private ViewRenderer $viewRenderer, protected UserModel $model)
    {
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