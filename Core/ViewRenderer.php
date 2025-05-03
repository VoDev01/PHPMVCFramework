<?php

namespace App\Core;

class ViewRenderer
{
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    // protected function renderViewLayout(string $layout, string $view)
    // {
    //     $layoutContent = $this->renderLayout($layout);
    //     $viewContent = $this->renderView($view);
    //     return str_replace("{{content}}", $viewContent, $layoutContent);
    // }
    protected function renderLayout(string $layout)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
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