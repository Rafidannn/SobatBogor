<?php
/**
 * core/Model.php
 * Base Model: provides common DB query helpers (find, findAll, create, update, delete).
 * TODO: Implement in Tugas 1
 */
class Model {
    protected $table;
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    public function create(array $data): bool {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})");
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');

        $data['id'] = $id;

        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$fields} WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
