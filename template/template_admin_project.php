<?php
/** @var App\Entity\Project[] $data */
include __DIR__ . '/component/header.php';
?>
<main>
    <h1>Daniel Bezes Architecture</h1>
    <h2>Page Admin des Projets</h2>
    <div>
        <a href="/admin/project/new">+ Nouveau projet</a>
        <a href="/admin/project/image">+ Ajouter des images</a>
    </div>
    <?php foreach ($data as $project) : ?>
        <article>
            <p><?= $project->getName() ?></p>
            <p><?= $project->getDescription() ?></p>
            <p><?= $project->getYear() ?></p>
            <p><?= $project->getName() ?></p>
            <p>
                <?php foreach ($project->getImages() as $image) : ?>
                    <button class="pill-button"><?= $image->getFilename() ?></button>
                <?php endforeach ?>
            </p>
        </article>
    <?php endforeach ?>
</main>

<?php include __DIR__ . '/component/footer.php'; ?>

    
