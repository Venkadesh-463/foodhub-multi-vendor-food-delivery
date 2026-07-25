<?php
/**
 * models/Payment.php & models/Transaction.php & models/Notification.php
 */
require_once __DIR__ . '/../config/database.php';

class Payment {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function record(int $orderId, string $txnId, string $method, float $amount, string $status = 'success'): bool {
        $stmt = $this->db->prepare("INSERT INTO payments (order_id, transaction_id, payment_method, amount, status) VALUES (:oid, :txn, :m, :amt, :s)");
        return $stmt->execute([':oid' => $orderId, ':txn' => $txnId, ':m' => $method, ':amt' => $amount, ':s' => $status]);
    }

    public function getByOrder(int $orderId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE order_id = :oid LIMIT 1");
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetch() ?: null;
    }
}
