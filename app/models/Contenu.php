<?php
namespace app\models;

use PDO;
use app\utils\FileCache;

class Contenu
{
    private PDO $db;
    private FileCache $cache;
    private const LIST_CACHE_TTL = 5;
    private const FRONT_CACHE_TTL = 5;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->cache = new FileCache();
    }

    public function findOne(array $conditions): ?object
    {
        $sql = "SELECT * FROM content WHERE ";
        $params = [];
        $whereClauses = [];

        if (!array_key_exists('deleted_at', $conditions)) {
            $whereClauses[] = 'deleted_at IS NULL';
        }

        foreach ($conditions as $key => $value) {
            if (is_null($value)) {
                $whereClauses[] = "$key IS NULL";
            } else {
                $whereClauses[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        $sql .= implode(' AND ', $whereClauses);
        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findAll(array $conditions = [], array $options = []): array
    {
        $sql = "SELECT * FROM content";
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
        if (!empty($options['offset'])) {
            $sql .= " OFFSET " . (int) $options['offset'];
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id, bool $includeDeleted = false): ?object
    {
        $sql = 'SELECT * FROM content WHERE id = :id';
        $params = [':id' => $id];

        if (!$includeDeleted) {
            $sql .= ' AND deleted_at IS NULL';
        }

        $sql .= ' LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function searchBySectionWithPagination(int $sectionId, array $filters, int $page, int $perPage): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $hash = md5(json_encode([$sectionId, $filters, $page, $perPage]));
        $listKey = 'content_list_' . $hash;

        $items = $this->cache->remember($listKey, self::LIST_CACHE_TTL, function () use ($sectionId, $filters, $page, $perPage): array {
            $params = [':section_id' => $sectionId];
            $whereSql = $this->buildSearchWhereClause($filters, $params);
            $offset = ($page - 1) * $perPage;

            $sql = 'SELECT id, section_id, title, summary, meta_title, slug, meta_description, created_at, updated_at'
                . ' FROM content'
                . ' WHERE section_id = :section_id'
                . ($whereSql !== '' ? (' AND ' . $whereSql) : '')
                . ' ORDER BY updated_at DESC, id DESC'
                . ' LIMIT :limit OFFSET :offset';

            $stmt = $this->db->prepare($sql);
            foreach ($params as $paramKey => $paramValue) {
                $stmt->bindValue($paramKey, $paramValue);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        });

        $totalKey = 'content_count_' . md5(json_encode([$sectionId, $filters]));
        $total = $this->cache->remember($totalKey, self::LIST_CACHE_TTL, function () use ($sectionId, $filters): int {
            $params = [':section_id' => $sectionId];
            $whereSql = $this->buildSearchWhereClause($filters, $params);
            $sql = 'SELECT COUNT(*) FROM content WHERE section_id = :section_id'
                . ($whereSql !== '' ? (' AND ' . $whereSql) : '');

            $stmt = $this->db->prepare($sql);
            foreach ($params as $paramKey => $paramValue) {
                $stmt->bindValue($paramKey, $paramValue);
            }
            $stmt->execute();

            return (int) $stmt->fetchColumn();
        });

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    public function getFrontArticlesBySectionId(int $sectionId): array
    {
        $cacheKey = 'front_articles_section_' . $sectionId;

        return $this->cache->remember($cacheKey, self::FRONT_CACHE_TTL, function () use ($sectionId): array {
            $sql = 'SELECT * FROM content'
                . ' WHERE section_id = :section_id AND deleted_at IS NULL'
                . ' ORDER BY created_at DESC, id DESC';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':section_id' => $sectionId]);

            return $stmt->fetchAll();
        });
    }

    public function getFrontArticleById(int $id): ?object
    {
        $cacheKey = 'front_article_' . $id;

        $article = $this->cache->remember($cacheKey, self::FRONT_CACHE_TTL, function () use ($id) {
            return $this->findById($id, false);
        });

        return $article instanceof \stdClass ? $article : ($article ?: null);
    }

    public function createContent(int $sectionId, array $payload): int
    {
        $sql = 'INSERT INTO content (section_id, title, summary, content_text, meta_title, slug, meta_description)'
            . ' VALUES (:section_id, :title, :summary, :content_text, :meta_title, :slug, :meta_description)';

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            ':section_id' => $sectionId,
            ':title' => $payload['title'],
            ':summary' => $payload['summary'],
            ':content_text' => $payload['content_text'],
            ':meta_title' => $payload['meta_title'],
            ':slug' => $payload['slug'],
            ':meta_description' => $payload['meta_description'],
        ]);

        if ($ok) {
            $this->clearListCaches();
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }

    public function updateContent(int $id, array $payload): bool
    {
        $sql = 'UPDATE content'
            . ' SET title = :title, summary = :summary, content_text = :content_text,'
            . ' meta_title = :meta_title, slug = :slug, meta_description = :meta_description,'
            . ' updated_at = CURRENT_TIMESTAMP'
            . ' WHERE id = :id  ';

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            ':title' => $payload['title'],
            ':summary' => $payload['summary'],
            ':content_text' => $payload['content_text'],
            ':meta_title' => $payload['meta_title'],
            ':slug' => $payload['slug'],
            ':meta_description' => $payload['meta_description'],
            ':id' => $id,
        ]);

        if ($ok) {
            $this->clearListCaches();
            return true;
        }

        return false;
    }

    public function softDelete(int $id): bool
    {
        $sql = 'UPDATE content SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP'
            . ' WHERE id = :id  ';

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([':id' => $id]);

        if ($ok && $stmt->rowCount() > 0) {
            $this->clearListCaches();
            return true;
        }

        return false;
    }

    public function generateUniqueSlug(string $text, ?int $excludeId = null): string
    {
        $baseSlug = $this->slugify($text);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'contenu';

        $candidate = $baseSlug;
        $counter = 1;

        while ($this->slugExists($candidate, $excludeId)) {
            $candidate = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $candidate;
    }

    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM content WHERE slug = :slug';
        $params = [':slug' => $slug];

        if ($excludeId !== null) {
            $sql .= ' AND id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function slugify(string $value): string
    {
        $value = trim($value);
        $transliterated = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if (is_string($transliterated)) {
            $value = $transliterated;
        }

        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        $value = trim($value, '-');

        return $value;
    }

    private function buildSearchWhereClause(array $filters, array &$params): string
    {
        $clauses = ['deleted_at IS NULL'];

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $params[':keyword'] = '%' . $this->escapeLike($keyword) . '%';
            $clauses[] = '(title LIKE :keyword OR meta_title LIKE :keyword OR slug LIKE :keyword)';
        }

        $title = trim((string) ($filters['title'] ?? ''));
        if ($title !== '') {
            $params[':title'] = '%' . $this->escapeLike($title) . '%';
            $clauses[] = 'title LIKE :title';
        }

        $slug = trim((string) ($filters['slug'] ?? ''));
        if ($slug !== '') {
            $params[':slug'] = '%' . $this->escapeLike($slug) . '%';
            $clauses[] = 'slug LIKE :slug';
        }

        return implode(' AND ', $clauses);
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }

    private function clearListCaches(): void
    {
        $this->cache->forgetByPrefix('section_list');
        $this->cache->forgetByPrefix('content_list');
        $this->cache->forgetByPrefix('content_count');
        $this->cache->forgetByPrefix('front_article');
        $this->cache->forgetByPrefix('front_articles_section');
        $this->cache->forgetByPrefix('front_images_content');
    }


}