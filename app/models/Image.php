<?php
namespace app\models;
use PDO;
use app\utils\FileCache;

class Image
{
    private PDO $db;
    private FileCache $cache;
    private const FRONT_CACHE_TTL = 120;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->cache = new FileCache();
    }

    /**
     * Retourne toutes les images correspondant aux conditions
     */
    public function findAll(array $conditions = [], array $options = []): array
    {
        $sql = "SELECT * FROM image";
        $params = [];
        $whereClauses = [];

        if (!array_key_exists('deleted_at', $conditions)) {
            $whereClauses[] = 'deleted_at IS NULL';
        }

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                if (is_null($value)) {
                    $whereClauses[] = "$key IS NULL";
                } else {
                    $whereClauses[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
        }

        if (!empty($whereClauses)) {
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
            $this->clearFrontCachesForContent($contentId);
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }

    public function softDeleteByContentId(int $contentId): bool
    {
        $sql = 'UPDATE image SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP'
            . ' WHERE content_id = :content_id  ';

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([':content_id' => $contentId]);

        if ($ok) {
            $this->clearFrontCachesForContent($contentId);
        }

        return $ok;
    }

    public function getFrontImagesByContentId(int $contentId): array
    {
        $cacheKey = 'front_images_content_' . $contentId;

        return $this->cache->remember($cacheKey, self::FRONT_CACHE_TTL, function () use ($contentId): array {
            return $this->findAll([
                'content_id' => $contentId,
                'deleted_at' => null,
            ], ['order' => 'display_order ASC, id ASC']);
        });
    }

    private function clearFrontCachesForContent(int $contentId): void
    {
        $this->cache->forgetByPrefix('front_images_content_' . $contentId);
        $this->cache->forgetByPrefix('front_article_' . $contentId);
        $this->cache->forgetByPrefix('front_articles_section_');
    }
}