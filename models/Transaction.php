<?php
/**
 * models/Transaction.php
 */
require_once __DIR__ . '/../config/database.php';

class Transaction {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(int $limit = 50): array {
        $stmt = $this->db->prepare("SELECT p.*, o.order_number, u.name AS customer_name FROM payments p JOIN orders o ON o.id = p.order_id JOIN users u ON u.id = o.user_id ORDER BY p.id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
