<?php include __DIR__ . '/component/header.php'; ?>
<main class="container-fluid">
    <h2>Se connecter</h2>
    <form action="" method="post">
        <input type="texte" name="username" placeholder="username">
        <input type="password" name="password" placeholder="mot de passe">
        <input type="submit" value="Connexion" name="submit">
    </form>
    <p><?= $data["msg"] ?? "" ?></p>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>