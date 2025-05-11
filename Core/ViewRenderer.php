<?php

namespace App\Core;

/**
 * Renders views and layouts
 */
class ViewRenderer
{
    /**
     * Renders layout and returns its contents
     * @param string $layout
     * 
     * @return string
     */
    private function renderLayout(string $layout): string
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
     * @return string
     */
    public function renderView(string $view, array $params = []): string
    {
        foreach($params as $key => $value)
        {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        $viewContent = ob_get_clean();
        if(preg_match("/^.*<x-(?<layoutName>.+)>\X*(?=.*<\/x-.+>).*$/mu", $viewContent, $matches))
        {
            $layoutContent = $this->renderLayout($matches["layoutName"]);
            return str_replace("{{content}}", $viewContent, $layoutContent);
        }
        return $viewContent;
    }
}