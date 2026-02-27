<?php
/**
 * Database connection using PDO (PHP 7.4+)
 * 
 * Author: untakebtw
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */

function getDb(array $config): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $db = $config['DB'];
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $db['host'],
        $db['port'] ?? 3306,
        $db['database']
    );

    $pdo = new PDO($dsn, $db['user'], $db['password'] ?? '', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    return $pdo;
}
