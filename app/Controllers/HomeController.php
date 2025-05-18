<?php

namespace App\Controllers;

use App\Core\Request;
use App\Validators\RegisterValidator;
use App\Core\Controller;
use App\Core\Exceptions\PageNotFoundException;

class HomeController extends Controller
{
    public function home()
    {
        return $this->render("home", ['name' => ' sweet home!']);
    }
    public function showProduct(int $id)
    {
        $product = $this->model->find($id);
        if(!$product)
            throw new PageNotFoundException("Requested resource of model " . get_class($this->model) . " with id {$id} not found");
        return $this->render("showProduct", ['product' => $product]);
    }
    public function catalog(string $name = "namename")
    {
        return $this->render("catalog", ['name' => $name]);
    }
    public function catalogPost(Request $request)
    {
        echo $request->name;
    }
    public function register()
    {
        return $this->render("register");
    }
    public function registerPost(Request $request)
    {
        $validated = (new RegisterValidator())->validate($request);
        var_dump($validated);
    }
}