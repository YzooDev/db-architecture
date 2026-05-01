<?php include __DIR__ . '/component/header.php'; ?>
<main>
    <h1>Daniel Bezes Architecture</h1>
    <h2>Page de Contact</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="firstname">Prénom</label>
        <input type="text" name="firstname" aria-label="Prénom">
        <label for="lastname">Nom</label>
        <input type="text" name="lastname" aria-label="Nom">
        <label for="email">Adresse mail</label>
        <input type="email" name="email" aria-label="Email">
        <label for="description">Détails de votre demande</label>
        <textarea name="description" cols="25" rows="10" placeholder="Détaillez votre demande ici" required></textarea>
        <input type="submit" value="Envoyer" name="submit">
    </form>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>

    
