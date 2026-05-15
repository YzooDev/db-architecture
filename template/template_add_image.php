<?php include __DIR__ . '/component/header.php'; ?>
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
            <h1>Ajouter une image</h1>
            <a href="/admin/project" class="btn btn--ghost">← Retour</a>
        </div>
        <?php if (!empty($data['msg'])) : ?>
            <p class="alert alert--success"><?= htmlspecialchars($data['msg']) ?></p>
        <?php endif; ?>
        <form action="/admin/project/image" method="post" enctype="multipart/form-data" novalidate>
            <div class="form-section">
                <p class="form-section__label">Projet associé</p>
                <div class="field">
                    <label for="project_id">Sélectionner le projet</label>
                    <select id="project_id" name="project_id" required>
                        <option value="">Sélectionner un projet</option>
                        <?php if (!empty($data['projects'])) : ?>
                            <?php foreach ($data['projects'] as $project) : ?>
                                <option value="<?= $project->getId() ?>">
                                    <?= htmlspecialchars($project->getName()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <option disabled>Aucun projet disponible</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-section">
                <p class="form-section__label">Image</p>
                <div class="upload-zone">
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" required>
                    <div class="upload-zone__icon">⊕</div>
                    <p class="upload-zone__text"><strong>Cliquez pour sélectionner</strong> une image</p>
                    <p class="upload-zone__hint">JPG · PNG · WEBP · Max 2 Mo</p>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit" class="btn btn--primary">Uploader</button>
                <a href="/admin/project" class="btn btn--ghost">Annuler</a>
            </div>
        </form>
    </main>
</div>
<?php include __DIR__ . '/component/footer.php'; ?>
