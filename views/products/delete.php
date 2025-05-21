<x-main>
    <h1>Product <?= $product["name"] ?></h1>
    <p>Are you sure you want to delete this product?</p>
    <form action="/products/deletePost" method="post">
        <input hidden type="number" id="id" name="id" value="<?=$product['id']?>" />
        <button
            type="submit"
            class="btn btn-primary"
        >
            Yes
        </button>
    </form>
</x-main>