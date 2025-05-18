<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected $table = "products";
    public string $name;
    public string $description;
}
