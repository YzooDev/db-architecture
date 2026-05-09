<?php
/** @var string $title */
/** @var App\Entity\Project $project */
$cover = null;
$gallery = [];
foreach ($project->getImages() as $img) {
    if ($img->getIsCover()) { $cover = $img; }
    else { $gallery[] = $img; }
}
if (!$cover && count($project->getImages()) > 0) {
    $cover = $project->getImages()[0];
    $gallery = array_slice($project->getImages(), 1);
}
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
            <h1><?= htmlspecialchars($project->getName()) ?></h1>
            <div style="display:flex; gap:12px; align-items:center;">
                <a href="/admin/project/<?= $project->getId() ?>/edit" class="btn btn--primary">Modifier</a>
                <form action="/admin/project/<?= $project->getId() ?>/delete" method="post"
                      onsubmit="return confirm('Supprimer ce projet ? Cette action est irréversible.')">
                    <button type="submit" class="btn btn--danger">Supprimer</button>
                </form>
                <a href="/admin/project" class="btn btn--ghost">← Retour</a>
            </div>
        </div>
        <?php if ($cover) : ?>
            <img src="<?= htmlspecialchars($cover->getWebPath()) ?>"
                 alt="<?= htmlspecialchars($cover->getAltText()) ?>"
                 style="width:100%; height:320px; object-fit:cover; display:block; margin-bottom:40px;">
        <?php endif; ?>
        <div class="project-detail" style="padding-top:0;">
            <div>
                <h2 class="project-detail__name" style="font-size:1.4rem;"><?= htmlspecialchars($project->getName()) ?></h2>
                <p class="project-detail__desc"><?= nl2br(htmlspecialchars($project->getDescription())) ?></p>
            </div>
            <aside class="project-meta">
                <div class="project-meta__item">
                    <p class="project-meta__label">Catégorie</p>
                    <p class="project-meta__value"><?= htmlspecialchars($project->getCategory()) ?></p>
                </div>
                <div class="project-meta__item">
                    <p class="project-meta__label">Localisation</p>
                    <p class="project-meta__value"><?= htmlspecialchars($project->getLocation()) ?></p>
                </div>
                <div class="project-meta__item">
                    <p class="project-meta__label">Année</p>
                    <p class="project-meta__value"><?= $project->getYear() ?></p>
                </div>
                <div class="project-meta__item">
                    <p class="project-meta__label">Statut</p>
                    <p class="project-meta__value"><?= $project->getBuilt() ? 'Réalisé' : 'En cours / Étude' ?></p>
                </div>
                <div class="project-meta__item">
                    <p class="project-meta__label">Images</p>
                    <p class="project-meta__value"><?= count($project->getImages()) ?></p>
                </div>
            </aside>
        </div>
        <?php if (!empty($gallery)) : ?>
            <h3 style="font-family:'Playfair Display',serif; font-weight:400; margin-bottom:16px;">Galerie</h3>
            <div class="project-gallery" style="margin-top:0;">
                <?php foreach ($gallery as $image) : ?>
                    <img src="<?= htmlspecialchars($image->getWebPath()) ?>"
                         alt="<?= htmlspecialchars($image->getAltText()) ?>"
                         class="project-gallery__img">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>
<?php include __DIR__ . '/component/footer.php'; ?>
