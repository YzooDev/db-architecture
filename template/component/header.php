<?php
/** @var string $metaDesc */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($metaDesc) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=Hind+Madurai:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style/<?= htmlspecialchars($template ?? 'error') ?>.css">
    <title><?= htmlspecialchars($title ?? 'Daniel Bezes Architecture') ?></title>
</head>
<body>
<header>
    <a href="/" aria-label="Accueil">
        <img src="/assets/img/Logo_dBa.webp" alt="Daniel Bezes Architecture">
    </a>
    <nav>
        <ul>
            <li><a href="/" <?= ($_SERVER['REQUEST_URI'] === '/') ? 'class="active"' : '' ?>>Accueil</a></li>
            <li><a href="/project" <?= str_starts_with($_SERVER['REQUEST_URI'], '/project') ? 'class="active"' : '' ?>>Projets</a></li>
            <?php if (!empty($_SESSION['connected'])) : ?>
                <li><a href="/admin/project" <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin') ? 'class="active"' : '' ?>>Admin</a></li>
                <li><a href="/logout">Déconnexion</a></li>
            <?php else : ?>
                <li><a href="/contact" <?= str_starts_with($_SERVER['REQUEST_URI'], '/contact') ? 'class="active"' : '' ?>>Contact</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
