<x-main>
    <h1>Product <?= $product['name'] ?></h1>
    <p><?= $product['name'] ?></p>
    <p><?= $product['description'] ?></p>
    <a href="/products/index/">Index</a>
    <?= "<a href=\"/products/{$product['id']}/edit\">Edit</a>" ?>
    <?= "<a href=\"/products/{$product['id']}/delete\">Delete</a>" ?>
</x-main>