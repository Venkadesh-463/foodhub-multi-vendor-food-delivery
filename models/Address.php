<?php
/**
 * models/Address.php
 */
require_once __DIR__ . '/../config/database.php';

class Address {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureTable();
    }

    private function ensureTable(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `addresses` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL,
                `title` VARCHAR(50) DEFAULT 'Home',
                `address_line` TEXT NOT NULL,
                `city` VARCHAR(100) DEFAULT '',
                `postal_code` VARCHAR(20) DEFAULT '',
                `is_default` TINYINT(1) DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM addresses WHERE user_id = :uid ORDER BY is_default DESC, id DESC");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function add(int $userId, string $title, string $addressLine, string $city = '', string $postalCode = '', bool $isDefault = false): bool {
        if ($isDefault) {
            $this->db->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = :uid")->execute([':uid' => $userId]);
        }
        $stmt = $this->db->prepare("INSERT INTO addresses (user_id, title, address_line, city, postal_code, is_default) VALUES (:uid, :t, :a, :c, :p, :d)");
        return $stmt->execute([
            ':uid' => $userId,
            ':t' => $title,
            ':a' => $addressLine,
            ':c' => $city,
            ':p' => $postalCode,
            ':d' => $isDefault ? 1 : 0
        ]);
    }

    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM addresses WHERE id = :id AND user_id = :uid");
        return $stmt->execute([':id' => $id, ':uid' => $userId]);
    }
}
