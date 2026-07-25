<?php
/**
 * models/Order.php
 */
require_once __DIR__ . '/../config/database.php';

class Order {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT o.*, r.name AS restaurant_name, r.image AS restaurant_image FROM orders o JOIN restaurants r ON r.id = o.restaurant_id WHERE o.user_id = :uid ORDER BY o.id DESC");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getByRestaurantId(int $restaurantId, string $status = ''): array {
        $sql = "SELECT o.*, u.name AS customer_name, u.phone AS customer_phone FROM orders o JOIN users u ON u.id = o.user_id WHERE o.restaurant_id = :rid";
        if (!empty($status)) {
            $sql .= " AND o.order_status = :status";
        }
        $sql .= " ORDER BY o.id DESC";
        $stmt = $this->db->prepare($sql);
        $params = [':rid' => $restaurantId];
        if (!empty($status)) $params[':status'] = $status;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT o.*, r.name AS restaurant_name, r.phone AS restaurant_phone, u.name AS customer_name, u.email AS customer_email, u.phone AS customer_phone FROM orders o JOIN restaurants r ON r.id = o.restaurant_id JOIN users u ON u.id = o.user_id WHERE o.id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getItems(int $orderId): array {
        $stmt = $this->db->prepare("SELECT oi.*, f.name AS food_name, f.image AS food_image FROM order_items oi JOIN food_items f ON f.id = oi.food_id WHERE oi.order_id = :oid");
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE orders SET order_status = :status, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }
}
