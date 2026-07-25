<?php
/**
 * models/Food.php
 */
require_once __DIR__ . '/../config/database.php';

class Food {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByRestaurant(int $restaurantId): array {
        $stmt = $this->db->prepare("SELECT f.*, c.name AS category_name FROM food_items f LEFT JOIN categories c ON c.id = f.category_id WHERE f.restaurant_id = :rid ORDER BY f.id DESC");
        $stmt->execute([':rid' => $restaurantId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT f.*, r.name AS restaurant_name, c.name AS category_name FROM food_items f JOIN restaurants r ON r.id = f.restaurant_id LEFT JOIN categories c ON c.id = f.category_id WHERE f.id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("INSERT INTO food_items (restaurant_id, category_id, name, description, price, image, is_veg, is_featured, status) VALUES (:rid, :cid, :name, :desc, :price, :img, :veg, :feat, :status)");
        return $stmt->execute([
            ':rid' => $data['restaurant_id'],
            ':cid' => $data['category_id'],
            ':name' => $data['name'],
            ':desc' => $data['description'] ?? '',
            ':price' => $data['price'],
            ':img' => $data['image'] ?? 'default-food.jpg',
            ':veg' => $data['is_veg'] ?? 0,
            ':feat' => $data['is_featured'] ?? 0,
            ':status' => $data['status'] ?? 'available'
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE food_items SET category_id = :cid, name = :name, description = :desc, price = :price, image = :img, is_veg = :veg, is_featured = :feat, status = :status WHERE id = :id");
        return $stmt->execute([
            ':cid' => $data['category_id'],
            ':name' => $data['name'],
            ':desc' => $data['description'] ?? '',
            ':price' => $data['price'],
            ':img' => $data['image'],
            ':veg' => $data['is_veg'] ?? 0,
            ':feat' => $data['is_featured'] ?? 0,
            ':status' => $data['status'] ?? 'available',
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM food_items WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
