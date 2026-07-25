<?php
/**
 * models/Category.php
 */
require_once __DIR__ . '/../config/database.php';

class Category {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $icon = 'fa-utensils', string $image = 'cat-default.jpg'): bool {
        $stmt = $this->db->prepare("INSERT INTO categories (name, icon, image) VALUES (:n, :i, :img)");
        return $stmt->execute([':n' => $name, ':i' => $icon, ':img' => $image]);
    }

    public function update(int $id, string $name, string $icon, string $image): bool {
        $stmt = $this->db->prepare("UPDATE categories SET name = :n, icon = :i, image = :img WHERE id = :id");
        return $stmt->execute([':n' => $name, ':i' => $icon, ':img' => $image, ':id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
