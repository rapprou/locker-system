<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gestion de Casiers' ?></title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/style.css">
</head>
<body>
    <header>
        <h1>Système de Gestion de Casiers</h1>
        <nav>
            <ul>
                <li><a href="<?= BASE_PATH ?>/">Accueil</a></li>
                <li><a href="<?= BASE_PATH ?>/lockers">Liste des Casiers</a></li>
                <li><a href="<?= BASE_PATH ?>/lockers/assign">Attribution</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash-message flash-<?= $_SESSION['flash']['type'] ?>">
                <?= $_SESSION['flash']['message'] ?>
            </div>
        <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?php if (isset($content)) : echo $content; endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> GAMMES Gestion de Casiers</p>
    </footer>
</body>
</html>