<?php include __DIR__ . '/component/header.php'; ?>
<div class="admin-shell">
    <?php include __DIR__ . '/component/admin_sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-page-header">
            <h1>Nouveau projet</h1>
            <a href="/admin/project" class="btn btn--ghost">← Retour</a>
        </div>
        <?php if (!empty($data['msg'])) : ?>
            <p class="alert alert--<?= str_contains($data['msg'], 'erreur') || str_contains($data['msg'], 'Veuillez') ? 'error' : 'success' ?>">
                <?= htmlspecialchars($data['msg']) ?>
            </p>
        <?php endif; ?>
        <form action="/admin/project/new" method="post" enctype="multipart/form-data" novalidate>
            <div class="form-section">
                <p class="form-section__label">Informations générales</p>
                <div class="field">
                    <label for="name">Nom du projet</label>
                    <input type="text" id="name" name="name"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                        placeholder="Ex : Résidence Thales" required>
                </div>
                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"
                        placeholder="Description du projet" required
                    ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label for="location">Localisation</label>
                        <input type="text" id="location" name="location"
                            value="<?= htmlspecialchars($_POST['location'] ?? '') ?>"
                            placeholder="Ex : Agen, France" required>
                    </div>
                    <div class="field">
                        <label for="year">Année</label>
                        <input type="number" id="year" name="year"
                            value="<?= htmlspecialchars($_POST['year'] ?? date('Y')) ?>"
                            required>
                    </div>
                </div>
                <div class="field">
                    <label for="category">Catégorie</label>
                    <select id="category" name="category" required>
                        <option value="" disabled selected>Sélectionnez une catégorie</option>
                        <?php foreach (['Collectif', 'Individuel', 'Autre'] as $cat) : ?>
                            <option value="<?= $cat ?>" <?= (($_POST['category'] ?? '') === $cat) ? 'selected' : '' ?>>
                                <?= $cat ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field field--checkbox">
                    <label>
                        <input type="checkbox" name="built" value="1" <?= !empty($_POST['built']) ? 'checked' : '' ?>>
                        Projet réalisé (construit)
                    </label>
                </div>
            </div>
            <div class="form-section">
                <p class="form-section__label">Images du projet</p>
                <div class="upload-zone">
                    <input type="file" id="images" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple required>
                    <div class="upload-zone__icon">⊕</div>
                    <p class="upload-zone__text">
                        <strong>Cliquez pour sélectionner</strong> ou glissez vos images<br>
                        <small>Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs fichiers</small>
                    </p>
                    <p class="upload-zone__hint">JPG · PNG · WEBP · Max 2 Mo par image</p>
                </div>
                <div id="preview-grid" class="preview-grid"></div>
                <p id="preview-count" class="preview-count"></p>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit" class="btn btn--primary">Créer le projet</button>
                <a href="/admin/project" class="btn btn--ghost">Annuler</a>
            </div>
        </form>
    </main>
</div>

<?php include __DIR__ . '/component/footer.php'; ?>
