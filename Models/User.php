<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = "users";
    public string $name;
    public string $surname;
    public string $email;
    public string $password;
}