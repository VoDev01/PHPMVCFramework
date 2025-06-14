<?php

namespace App\Core;

/**
 * Base class of controller
 */
class Controller
{
    protected Request $request;

    protected TemplateViewRendererInterface $viewRenderer;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setViewRenderer(TemplateViewRendererInterface $viewRenderer)
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
    }
}