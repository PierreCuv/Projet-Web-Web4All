<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Web4All - Plateforme de gestion d'offres de stages">
    <title><?= \Core\View::e($title ?? 'Web4All') ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Work+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= APP_URL ?>/" style="text-decoration:none;">
                <h1 class="logo">Web<span class="highlight">4</span>All</h1>
            </a>
        </div>
        <ul class="nav-menu">
            <li><a href="<?= APP_URL ?>/" class="nav-link">Accueil</a></li>
            <li><a href="<?= APP_URL ?>/offres" class="nav-link">Offres</a></li>
            <li><a href="<?= APP_URL ?>/entreprises" class="nav-link">Entreprises</a></li>

            <?php if (\Core\Auth::isStudent()): ?>
                <li><a href="<?= APP_URL ?>/ma-wishlist" class="nav-link">Ma Wishlist</a></li>
                <li><a href="<?= APP_URL ?>/mes-candidatures" class="nav-link">Mes candidatures</a></li>
            <?php endif; ?>

            <?php if (\Core\Auth::isStaff()): ?>
                <li><a href="<?= APP_URL ?>/etudiants" class="nav-link">Étudiants</a></li>
                <li><a href="<?= APP_URL ?>/offres/statistiques" class="nav-link">Statistiques</a></li>
            <?php endif; ?>

            <?php if (\Core\Auth::isPilot()): ?>
                <li><a href="<?= APP_URL ?>/pilote/candidatures" class="nav-link">Candidatures</a></li>
            <?php endif; ?>

            <?php if (\Core\Auth::isAdmin()): ?>
                <li><a href="<?= APP_URL ?>/pilotes" class="nav-link">Pilotes</a></li>
            <?php endif; ?>

            <li><a href="<?= APP_URL ?>/mentions-legales" class="nav-link">Légal</a></li>

            <li class="nav-auth">
                <?php if (\Core\Auth::isLoggedIn()): ?>
                    <span style="font-size:.85rem;margin-right:.5rem;">
                        <?= \Core\View::e(\Core\Auth::getFullName()) ?>
                    </span>
                    <a href="<?= APP_URL ?>/logout" class="btn btn-primary btn-small">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/login" class="btn btn-primary btn-small">Connexion</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</nav>

<?php
$successMsg = \Core\Session::getFlash('success');
$errorMsg   = \Core\Session::getFlash('error');
?>
<?php if ($successMsg): ?>
    <div style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;
                padding:.75rem 1.25rem;margin:.5rem auto;max-width:1200px;
                border-radius:8px;font-size:.9rem;">
        <?= \Core\View::e($successMsg) ?>
    </div>
<?php endif; ?>
<?php if ($errorMsg): ?>
    <div style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;
                padding:.75rem 1.25rem;margin:.5rem auto;max-width:1200px;
                border-radius:8px;font-size:.9rem;">
        <?= \Core\View::e($errorMsg) ?>
    </div>
<?php endif; ?>

<main>
    <?= $content ?>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3 class="footer-title">Web<span class="highlight">4</span>All</h3>
                <p>Votre partenaire pour trouver le stage idéal dans le secteur tech.</p>
            </div>
            <div class="footer-section">
                <h4>Navigation</h4>
                <ul class="footer-links">
                    <li><a href="<?= APP_URL ?>/">Accueil</a></li>
                    <li><a href="<?= APP_URL ?>/offres">Offres</a></li>
                    <li><a href="<?= APP_URL ?>/entreprises">Entreprises</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Légal</h4>
                <ul class="footer-links">
                    <li><a href="<?= APP_URL ?>/mentions-legales">Mentions légales</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p>Email: contact@web4all.fr</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Web4All. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<script src="<?= APP_URL ?>/public/js/script.js"></script>
</body>
</html>
