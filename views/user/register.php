<x-main>
    <h1>Register</h1>
    <form action="/register" method="post">
        <div class="row mb-3">
            <div class="col">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" />
                <?php if(isset($errors["name"])): ?>
                    <p><?= $errors["name"] ?></p>
                <?php endif; ?>
            </div>
            <div class="col">
                <label for="surname" class="form-label">Surname</label>
                <input type="text" class="form-control" name="surname" id="surname" />
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                class="form-control"
                name="email"
                id="email" />
                <?php if(isset($errors["email"])): ?>
                    <p><?= $errors["email"] ?></p>
                <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                class="form-control"
                name="password"
                id="password" />
                <?php if(isset($errors["password"])): ?>
                    <p><?= $errors["password"] ?></p>
                <?php endif; ?>
        </div>
        <button
            type="submit"
            class="btn btn-primary">
            Submit
        </button>
    </form>
</x-main>