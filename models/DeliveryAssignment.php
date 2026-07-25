<?php
/**
 * models/DeliveryAssignment.php
 */
require_once __DIR__ . '/../config/database.php';

class DeliveryAssignment {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByDriver(int $driverId): array {
        $stmt = $this->db->prepare("SELECT da.*, o.order_number, o.total_amount, o.delivery_address, r.name AS restaurant_name, r.address AS restaurant_address FROM delivery_assignments da JOIN orders o ON o.id = da.order_id JOIN restaurants r ON r.id = o.restaurant_id WHERE da.driver_id = :did ORDER BY da.id DESC");
        $stmt->execute([':did' => $driverId]);
        return $stmt->fetchAll();
    }

    public function assign(int $orderId, int $driverId): bool {
        $stmt = $this->db->prepare("INSERT INTO delivery_assignments (order_id, driver_id, status) VALUES (:oid, :did, 'assigned')");
        return $stmt->execute([':oid' => $orderId, ':did' => $driverId]);
    }

    public function updateStatus(int $orderId, string $status): bool {
        $sql = "UPDATE delivery_assignments SET status = :s";
        if ($status === 'delivered') $sql .= ", delivered_at = NOW()";
        $sql .= " WHERE order_id = :oid";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':s' => $status, ':oid' => $orderId]);
    }
}
