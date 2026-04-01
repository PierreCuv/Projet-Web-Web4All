<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

/**
 * Singleton PDO.
 * Une seule connexion BDD est créée pour toute la durée de la requête HTTP.
 */
class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require ROOT_PATH . '/config/database.php';

            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=%s',
                $config['driver'],
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                if (APP_ENV === 'development') {
                    die('Erreur de connexion BDD : ' . $e->getMessage());
                }
                die('Une erreur technique est survenue.');
            }
        }

        return self::$instance;
    }
}
