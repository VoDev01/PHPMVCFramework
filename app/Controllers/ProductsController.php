<?php

namespace App\Controllers;

use App\Core\Controller;

class ProductsController extends Controller
{
    public function index()
    {
        return $this->render("/products/index");
    }
    public function edit()
    {
        
    }
    public function showProduct(int $id)
    {
        $product = $this->model->find($id);
        return $this->render("/products/show", ['product' => $product]);
    }
}