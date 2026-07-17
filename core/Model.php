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

    public function findAll(): array { return []; }
    public function findById(int $id): ?array { return null; }
    public function create(array $data): bool { return false; }
    public function update(int $id, array $data): bool { return false; }
    public function delete(int $id): bool { return false; }
}
