<?php
/**
 * models/Notification.php
 */
require_once __DIR__ . '/../config/database.php';

class Notification {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getForUser(int $userId, int $limit = 20): array {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = :uid ORDER BY id DESC LIMIT :limit");
        $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markRead(int $id, int $userId): bool {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :uid");
        return $stmt->execute([':id' => $id, ':uid' => $userId]);
    }
}
