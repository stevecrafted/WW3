<?php
namespace app\models;

use PDO;

class Section
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findOne(array $conditions): ?object
    {
        $sql = "SELECT * FROM section WHERE ";
        $params = [];
        foreach ($conditions as $key => $value) {
            $sql .= "$key = :$key AND ";
            $params[":$key"] = $value;
        }
        $sql = rtrim($sql, 'AND ');
        $sql .= " LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findAll(array $conditions = [], array $options = []): array
    {
        $sql = "SELECT * FROM section";
        $params = [];
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            foreach ($conditions as $key => $value) {
                $sql .= "$key = :$key AND ";
                $params[":$key"] = $value;
            }
            $sql = rtrim($sql, 'AND ');
        }
        if (!empty($options['order'])) {
            $sql .= " ORDER BY " . $options['order'];
        }
        if (!empty($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}