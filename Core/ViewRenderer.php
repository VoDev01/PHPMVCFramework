<?php

namespace App\Core;

/**
 * Renders views and layouts
 */
class ViewRenderer
{
    /**
     * @var Request
     */
    public Request $request;
    /**
     * @var Response
     */
    public Response $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Renders layout and returns its contents
     * @param string $layout
     * 
     * @return [type]
     */
    private function renderLayout(string $layout)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    /**
     * Renders view with parameters that will be used in the view. Searches for layout tags (if there is any) in view and replaces them with layout content
     * @param string $view
     * @param array $params
     * 
     * @return [type]
     */
    public function renderView(string $view, array $params = [])
    {
        foreach($params as $key => $value)
        {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        $viewContent = ob_get_clean();
        $matches = [];
        if(preg_match("/^.*<x-.*$|^.*<\/x-.*$/mu", $viewContent, $matches) == 1)
        {
            $layoutPathDelimiterPos = strpos($matches[0], "-")+1;
            $layoutName = substr($matches[0], $layoutPathDelimiterPos, strlen($matches[0]) - $layoutPathDelimiterPos - 1);
            $layoutContent = $this->renderLayout($layoutName);
            return str_replace("{{content}}", $viewContent, $layoutContent);
        }
        return $viewContent;
    }
}