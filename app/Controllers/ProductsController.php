<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    public function __construct(private Product $product) 
    {
    }
    public function index()
    {
        $products = $this->product->getAll();
        $total = $this->product->getTotal();
        return $this->render("/products/index", ["products" => $products, "total" => $total]);
    }
    public function create()
    {
        return $this->render("/products/create");
    }
    public function createPost(Request $request)
    {
        $this->product->insert($request->body());
        header("Location: /products/{$this->product->getInsertId()}/show");
        exit;
    }
    public function edit(int $id)
    {
        $product = $this->product->find($id);
        return $this->render("/products/edit", ['product' => $product]);
    }
    public function editPost(Request $request)
    {
        $this->product->update($request->body(), $request->id);
        header("Location: /products/$request->id/show");
        exit;
    }
    public function show(int $id)
    {
        $product = $this->product->find($id);
        return $this->render("/products/show", ['product' => $product]);
    }
    public function delete(int $id)
    {
        $product = $this->product->find($id);
        return $this->render("/products/delete", ['product' => $product]);
    }
    public function deletePost(Request $request)
    {
        $this->product->delete($request->id);
        header("Location: /products/index");
        exit;
    }
}