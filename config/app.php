<?php

declare(strict_types=1);

// ── Environnement ────────────────────────────────────────────────────────
define('APP_ENV', 'development');
define('APP_NAME', 'Web4All');
define('APP_URL', 'http://localhost:8080');

// ── Affichage des erreurs ────────────────────────────────────────────────
// En development on les affiche, en production on les cache
if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// ── Timezone ─────────────────────────────────────────────────────────────
date_default_timezone_set('Europe/Paris');

// ── Rôles (correspondent exactement aux libellés dans la table `role`) ───
define('ROLES', [
    'administrateur' => 'Administrateur',
    'pilote'         => 'Pilote',
    'etudiant'       => 'Etudiant',
    'anonyme'        => 'Anonyme',
]);

// ── Pagination ────────────────────────────────────────────────────────────
define('ITEMS_PER_PAGE', 10);

// ── Upload fichiers (CV, LM) ──────────────────────────────────────────────
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads/');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5 Mo max
define('UPLOAD_ALLOWED_TYPES', ['application/pdf']);
