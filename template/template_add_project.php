<?php include __DIR__ . '/component/header.php'; ?>
<main>
    <h1>Daniel Bezes Architecture</h1>
    <h2>Page d'ajout de projet</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Informations générales</legend>
            <p>
                <label for="name">Nom du projet</label>
                <input type="text" name="name" placeholder="Ex : Résidence Thales" aria-label="Nom du projet" required>
            </p>
            <p>
                <label for="description">Description</label>
                <textarea name="description" cols="25" rows="10" placeholder="Description" required></textarea>
            </p>
            <p>
                <label for="location">Localisation</label>
                <input type="text" name="location" placeholder="Localisation" aria-label="Localisation" required>
            </p>
            <p>
                <label for="year">Année</label>
                <input type="number" name="year" placeholder="Année" aria-label="Année">
            </p>
            <p>
            <textarea name="description" cols="25" rows="10" placeholder="Description" required></textarea>
            </p>
        </fieldset>
        <fieldset>
            <input type="file" name="image">
            <input type="submit" value="Inscription" name="submit">
        </fieldset>
    </form>
    <?php if(isset($data["msg"])) : ?>
    <p><?= $data["msg"] ?></p>
    <?php endif ?>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>

    
