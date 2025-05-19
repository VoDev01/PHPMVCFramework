<?php

namespace App\Controllers;

use App\Core\Request;
use App\Validators\RegisterValidator;
use App\Core\Controller;

class HomeController extends Controller
{
    public function home()
    {
        return $this->render("home", ['name' => ' sweet home!']);
    }
    public function showUser(int $id)
    {
        $user = $this->model->find($id);
        return $this->render("/user/show", ['user' => $user]);
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
        $validated = (new RegisterValidator)->validate($request);
        if(!isset($validated->errors))
        {
            $this->model->insert($validated);
            header("Location: /home/{$this->model->getInsertId()}/showuser");
            exit;
        }
        else
        {
            $this->render("register", ['errors' => $validated->errors]);
        }
    }
}