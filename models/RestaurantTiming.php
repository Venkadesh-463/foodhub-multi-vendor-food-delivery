<?php
/**
 * models/RestaurantTiming.php
 */
require_once __DIR__ . '/../config/database.php';

class RestaurantTiming {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureTable();
    }

    private function ensureTable(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `restaurant_timings` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `restaurant_id` INT NOT NULL,
                `day_of_week` ENUM('Mon','Tue','Wed','Thu','Fri','Sat','Sun') NOT NULL,
                `open_time` TIME DEFAULT '09:00:00',
                `close_time` TIME DEFAULT '22:00:00',
                `is_closed` TINYINT(1) DEFAULT 0,
                FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function getByRestaurant(int $restaurantId): array {
        $stmt = $this->db->prepare("SELECT * FROM restaurant_timings WHERE restaurant_id = :rid");
        $stmt->execute([':rid' => $restaurantId]);
        return $stmt->fetchAll();
    }

    public function saveTiming(int $restaurantId, string $day, string $open, string $close, bool $isClosed): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO restaurant_timings (restaurant_id, day_of_week, open_time, close_time, is_closed)
             VALUES (:rid, :day, :open, :close, :closed)
             ON DUPLICATE KEY UPDATE open_time = :open2, close_time = :close2, is_closed = :closed2"
        );
        return $stmt->execute([
            ':rid' => $restaurantId, ':day' => $day,
            ':open' => $open, ':open2' => $open,
            ':close' => $close, ':close2' => $close,
            ':closed' => $isClosed ? 1 : 0, ':closed2' => $isClosed ? 1 : 0
        ]);
    }
}
