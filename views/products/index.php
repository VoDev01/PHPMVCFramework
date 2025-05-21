<x-main>
    <h1>Products</h1>
    <a class="text-decoration-none" href="/products/create">New product</a>
    <?php foreach($products as $product): ?>
        <?= "<br> <a class=\"text-decoration-none\" href=\"/products/{$product['id']}/show\">{$product['name']}</a>" ?>
    <?php endforeach; ?>
    <p>Total: <?= $total ?></p>
</x-main>