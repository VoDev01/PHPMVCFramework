<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\ViewRenderer;

class HomeController
{
    public function home()
    {
        $viewRenderer = new ViewRenderer(new Request(), new Response());
        echo $viewRenderer->renderView("home");
        return;
    }
}