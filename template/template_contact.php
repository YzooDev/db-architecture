<?php
/** @var string $title */
/** @var array $errors */
/** @var array $formData */
include __DIR__ . '/component/header.php';
?>
<main>
    <section class="section">
        <div class="section-header">
            <h1>Contact</h1>
        </div>
        <?php if (isset($_GET['success'])) : ?>
            <p class="alert alert--success">Votre message a bien été envoyé. Nous vous répondrons rapidement.</p>
        <?php endif; ?>
        <form action="/contact" method="post" class="form" novalidate>
            <div class="field-row">
                <div class="field">
                    <label for="firstname">Prénom</label>
                    <input type="text" id="firstname" name="firstname"
                        value="<?= htmlspecialchars($formData['firstname'] ?? '') ?>" required>
                    <?php if (!empty($errors['firstname'])) : ?>
                        <p class="alert alert--error"><?= $errors['firstname'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="field">
                    <label for="lastname">Nom</label>
                    <input type="text" id="lastname" name="lastname"
                        value="<?= htmlspecialchars($formData['lastname'] ?? '') ?>" required>
                    <?php if (!empty($errors['lastname'])) : ?>
                        <p class="alert alert--error"><?= $errors['lastname'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="field">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email"
                    value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                <?php if (!empty($errors['email'])) : ?>
                    <p class="alert alert--error"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>
            <div class="field">
                <label for="description">Message</label>
                <textarea id="description" name="description" rows="8" required
                ><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                <?php if (!empty($errors['description'])) : ?>
                    <p class="alert alert--error"><?= $errors['description'] ?></p>
                <?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit" class="btn btn--primary">Envoyer le message</button>
            </div>
        </form>
    </section>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>
