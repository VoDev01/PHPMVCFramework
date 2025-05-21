<?php

namespace App\Controllers;

use App\Core\Request;
use App\Validators\RegisterValidator;
use App\Core\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct(private User $user) 
    {
    }
    public function home()
    {
        return $this->render("home", ['name' => ' sweet home!']);
    }
    public function showUser(int $id)
    {
        $user = $this->user->find($id);
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
            $this->user->insert($validated);
            header("Location: /home/{$this->user->getInsertId()}/showuser");
            exit;
        }
        else
        {
            $this->render("register", ['errors' => $validated->errors]);
        }
    }
}