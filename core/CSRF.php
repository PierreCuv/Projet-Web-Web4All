<?php

declare(strict_types=1);

namespace Core;

/**
 * Protection CSRF.
 * Un token unique par session est généré et validé sur chaque formulaire POST.
 *
 * Dans la vue :       echo CSRF::field();
 * Dans le contrôleur: CSRF::verify();
 */
class CSRF
{
    private const TOKEN_KEY = '_csrf_token';

    public static function generate(): string
    {
        if (!Session::has(self::TOKEN_KEY)) {
            Session::set(self::TOKEN_KEY, bin2hex(random_bytes(32)));
        }
        return Session::get(self::TOKEN_KEY);
    }

    /** Retourne un champ input hidden à insérer dans chaque formulaire */
    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="_csrf_token" value="'
            . htmlspecialchars($token, ENT_QUOTES) . '">';
    }

    /** Vérifie le token POST — arrête tout si invalide */
    public static function verify(): void
    {
        $submitted = $_POST['_csrf_token'] ?? '';
        $stored    = Session::get(self::TOKEN_KEY, '');

        if (!hash_equals($stored, $submitted)) {
            http_response_code(419);
            die('Token CSRF invalide. Veuillez recharger la page.');
        }
    }
}
