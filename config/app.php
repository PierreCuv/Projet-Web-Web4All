<?php

declare(strict_types=1);

define('APP_ENV', 'development');
define('APP_NAME', 'Web4All');
define('APP_URL', 'http://localhost:8000');

if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

date_default_timezone_set('Europe/Paris');

define('ROLES', [
    'administrateur' => 'Administrateur',
    'pilote'         => 'Pilote',
    'etudiant'       => 'Etudiant',
    'anonyme'        => 'Anonyme',
]);

define('ITEMS_PER_PAGE', 10);

define('UPLOAD_PATH', ROOT_PATH . '/public/uploads/');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024);
define('UPLOAD_ALLOWED_TYPES', ['application/pdf']);
