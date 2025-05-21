<x-main>
    <h1>Edit product <?= $product['id'] ?></h1>
    <form action="/products/editPost" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input
                type="text"
                class="form-control"
                name="name"
                id="name"
                <?= "value=\"{$product['name']}\"" ?> />
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>

            <textarea class="form-control"
                type="text"
                class="form-control"
                name="description"
                id="description"
                <?= "value=\"{$product['description']}\"" ?> rows="5" style="resize: none;"></textarea>
        </div>
        <input type="number" class="form-control" name="id" id="id" value="<?= $product['id'] ?>" hidden />

        <button
            type="submit"
            class="btn btn-primary">
            Submit
        </button>
    </form>
    <?= "<a href=\"/products/{$product['id']}/show\">Cancel</a>" ?>
</x-main>