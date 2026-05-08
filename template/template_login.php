<?php
/** @var string $title */
/** @var array $data */
include __DIR__ . '/component/header.php';
?>
<main class="login-page">
    <div class="login-page__visual" aria-hidden="true">
        <p class="login-page__quote">« Celui qui construit selon les conseils de tout le monde aura la maison de tout le monde. »</p>
        <p class="login-page__quote-author">— Bernard Serge</p>
    </div>
    <div class="login-page__form-side">
        <p class="login-form__eyebrow">Espace administration</p>
        <h2 class="login-form__title">Connexion</h2>
        <?php if (!empty($data['msg'])) : ?>
            <p class="alert alert--error"><?= htmlspecialchars($data['msg']) ?></p>
        <?php endif; ?>
        <form action="/admin" method="post" novalidate>
            <div class="field">
                <label for="username">Identifiant</label>
                <input type="text" id="username" name="username" autocomplete="username" required autofocus>
            </div>
            <div class="field">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <div style="margin-top:32px;">
                <button type="submit" name="submit" class="btn btn--primary">Se connecter</button>
            </div>
        </form>
        <p class="login-form__back"><a href="/">← Retour au site</a></p>
    </div>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>
