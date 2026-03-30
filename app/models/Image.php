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

    public function createImage(int $contentId, array $payload): int
    {
        $sql = 'INSERT INTO image (content_id, url, alt_text, display_order)'
            . ' VALUES (:content_id, :url, :alt_text, :display_order)';

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            ':content_id' => $contentId,
            ':url' => $payload['url'],
            ':alt_text' => $payload['alt_text'] ?? null,
            ':display_order' => (int) ($payload['display_order'] ?? 0),
        ]);

        if ($ok) {
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }

    public function softDeleteByContentId(int $contentId): bool
    {
        $sql = 'UPDATE image SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP'
            . ' WHERE content_id = :content_id AND deleted_at IS NULL';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':content_id' => $contentId]);
    }
}