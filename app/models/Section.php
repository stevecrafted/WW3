<?php
namespace app\models;

use PDO;
use app\utils\FileCache;

class Section
{
    private PDO $db;
    private FileCache $cache;
    private const LIST_CACHE_TTL = 120;
    private const FRONT_CACHE_TTL = 120;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->cache = new FileCache();
    }

    public function findOne(array $conditions): ?object
    {
        $sql = "SELECT * FROM section WHERE ";
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
        $sql = "SELECT * FROM section";
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
            $sql .= " LIMIT " . (int)$options['limit'];
        }
        if (!empty($options['offset'])) {
            $sql .= " OFFSET " . (int)$options['offset'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id, bool $includeDeleted = false): ?object
    {
        $sql = 'SELECT * FROM section WHERE id = :id';
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

    public function searchWithPagination(array $filters, int $page, int $perPage): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $hash = md5(json_encode([$filters, $page, $perPage]));
        $listCacheKey = 'section_list_' . $hash;

        $items = $this->cache->remember($listCacheKey, self::LIST_CACHE_TTL, function () use ($filters, $page, $perPage): array {
            $params = [];
            $whereSql = $this->buildSearchWhereClause($filters, $params);
            $offset = ($page - 1) * $perPage;

            $sql = 'SELECT id, name, slug, title, created_at, updated_at'
                . ' FROM section'
                . ($whereSql !== '' ? (' WHERE ' . $whereSql) : '')
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

        $totalCacheKey = 'section_count_' . md5(json_encode($filters));
        $total = $this->cache->remember($totalCacheKey, self::LIST_CACHE_TTL, function () use ($filters): int {
            $params = [];
            $whereSql = $this->buildSearchWhereClause($filters, $params);
            $sql = 'SELECT COUNT(*) FROM section'
                . ($whereSql !== '' ? (' WHERE ' . $whereSql) : '');

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

    public function getFrontSections(): array
    {
        return $this->cache->remember('front_sections_nav', self::FRONT_CACHE_TTL, function (): array {
            return $this->findAll([], ['order' => 'updated_at DESC, id DESC']);
        });
    }

    public function createSection(array $payload): int
    {
        $sql = 'INSERT INTO section (name, slug, title) VALUES (:name, :slug, :title)';
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            ':name' => $payload['name'],
            ':slug' => $payload['slug'],
            ':title' => $payload['title'],
        ]);

        if ($ok) {
            $this->clearListCaches();
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }

    public function updateSection(int $id, array $payload): bool
    {
        $sql = 'UPDATE section'
            . ' SET name = :name, slug = :slug, title = :title, updated_at = CURRENT_TIMESTAMP'
            . ' WHERE id = :id  ';

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            ':name' => $payload['name'],
            ':slug' => $payload['slug'],
            ':title' => $payload['title'],
            ':id' => $id,
        ]);

        if ($ok && $stmt->rowCount() > 0) {
            $this->clearListCaches();
            return true;
        }

        return false;
    }

    public function softDelete(int $id): bool
    {
        $sql = 'UPDATE section SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP'
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
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'section';

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
        $sql = 'SELECT COUNT(*) FROM section WHERE slug = :slug';
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
            $clauses[] = '(name LIKE :keyword OR title LIKE :keyword OR slug LIKE :keyword)';
        }

        $name = trim((string) ($filters['name'] ?? ''));
        if ($name !== '') {
            $params[':name'] = '%' . $this->escapeLike($name) . '%';
            $clauses[] = 'name LIKE :name';
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
        $this->cache->forgetByPrefix('section_list_');
        $this->cache->forgetByPrefix('section_count_');
        $this->cache->forgetByPrefix('front_sections_');
    }
}