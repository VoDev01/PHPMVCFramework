<?php

namespace App\Core;

interface TemplateViewRendererInterface
{
    public function renderLayout(string $layout): string;
    public function renderView(string $view, array $params = []): string;
}
