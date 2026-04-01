<?php

declare(strict_types=1);

// ── Environnement ────────────────────────────────────────────────────────
define('APP_ENV', 'development');
define('APP_NAME', 'Web4All');

// ── URL de base ──────────────────────────────────────────────────────────
// Calcule automatiquement l'URL du projet sous Apache (localhost, vhost,
// sous-dossier, etc.) au lieu d'imposer localhost:8080.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath = rtrim(str_replace('/index.php', '', $scriptName), '/');
define('APP_URL', $scheme . '://' . $host . $basePath);

// ── Affichage des erreurs ────────────────────────────────────────────────
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

if (!is_dir(UPLOAD_PATH)) {
    @mkdir(UPLOAD_PATH, 0775, true);
}
