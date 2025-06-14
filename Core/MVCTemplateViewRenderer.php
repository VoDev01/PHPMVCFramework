<?php

namespace App\Core;

use PDO;

class MVCTemplateViewRenderer implements TemplateViewRendererInterface
{
    /**
     * Renders layout and returns its contents
     * @param string $layout
     * 
     * @return string
     */
    public function renderLayout(string $layout): string
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.mvc.php";
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
        $view = trim($view, "/");

        $viewContent = file_get_contents(Application::$ROOT_DIR . "/views/$view.mvc.php");

        $viewContent = $this->replaceVariables($viewContent);

        $viewContent = $this->replacePHP($viewContent);

        extract($params, EXTR_SKIP);

        ob_start();

        if (preg_match("/^.*<x-(?<layoutName>[^\s]+)>\X*(?=.*<\/x-.+>).*$/mu", $viewContent, $matches))
        {
            $layoutContent = $this->renderLayout($matches["layoutName"]);
            $layoutContent = $this->replaceSlot($layoutContent, $viewContent);
            eval("?>" . preg_replace("/{{\s*content\s*}}/", $viewContent, $layoutContent));
            return ob_get_clean();
        }

        else
        {
            eval("?>$viewContent");

            return ob_get_clean();
        }
    }

    private function replaceSlot(string $layout, string $slot)
    {
        preg_match_all("/^.*<x-slot name=\"(?<slotName>.+?)\">(?<slotContent>\X+?)<\/x-slot>$/mu", $slot, $matches, PREG_SET_ORDER);
        $newLayout = "";
        foreach($matches as $match)
        {
            $newLayout = preg_replace("/{{\s*".$match["slotName"]."\s*}}/", "<{$match["slotName"]}>{$match["slotContent"]}</{$match["slotName"]}>", $layout);
        }
        return $newLayout;
    }

    private function replaceVariables(string $code): string
    {
        return preg_replace("/{{\s*(\S+)\s*}}/", "<?= htmlspecialchars(\$$1) ?>", $code);
    }

    private function replacePHP(string $code): string
    {
        return preg_replace("/{%\s*(.+)\s*%}/", "<?php $1 ?>", $code);
    }

    private function getBlocks(string $code): array
    {
        preg_match_all("/{% block (?<name>\w+) %}(?<content>.*?){% endblock %}/s", $code, $matches, PREG_SET_ORDER);

        $blocks = [];

        foreach ($matches as $match)
        {
            $blocks[$match["name"]] = $match["content"];
        }

        return $matches;
    }
}
