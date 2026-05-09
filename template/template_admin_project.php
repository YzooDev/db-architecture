<?php
/** @var string $title */
/** @var App\Entity\Project[] $projects */
include __DIR__ . '/component/header.php';
?>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="admin-sidebar__brand">Daniel Bezes <span>Administration</span></div>
        <nav class="admin-sidebar__nav">
            <a href="/admin/project" class="active">Projets</a>
            <a href="/" target="_blank">Voir le site</a>
            <a href="/logout">Déconnexion</a>
        </nav>
    </aside>
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
                        <td>
                            <?php if ($cover) : ?>
                                <img src="<?= htmlspecialchars($cover->getWebPath()) ?>" alt="" class="admin-table__thumb">
                            <?php else : ?>
                                <span class="admin-table__thumb-placeholder"></span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($project->getName()) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($project->getCategory()) ?></span></td>
                        <td><?= htmlspecialchars($project->getLocation()) ?></td>
                        <td><?= $project->getYear() ?></td>
                        <td><?= $nbImgs ?></td>
                        <td>
                            <div class="admin-table__actions">
                                <a href="/admin/project/<?= $project->getId() ?>" class="btn-link">Voir</a>
                                <a href="/admin/project/<?= $project->getId() ?>/edit" class="btn btn--primary btn--sm">Modifier</a>
                                <form action="/admin/project/<?= $project->getId() ?>/delete" method="post"
                                      onsubmit="return confirm('Supprimer le projet « <?= addslashes($project->getName()) ?> » ? Cette action est irréversible.')">
                                    <button type="submit" class="btn btn--danger btn--sm">Supprimer</button>
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
