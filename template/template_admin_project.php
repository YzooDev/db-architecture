<?php
/** @var string $title */
/** @var App\Entity\Project[] $projects */
include __DIR__ . '/component/header.php';
?>
<div class="admin-shell">
    <?php include __DIR__ . '/component/admin_sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-page-header">
            <h1>Projets</h1>
            <a href="/admin/project/new" class="btn btn--primary">+ Nouveau projet</a>
        </div>
        <?php if (empty($projects)) : ?>
            <p class="empty-state">Aucun projet. <a href="/admin/project/new">Créer le premier →</a></p>
        <?php else : ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Aperçu</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Localisation</th>
                        <th>Année</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project) :
                        $cover = null;
                        foreach ($project->getImages() as $img) {
                            if ($img->getIsCover()) { $cover = $img; break; }
                        }
                        if (!$cover && count($project->getImages()) > 0) {
                            $cover = $project->getImages()[0];
                        }
                        $nbImgs = count($project->getImages());
                    ?>
                    <tr>
                        <td data-label="Aperçu">
                            <?php if ($cover) : ?>
                                <img src="<?= htmlspecialchars($cover->getWebPath()) ?>" alt="" class="admin-table__thumb">
                            <?php else : ?>
                                <span class="admin-table__thumb-placeholder"></span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Nom"><?= htmlspecialchars($project->getName()) ?></td>
                        <td data-label="Catégorie"><span class="badge"><?= htmlspecialchars($project->getCategory()) ?></span></td>
                        <td data-label="Localisation"><?= htmlspecialchars($project->getLocation()) ?></td>
                        <td data-label="Année"><?= $project->getYear() ?></td>
                        <td data-label="Images"><?= $nbImgs ?></td>
                        <td data-label="Actions">
                            <div class="admin-table__actions">
                                <a href="/admin/project/<?= $project->getId() ?>/edit" class="btn btn--primary btn--sm">Modifier</a>
                                <form action="/admin/project/<?= $project->getId() ?>/delete" method="post" onsubmit="return confirm('Supprimer le projet « <?= $project->getName() ?> » ? Cette action est irréversible.')">
                                    <button type="submit" name="submit_delete" class="btn btn--danger btn--sm">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</div>
<?php include __DIR__ . '/component/footer.php'; ?>
