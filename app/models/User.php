<?php

declare(strict_types=1);

namespace app\models;

use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByUserName(string $userName): ?object
    {
        $sql = 'SELECT * FROM user WHERE user_name = :user_name AND deleted_at IS NULL LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_name' => $userName]);

        $row = $stmt->fetch();
        return $row ?: null;
    }
}
