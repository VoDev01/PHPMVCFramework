<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function home()
    {
        return $this->render("home", ['name' => ' sweet home!']);
    }
    public function catalog()
    {
        return $this->render("catalog");
    }
    public function catalogPost()
    {
        echo 'Handling response data';
        return;
    }
}