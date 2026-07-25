<?php
/**
 * models/FoodReview.php
 */
require_once __DIR__ . '/../config/database.php';

class FoodReview {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByRestaurant(int $restaurantId): array {
        $stmt = $this->db->prepare("SELECT r.*, u.name AS user_name, u.avatar FROM reviews r JOIN users u ON u.id = r.user_id WHERE r.restaurant_id = :rid ORDER BY r.id DESC");
        $stmt->execute([':rid' => $restaurantId]);
        return $stmt->fetchAll();
    }

    public function add(int $userId, int $restaurantId, int $rating, string $comment = ''): bool {
        $stmt = $this->db->prepare("INSERT INTO reviews (user_id, restaurant_id, rating, comment) VALUES (:uid, :rid, :rat, :c)");
        return $stmt->execute([':uid' => $userId, ':rid' => $restaurantId, ':rat' => $rating, ':c' => $comment]);
    }
}
