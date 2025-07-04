<x-slot name="title">Create</x-slot>
<x-main>
    <h1>Create product</h1>
    <form action="/products/createPost" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input
                type="text"
                class="form-control"
                name="name"
                id="name" />
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input
                type="text"
                class="form-control"
                name="description"
                id="description" />
        </div>
        <button
            type="submit"
            class="btn btn-primary">
            Submit
        </button>
    </form>
</x-main>