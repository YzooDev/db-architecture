<?php include __DIR__ . '/component/header.php'; ?>
<main>
    <h1>Daniel Bezes Architecture</h1>
    <h2>Page de gestion des Projets</h2>
    <?php if(isset($_SESSION["connected"])): ?>
        <h3><a href="/admin/project/new">Ajouter un projet</a></h3>
    <?php endif ?>
</main>
<?php include __DIR__ . '/component/footer.php'; ?>