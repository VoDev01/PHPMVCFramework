<x-main>
    <h1>Product <?= $product['id'] ?></h1>
    <p><?= $product['name'] ?></p>
    <p><?= $product['description'] ?></p>
    <a href="product/edit">Edit</a>
</x-main>