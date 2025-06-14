<x-slot name="title">Products</x-slot>
<x-main>
    <h1>Products</h1>
    <a class="text-decoration-none" href="/products/create">New product</a>

    {% foreach($products as $product): %}
        <br> <a class="text-decoration-none" href="/products/{{ product['id'] }}/show\">{{ product['name'] }}</a>
    {% endforeach; %}
    
    <p>Total: {{ total }}</p>
</x-main>