<?php

declare(strict_types=1);

namespace Core;

use PDO;

/**
 * Modèle de base.
 * Fournit les méthodes CRUD génériques à tous les modèles.
 * Chaque modèle hérite de cette classe et définit $table et $fillable.
 */
abstract class Model
{
    protected PDO    $db;
    protected string $table      = '';
    protected string $primaryKey = 'id';
    protected array  $fillable   = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Lecture ───────────────────────────────────────────────────────────

    public function findAll(int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($limit > 0) $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        if ($limit > 0) {
            $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE {$this->primaryKey} = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBy(array $conditions): ?array
    {
        $where = implode(' AND ', array_map(fn($k) => "$k = :$k", array_keys($conditions)));
        $stmt  = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE $where LIMIT 1"
        );
        $stmt->execute(self::prefixKeys($conditions));
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // ── Écriture ──────────────────────────────────────────────────────────

    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        $cols = implode(', ', array_keys($data));
        $vals = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} ($cols) VALUES ($vals)"
        );
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        $set  = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET $set
             WHERE {$this->primaryKey} = :__id"
        );
        $data[':__id'] = $id;
        $params = [];
        foreach ($data as $k => $v) {
            $params[$k === ':__id' ? ':__id' : ":$k"] = $v;
        }
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table}
             WHERE {$this->primaryKey} = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    // ── Helpers pour les modèles enfants ──────────────────────────────────

    protected function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    protected function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    protected function queryValue(string $sql, array $params = []): mixed
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    // ── Privé ─────────────────────────────────────────────────────────────

    private function filterFillable(array $data): array
    {
        if (empty($this->fillable)) return $data;
        return array_intersect_key($data, array_flip($this->fillable));
    }

    private static function prefixKeys(array $arr): array
    {
        $result = [];
        foreach ($arr as $k => $v) {
            $result[':' . $k] = $v;
        }
        return $result;
    }
}
