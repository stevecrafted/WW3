<?php

namespace app\models;

use PDO;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $configPath = dirname(__DIR__) . '/config/config.php';
        $config = require $configPath;
        $dbConfig = $config['database'] ?? [];

        if (!empty($dbConfig['file_path'])) {
            $dsn = 'sqlite:' . $dbConfig['file_path'];
            $username = null;
            $password = null;
        } else {
            $host = $dbConfig['host'] ?? 'localhost';
            $dbname = $dbConfig['dbname'] ?? '';
            $charset = $dbConfig['charset'] ?? 'utf8mb4';
            $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=' . $charset;
            $username = $dbConfig['user'] ?? null;
            $password = $dbConfig['password'] ?? null;
        }

        self::$connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ]);

        return self::$connection;
    }
}
