<?php

declare(strict_types=1);

namespace Core;

/**
 * Middleware RBAC.
 * Vérifie les droits d'accès avant chaque action contrôleur.
 */
class Middleware
{
    public static function checkAccess(array $allowedRoles): void
    {
        if (empty($allowedRoles)) return;

        $userRole = Auth::getRole();

        if (!in_array($userRole, $allowedRoles, true)) {
            if ($userRole === 'anonyme') {
                Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
                header('Location: ' . APP_URL . '/login');
                exit;
            }
            self::forbidden();
        }
    }

    public static function denyUnless(bool $condition): void
    {
        if (!$condition) self::forbidden();
    }

    private static function forbidden(): void
    {
        http_response_code(403);
        $view = new View();
        $view->render('errors/403', ['title' => 'Accès refusé']);
        exit;
    }
}
