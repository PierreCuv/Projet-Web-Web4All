<?php

declare(strict_types=1);

define('ROOT_PATH', __DIR__);
define('APP_NAME', 'Web4All');

// ── Autoloader ──────────────────────────────────────────────────────────
// Charge automatiquement les classes PHP sans avoir à faire des require partout
spl_autoload_register(function (string $class): void {
    $map = [
        'Core\\'        => ROOT_PATH . '/core/',
        'Controllers\\' => ROOT_PATH . '/controllers/',
        'Models\\'      => ROOT_PATH . '/models/',
    ];

    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $file = $dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// ── Config & session ────────────────────────────────────────────────────
require_once ROOT_PATH . '/config/app.php';

use Core\Session;
use Core\Router;

Session::start();

// ── Routeur ─────────────────────────────────────────────────────────────
$router = new Router();
require_once ROOT_PATH . '/routes/web.php';
$router->dispatch();
