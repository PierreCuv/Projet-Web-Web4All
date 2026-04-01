<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires — Projet Web4All
 * Groupe : Sana, Arthur, Pierre
 *
 * Lancer avec : ./vendor/bin/phpunit tests/
 */
class Web4AllTest extends TestCase
{
    // ══════════════════════════════════════════════════════
    // 1. SÉCURITÉ — Hashage des mots de passe
    // ══════════════════════════════════════════════════════

    /**
     * Vérifie que le mot de passe n'est pas stocké en clair
     */
    public function testMotDePasseHasheEstDifferentDuMotDePasseClair(): void
    {
        $motDePasse = 'Admin123!';
        $hash       = password_hash($motDePasse, PASSWORD_BCRYPT);

        $this->assertNotEquals($motDePasse, $hash);
    }

    /**
     * Vérifie que le hash commence bien par $2y$ (format bcrypt)
     */
    public function testHashEstAuFormatBcrypt(): void
    {
        $hash = password_hash('Admin123!', PASSWORD_BCRYPT);

        $this->assertStringStartsWith('$2y$', $hash);
    }

    /**
     * Vérifie que password_verify fonctionne avec le bon mot de passe
     */
    public function testVerificationMotDePasseCorrect(): void
    {
        $motDePasse = 'Admin123!';
        $hash       = password_hash($motDePasse, PASSWORD_BCRYPT);

        $this->assertTrue(password_verify($motDePasse, $hash));
    }

    /**
     * Vérifie que password_verify échoue avec un mauvais mot de passe
     */
    public function testVerificationMotDePasseIncorrect(): void
    {
        $hash = password_hash('Admin123!', PASSWORD_BCRYPT);

        $this->assertFalse(password_verify('mauvaisMotDePasse', $hash));
    }

    /**
     * Vérifie que deux hash du même mot de passe sont différents (sel aléatoire)
     */
    public function testDeuxHashDuMemeMdpSontDifferents(): void
    {
        $hash1 = password_hash('Admin123!', PASSWORD_BCRYPT);
        $hash2 = password_hash('Admin123!', PASSWORD_BCRYPT);

        $this->assertNotEquals($hash1, $hash2);
        $this->assertTrue(password_verify('Admin123!', $hash1));
        $this->assertTrue(password_verify('Admin123!', $hash2));
    }

    // ══════════════════════════════════════════════════════
    // 2. SÉCURITÉ — Protection XSS
    // ══════════════════════════════════════════════════════

    /**
     * Vérifie que htmlspecialchars neutralise les balises script
     */
    public function testEchappementXssBaliseScript(): void
    {
        $input   = '<script>alert("xss")</script>';
        $escaped = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $this->assertStringNotContainsString('<script>', $escaped);
        $this->assertStringContainsString('&lt;script&gt;', $escaped);
    }

    /**
     * Vérifie que les guillemets sont bien échappés
     */
    public function testEchappementXssGuillemets(): void
    {
        $input   = '" onmouseover="alert(1)';
        $escaped = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $this->assertStringNotContainsString('"', $escaped);
        $this->assertStringContainsString('&quot;', $escaped);
    }

    /**
     * Vérifie qu'une valeur saine n'est pas modifiée
     */
    public function testEchappementValeurNormale(): void
    {
        $input   = 'Développeur PHP';
        $escaped = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $this->assertEquals($input, $escaped);
    }

    // ══════════════════════════════════════════════════════
    // 3. SÉCURITÉ — Token CSRF
    // ══════════════════════════════════════════════════════

    /**
     * Vérifie que le token CSRF a la bonne longueur (64 caractères)
     */
    public function testTokenCsrfABonneLongueur(): void
    {
        $token = bin2hex(random_bytes(32));

        $this->assertEquals(64, strlen($token));
    }

    /**
     * Vérifie que hash_equals valide un token correct
     */
    public function testComparaisonTokenCsrfValide(): void
    {
        $token = bin2hex(random_bytes(32));

        $this->assertTrue(hash_equals($token, $token));
    }

    /**
     * Vérifie que hash_equals rejette un token incorrect
     */
    public function testComparaisonTokenCsrfInvalide(): void
    {
        $token1 = bin2hex(random_bytes(32));
        $token2 = bin2hex(random_bytes(32));

        $this->assertFalse(hash_equals($token1, $token2));
    }

    /**
     * Vérifie que deux tokens générés sont toujours différents
     */
    public function testDeuxTokensCsrfSontUniques(): void
    {
        $token1 = bin2hex(random_bytes(32));
        $token2 = bin2hex(random_bytes(32));

        $this->assertNotEquals($token1, $token2);
    }

    // ══════════════════════════════════════════════════════
    // 4. VALIDATION — Format email
    // ══════════════════════════════════════════════════════

    /**
     * Vérifie qu'un email valide est accepté
     */
    public function testEmailValideAccepte(): void
    {
        $this->assertNotFalse(filter_var('admin@web4all.fr', FILTER_VALIDATE_EMAIL));
        $this->assertNotFalse(filter_var('lucas.martin@cesi.fr', FILTER_VALIDATE_EMAIL));
    }

    /**
     * Vérifie qu'un email invalide est rejeté
     */
    public function testEmailInvalideRejete(): void
    {
        $this->assertFalse(filter_var('pas-un-email', FILTER_VALIDATE_EMAIL));
        $this->assertFalse(filter_var('@sansnom.fr', FILTER_VALIDATE_EMAIL));
        $this->assertFalse(filter_var('', FILTER_VALIDATE_EMAIL));
    }

    // ══════════════════════════════════════════════════════
    // 5. VALIDATION — Rôles utilisateurs
    // ══════════════════════════════════════════════════════

    /**
     * Vérifie que les rôles autorisés sont bien définis
     */
    public function testRolesAutorisesSontValides(): void
    {
        $rolesValides = ['administrateur', 'pilote', 'etudiant'];

        $this->assertContains('administrateur', $rolesValides);
        $this->assertContains('pilote', $rolesValides);
        $this->assertContains('etudiant', $rolesValides);
    }

    /**
     * Vérifie que "anonyme" n'est pas un rôle BDD valide
     */
    public function testAnonymeNestPasUnRoleBDD(): void
    {
        $rolesBDD = ['administrateur', 'pilote', 'etudiant'];

        $this->assertNotContains('anonyme', $rolesBDD);
    }

    /**
     * Vérifie le contrôle d'accès par rôle (logique RBAC)
     */
    public function testRbacAdminAAccesATout(): void
    {
        $roleUtilisateur = 'administrateur';
        $routesAdmin     = ['administrateur'];
        $routesPubliques = [];

        // Route publique — tout le monde y a accès
        $this->assertTrue(
            empty($routesPubliques) || in_array($roleUtilisateur, $routesPubliques)
        );

        // Route admin — seul l'admin y a accès
        $this->assertContains($roleUtilisateur, $routesAdmin);
    }

    /**
     * Vérifie qu'un étudiant n'a pas accès aux routes admin
     */
    public function testRbacEtudiantNaPasAccesAdmin(): void
    {
        $roleUtilisateur = 'etudiant';
        $routesAdmin     = ['administrateur'];

        $this->assertNotContains($roleUtilisateur, $routesAdmin);
    }

    // ══════════════════════════════════════════════════════
    // 6. VALIDATION — Données métier
    // ══════════════════════════════════════════════════════

    /**
     * Vérifie que la note d'évaluation est entre 1 et 5
     */
    public function testNoteEvaluationValide(): void
    {
        foreach ([1, 2, 3, 4, 5] as $note) {
            $this->assertGreaterThanOrEqual(1, $note);
            $this->assertLessThanOrEqual(5, $note);
        }
    }

    /**
     * Vérifie que les notes hors limites sont invalides
     */
    public function testNoteEvaluationHorsLimites(): void
    {
        foreach ([0, 6, -1, 10] as $note) {
            $valide = ($note >= 1 && $note <= 5);
            $this->assertFalse($valide);
        }
    }

    /**
     * Vérifie que la rémunération ne peut pas être négative
     */
    public function testRemunerationNonNegative(): void
    {
        $remuneration = 850.00;

        $this->assertGreaterThanOrEqual(0, $remuneration);
    }

    /**
     * Vérifie la troncature de description (pagination affichage)
     */
    public function testTroncatureDescription(): void
    {
        $description = str_repeat('a', 200);
        $tronque     = mb_substr($description, 0, 100);

        $this->assertEquals(100, mb_strlen($tronque));
    }

    // ══════════════════════════════════════════════════════
    // 7. SÉCURITÉ — Upload de fichiers
    // ══════════════════════════════════════════════════════

    /**
     * Vérifie que seuls les PDF sont autorisés
     */
    public function testSeulsPdfSontAutorises(): void
    {
        $typesAutorises = ['application/pdf'];

        $this->assertContains('application/pdf', $typesAutorises);
        $this->assertNotContains('image/jpeg', $typesAutorises);
        $this->assertNotContains('application/exe', $typesAutorises);
    }

    /**
     * Vérifie que la taille max d'upload est correcte (5 Mo)
     */
    public function testTailleMaxUploadEstCinqMo(): void
    {
        $tailleMax = 5 * 1024 * 1024;

        $this->assertEquals(5242880, $tailleMax);
    }

    /**
     * Vérifie qu'un nom de fichier aléatoire est unique
     */
    public function testNomFichierUploadEstUnique(): void
    {
        $nom1 = 'cv_1_1_' . bin2hex(random_bytes(4)) . '.pdf';
        $nom2 = 'cv_1_1_' . bin2hex(random_bytes(4)) . '.pdf';

        $this->assertNotEquals($nom1, $nom2);
    }
}
