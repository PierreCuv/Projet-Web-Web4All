<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \Core\View::e($title ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/app.css">
</head>
<body>
<header class="navbar">
    <div class="container">
        <a href="<?= APP_URL ?>/" class="navbar__brand">Web4All</a>
        <button class="navbar__burger" id="burger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <nav class="navbar__menu" id="nav-menu">
            <a href="<?= APP_URL ?>/offres">Offres</a>
            <a href="<?= APP_URL ?>/entreprises">Entreprises</a>
            <?php if (\Core\Auth::isStudent()): ?>
                <a href="<?= APP_URL ?>/ma-wishlist">Wish-list</a>
                <a href="<?= APP_URL ?>/mes-candidatures">Mes candidatures</a>
            <?php endif; ?>
            <?php if (\Core\Auth::isStaff()): ?>
                <a href="<?= APP_URL ?>/etudiants">Étudiants</a>
            <?php endif; ?>
            <?php if (\Core\Auth::isPilot()): ?>
                <a href="<?= APP_URL ?>/pilote/candidatures">Candidatures</a>
            <?php endif; ?>
            <?php if (\Core\Auth::isAdmin()): ?>
                <a href="<?= APP_URL ?>/pilotes">Pilotes</a>
            <?php endif; ?>
            <div class="navbar__sep"></div>
            <?php if (\Core\Auth::isLoggedIn()): ?>
                <span class="navbar__user"><?= \Core\View::e(\Core\Auth::getFullName()) ?></span>
                <span class="navbar__role role--<?= \Core\Auth::getRole() ?>">
                    <?= ROLES[\Core\Auth::getRole()] ?? '' ?>
                </span>
                <a href="<?= APP_URL ?>/logout" class="btn btn--outline btn--sm">Déconnexion</a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/login" class="btn btn--primary btn--sm">Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?php
$successMsg = \Core\Session::getFlash('success');
$errorMsg   = \Core\Session::getFlash('error');
?>
<?php if ($successMsg): ?>
    <div class="flash flash--success container"><?= \Core\View::e($successMsg) ?></div>
<?php endif; ?>
<?php if ($errorMsg): ?>
    <div class="flash flash--error container"><?= \Core\View::e($errorMsg) ?></div>
<?php endif; ?>

<main class="main">
    <?= $content ?>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> Web4All –
            <a href="<?= APP_URL ?>/mentions-legales">Mentions légales</a>
        </p>
    </div>
</footer>
<script src="<?= APP_URL ?>/public/js/app.js"></script>
</body>
</html>
