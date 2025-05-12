<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    public int $id;
    public string $name;
    public string $surname;
    public string $email;
    public string $password;
}