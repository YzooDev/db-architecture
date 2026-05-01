<?php include __DIR__ . '/component/header.php'; ?>
<main>
    <h1>Daniel Bezes Architecture</h1>
    <h2>Page d'ajout de projet</h2>
    <a href="/admin/image">+ Ajouter des images</a>
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
                <input type="number" name="year" placeholder="Année" aria-label="Année" required>
            </p>
            <p>
                <label for="category">Catégorie</label>
                <select name="category" required>
                    <option value="1" selected>Collectif</option>
                    <option value="2">Individuel</option>
                    <option value="3">Marché public</option>
                </select>
            </p>
            <p>
                <label for="built">Projet réalisé (construit)</label>
                <input type="checkbox" name="built" value="1" <?= !empty($_POST['built']) ? 'checked' : '' ?>>
            </p>
        </fieldset>
        <!-- <fieldset>
            <legend>Images du projet</legend>
            <?php foreach ($data["images"] as $image): ?>
                <option value="<?= $image->getId() ?>">
                    <?= $image->getFilename() ?>
                </option>
            <?php endforeach ?>
        </fieldset> -->
        <input type="submit" value="Créer le projet" name="submit">
        <a href="/admin/project">Annuler</a>
    </form>
    <?php if(isset($data["msg"])) : ?>
    <p><?= $data["msg"] ?></p>
    <?php endif ?>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>

    
