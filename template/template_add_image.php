<?php include __DIR__ . '/component/header.php'; ?>
<main>
    <h1>Daniel Bezes Architecture</h1>
    <h2>Page d'ajout d'images</h2>
    <div class="info">
        <h2>Uploaded images : </h2>
    </div>
    <div class="preview">
        <div class="image-wrapper">
            <img src="" alt="">
            <a href="">Supprimer</a>
        </div>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="image">Selectionner une image à télécharger</label>
        <input type="file" name="image">
        <input type="submit" value="Télécharger" name="submit">
        <a href="/admin/project/image">Annuler</a>

        <p>Fichier téléchargé correctement</p>
    </form>
    <?php if(isset($data["msg"])) : ?>
        <p><?= $data["msg"] ?></p>
    <?php endif ?>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>

    
