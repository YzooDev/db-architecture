<?php
/** @var string $title */
/** @var App\Entity\Project $project */
/** @var string|null $msg */
include __DIR__ . '/component/header.php';
?>
<div class="admin-shell">
    <?php include __DIR__ . '/component/admin_sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-page-header">
            <h1>Modifier le projet</h1>
            <a href="/admin/project" class="btn btn--ghost">← Retour</a>
        </div>
        <?php if (!empty($msg)) : ?>
            <p class="alert alert--success" style="margin-bottom:32px;"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
        <form action="/admin/project/<?= $project->getId() ?>/edit" method="post" novalidate>
            <div class="form-section">
                <p class="form-section__label">Informations générales</p>
                <div class="field">
                    <label for="name">Nom du projet</label>
                    <input type="text" id="name" name="name"
                        value="<?= htmlspecialchars($project->getName()) ?>" required>
                </div>
                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required
                    ><?= htmlspecialchars($project->getDescription()) ?></textarea>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label for="location">Localisation</label>
                        <input type="text" id="location" name="location"
                            value="<?= htmlspecialchars($project->getLocation()) ?>" required>
                    </div>
                    <div class="field">
                        <label for="year">Année</label>
                        <input type="number" id="year" name="year"
                            value="<?= $project->getYear() ?>"
                            required>
                    </div>
                </div>
                <div class="field">
                    <label for="category">Catégorie</label>
                    <select id="category" name="category" required>
                        <?php foreach (['Collectif', 'Individuel', 'Autre'] as $cat) : ?>
                            <option value="<?= $cat ?>" <?= $project->getCategory() === $cat ? 'selected' : '' ?>>
                                <?= $cat ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field field--checkbox">
                    <label>
                        <input type="checkbox" name="built" value="1" <?= $project->getBuilt() ? 'checked' : '' ?>> Projet réalisé (construit)
                    </label>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit_edit" class="btn btn--primary">Enregistrer les modifications</button>
                <a href="/admin/project" class="btn btn--ghost">Annuler</a>
            </div>
        </form>
        <div class="form-section" style="margin-top: 64px; border-top: 1px solid #e8e3db; padding-top: 48px;">
            <p class="form-section__label">Images du projet (<?= count($project->getImages()) ?>)</p>
            <?php if (!empty($project->getImages())) : ?>
                <div class="media-grid">
                    <?php foreach ($project->getImages() as $image) : ?>
                    <div class="media-card <?= $image->getIsCover() ? 'media-card--cover' : '' ?>">
                        <div class="media-card__img-wrap">
                            <img src="<?= htmlspecialchars($image->getWebPath()) ?>" alt="<?= htmlspecialchars($image->getAltText()) ?>" class="media-card__img" loading="lazy">
                            <?php if ($image->getIsCover()) : ?>
                                <span class="media-card__badge">Couverture</span>
                            <?php endif; ?>
                        </div>
                        <div class="media-card__actions">
                            <?php if (!$image->getIsCover()) : ?>
                                <form action="/admin/project/<?= $project->getId() ?>/image/<?= $image->getId() ?>/cover" method="post">
                                    <button type="submit" name="submit_cover" class="btn-media btn-media--cover" title="Définir comme couverture">
                                        ☆ Couverture
                                    </button>
                                </form>
                            <?php else : ?>
                                <span class="btn-media btn-media--is-cover">★ Couverture</span>
                            <?php endif; ?>
                            <?php if (count($project->getImages()) > 1) : ?>
                                <form action="/admin/project/<?= $project->getId() ?>/image/<?= $image->getId() ?>/delete"  method="post" onsubmit="return confirm ('Supprimer cette image définitivement ?')">
                                    <button type="submit" name="submit_delete_image" class="btn-media btn-media--delete">
                                        ✕ Supprimer
                                    </button>
                                </form>
                            <?php else : ?>
                                <button type="button" class="btn-media btn-media--delete" title="Impossible de supprimer la dernière image du projet" disabled >
                                    ✕ Supprimer
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="empty-state" style="padding-bottom: 24px;">Aucune image pour ce projet.</p>
            <?php endif; ?>
            <form action="/admin/project/<?= $project->getId() ?>/image/store" method="post" enctype="multipart/form-data" style="margin-top: 32px;" novalidate>
                <div class="upload-zone">
                    <input type="file" id="images" name="images[]"
                        accept=".jpg,.jpeg,.png,.webp" multiple>
                    <div class="upload-zone__icon">⊕</div>
                    <p class="upload-zone__text">
                        <strong>Cliquez pour ajouter des images</strong><br>
                        <?php if (empty($project->getImages())) : ?>
                            La première image sera définie comme couverture.
                        <?php else : ?>
                            Les nouvelles images s'ajouteront à la galerie existante.
                        <?php endif; ?>
                    </p>
                    <p class="upload-zone__hint">JPG · PNG · WEBP · Max 2 Mo par image</p>
                </div>
                <div id="preview-grid" class="preview-grid"></div>
                <p id="preview-count" class="preview-count"></p>
                <div class="form-actions" style="margin-top: 24px;">
                    <button type="submit" name="submit_upload" class="btn btn--primary">Ajouter les images</button>
                </div>
            </form>
        </div>
    </main>
</div>
<?php include __DIR__ . '/component/footer.php'; ?>

