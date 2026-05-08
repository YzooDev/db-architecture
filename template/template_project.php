<?php
/** @var string $title */
/** @var App\Entity\Project[] $projects */
include __DIR__ . '/component/header.php';
?>
<main>
    <section class="section">
        <div class="section-header">
            <h1>Projets</h1>
            <?php if (!empty($projects)) : ?>
                <span class="projects-count"><?= count($projects) ?> réalisation<?= count($projects) > 1 ? 's' : '' ?></span>
            <?php endif; ?>
        </div>

        <?php if (empty($projects)) : ?>
            <p class="empty-state">Aucun projet disponible pour le moment.</p>
        <?php else : ?>
            <div class="projects-grid">
                <?php foreach ($projects as $project) :
                    $cover = null;
                    foreach ($project->getImages() as $img) {
                        if ($img->getIsCover()) { $cover = $img; break; }
                    }
                    if (!$cover && count($project->getImages()) > 0) {
                        $cover = $project->getImages()[0];
                    }
                ?>
                <!-- MODIFICATION 3 : lien vers le détail du projet -->
                <a href="/project/<?= $project->getId() ?>" class="project-card">
                    <?php if ($cover) : ?>
                        <img src="<?= htmlspecialchars($cover->getWebPath()) ?>"
                             alt="<?= htmlspecialchars($cover->getAltText()) ?>"
                             class="project-card__img" loading="lazy">
                    <?php else : ?>
                        <div class="project-card__placeholder">Image à venir</div>
                    <?php endif; ?>
                    <div class="project-card__overlay">
                        <span class="project-card__category"><?= htmlspecialchars($project->getCategory()) ?></span>
                        <h2 class="project-card__name"><?= htmlspecialchars($project->getName()) ?></h2>
                        <p class="project-card__meta"><?= htmlspecialchars($project->getLocation()) ?> · <?= $project->getYear() ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>
