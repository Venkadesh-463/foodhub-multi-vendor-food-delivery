<?php
/**
 * models/OrderItem.php
 */
require_once __DIR__ . '/../config/database.php';

class OrderItem {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(int $orderId, int $foodId, int $quantity, float $price, float $subtotal): bool {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, food_id, quantity, price, subtotal) VALUES (:oid, :fid, :qty, :p, :sub)");
        return $stmt->execute([
            ':oid' => $orderId,
            ':fid' => $foodId,
            ':qty' => $quantity,
            ':p'   => $price,
            ':sub' => $subtotal
        ]);
    }

    public function getByOrder(int $orderId): array {
        $stmt = $this->db->prepare("SELECT oi.*, f.name, f.image FROM order_items oi JOIN food_items f ON f.id = oi.food_id WHERE oi.order_id = :oid");
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll();
    }
}
