<x-main>
    <h1>User <?= $user['id'] ?></h1>
    <p><?= $user['name'] ?></p>
    <p><?= $user['surname'] ?? "None" ?></p>
    <p><?= $user['email'] ?></p>
    <p><?= $user['password'] ?></p>
</x-main>