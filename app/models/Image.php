<?php
namespace app\models;
use PDO;

class Image
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Retourne toutes les images correspondant aux conditions
     */
    public function findAll(array $conditions = [], array $options = []): array
    {
        $sql = "SELECT * FROM image";
        $params = [];
        $whereClauses = [];

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                if (is_null($value)) {
                    $whereClauses[] = "$key IS NULL";
                } else {
                    $whereClauses[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        if (!empty($options['order'])) {
            $sql .= " ORDER BY " . $options['order'];
        }
        if (!empty($options['limit'])) {
            $sql .= " LIMIT " . (int) $options['limit'];
        }

        error_log($sql);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    /**
     * Retourne une seule image
     */
    public function findOne(array $conditions): ?object
    {
        $results = $this->findAll($conditions, ['limit' => 1]);
        return $results[0] ?? null;
    }
}