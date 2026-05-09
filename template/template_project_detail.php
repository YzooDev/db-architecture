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
<main>
    <div class="project-hero">
        <?php if ($cover) : ?>
            <img
                src="<?= htmlspecialchars($cover->getWebPath()) ?>"
                alt="<?= htmlspecialchars($cover->getAltText() ?: $project->getName()) ?>"
                class="project-hero__cover"
            >
        <?php else : ?>
            <div class="project-hero__placeholder">Aucune image</div>
        <?php endif; ?>
    </div>
    <div style="padding-inline: clamp(24px, 5vw, 80px); max-width: 1280px; margin-inline: auto;">
        <div class="project-detail">
            <div>
                <h1 class="project-detail__name"><?= htmlspecialchars($project->getName()) ?></h1>
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
            </aside>
        </div>
        <?php if (!empty($gallery)) : ?>
            <div class="project-gallery">
                <?php foreach ($gallery as $image) : ?>
                    <img
                        src="<?= htmlspecialchars($image->getWebPath()) ?>"
                        alt="<?= htmlspecialchars($image->getAltText() ?: $project->getName()) ?>"
                        class="project-gallery__img"
                        loading="lazy"
                    >
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div style="padding-bottom: 80px; margin-top: 40px;">
            <a href="/project" style="font-size:0.8rem; letter-spacing:0.12em; text-transform:uppercase; border-bottom:1px solid #e8e3db;">← Tous les projets</a>
        </div>
    </div>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>
