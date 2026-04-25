<?php include __DIR__ . '/component/header.php'; ?>
<main>
    <h1>Daniel Bezes Architecture</h1>
    <h2>Page de Contact</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="firstname" placeholder="Prénom" aria-label="Prénom">
        <input type="text" name="lastname" placeholder="Nom" aria-label="Nom">
        <input type="email" name="email" placeholder="email">
        <textarea name="description" cols="25" rows="10" placeholder="Détailler votre demande" required></textarea>
        <input type="submit" value="Envoyer" name="submit">
    </form>
    <?php if(isset($data["msg"])) : ?>
        <p><?= $data["msg"] ?></p>
    <?php endif ?>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>

    
