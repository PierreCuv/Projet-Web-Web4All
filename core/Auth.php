<?php

declare(strict_types=1);

namespace Core;

/**
 * Gestion de l'authentification.
 * Les données utilisateur sont stockées en session.
 */
class Auth
{
    private const SESSION_KEY = 'auth_user';

    public static function login(array $user): void
    {
        session_regenerate_id(true);

        Session::set(self::SESSION_KEY, [
            'id'          => $user['id_utilisateur'],
            'email'       => $user['email'],
            'firstname'   => $user['prenom'],
            'lastname'    => $user['nom'],
            'role'        => $user['role'],
            'id_etudiant' => $user['id_etudiant'] ?? null,
            'id_pilote'   => $user['pilote_id']   ?? null,
        ]);
    }

    public static function logout(): void
    {
        Session::remove(self::SESSION_KEY);
        session_regenerate_id(true);
    }

    public static function isLoggedIn(): bool
    {
        return Session::has(self::SESSION_KEY);
    }

    public static function getUser(): ?array
    {
        return Session::get(self::SESSION_KEY);
    }

    public static function getId(): ?int
    {
        return self::getUser()['id'] ?? null;
    }

    public static function getRole(): string
    {
        return self::getUser()['role'] ?? 'anonyme';
    }

    public static function getFullName(): string
    {
        $user = self::getUser();
        if (!$user) return 'Invité';
        return $user['firstname'] . ' ' . $user['lastname'];
    }

    public static function getEtudiantId(): ?int
    {
        return self::getUser()['id_etudiant'] ?? null;
    }

    public static function getPiloteId(): ?int
    {
        return self::getUser()['id_pilote'] ?? null;
    }

    // ── Raccourcis ────────────────────────────────────────────────────────
    public static function isAdmin(): bool
    {
        return self::getRole() === 'administrateur';
    }

    public static function isPilot(): bool
    {
        return self::getRole() === 'pilote';
    }

    public static function isStudent(): bool
    {
        return self::getRole() === 'etudiant';
    }

    public static function isGuest(): bool
    {
        return self::getRole() === 'anonyme';
    }

    /** Admin ou Pilote — partagent beaucoup de fonctionnalités */
    public static function isStaff(): bool
    {
        return in_array(self::getRole(), ['administrateur', 'pilote'], true);
    }
}
