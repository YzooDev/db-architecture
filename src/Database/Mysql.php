<?php

namespace App\Database;

class Mysql
{
    private static ?\PDO $instance = null;

    private function __construct() {}

    public static function connectBdd(): \PDO
    {
        if (self::$instance === null) {
            self::$instance = new \PDO(
                'mysql:host=' . $_ENV["DATABASE_HOST"] . ';dbname=' . $_ENV["DATABASE_NAME"] . ';charset=utf8mb4',
                $_ENV["DATABASE_USERNAME"],
                $_ENV["DATABASE_PASSWORD"],
                [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }
        return self::$instance;
    }
}
