<?php
/**
 * models/DeliveryPartner.php
 */
require_once __DIR__ . '/../config/database.php';

class DeliveryPartner {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAvailable(): array {
        $stmt = $this->db->query("SELECT u.id, u.name, u.phone, u.avatar FROM users u WHERE u.role = 'delivery' AND u.status = 'active'");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id AND role = 'delivery' LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }
}
