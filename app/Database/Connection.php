<?php
namespace App\Database;

use PDO;
use PDOException;

final class Connection
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host    = getenv('DB_HOST') ?: 'localhost';
        $port    = getenv('DB_PORT') ?: '3306';
        $db      = getenv('DB_NAME') ?: 'db_minhacarteira';
        $user    = getenv('DB_USER') ?: 'db_user_app';
        $pass    = getenv('DB_PASS') ?: '1234';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $db, $charset);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            self::$pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log('[DB ERROR] ' . $e->getMessage());
            exit('Falha na conexão ao banco. Verifique configuração.');
        }

        return self::$pdo;
    }
}
