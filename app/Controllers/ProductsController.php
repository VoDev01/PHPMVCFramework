<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Product;

class ProductsController extends Controller
{
    public function __construct(private Product $product) 
    {
    }
    public function index(): Response
    {
        $products = $this->product->getAll();
        $total = $this->product->getTotal();
        return $this->render("/products/index", ["products" => $products, "total" => $total]);
    }
    public function create(): Response
    {
        return $this->render("/products/create");
    }
    public function createPost(Request $request)
    {
        $this->product->insert($request->body);
        return $this->redirect("/products/{$this->product->getInsertId()}/show");
    }
    public function edit(int $id): Response
    {
        $product = $this->product->find($id);
        return $this->render("/products/edit", ['product' => $product]);
    }
    public function editPost(Request $request)
    {
        $this->product->update($request->rawBody(), $request->id);
        return $this->redirect("/products/$request->id/show");
    }
    public function show(int $id): Response
    {
        $product = $this->product->find($id);
        return $this->render("/products/show", ['product' => $product]);
    }
    public function delete(int $id): Response
    {
        $product = $this->product->find($id);
        return $this->render("/products/delete", ['product' => $product]);
    }
    public function deletePost(Request $request)
    {
        $this->product->delete($request->id);
        return $this->redirect("/products/index");
    }
    public function responseCodeExample(): Response
    {
        $this->response->setResponseCode(451);

        $this->response->setBody("Unavailable for legal reasons");

        return $this->response;
    }
}