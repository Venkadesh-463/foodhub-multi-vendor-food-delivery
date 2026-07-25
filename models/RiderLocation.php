<?php
/**
 * models/RiderLocation.php
 */
require_once __DIR__ . '/../config/database.php';

class RiderLocation {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function update(int $riderId, float $lat, float $lng): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO rider_locations (rider_id, latitude, longitude, updated_at) VALUES (:rid, :lat, :lng, NOW()) ON DUPLICATE KEY UPDATE latitude = :lat2, longitude = :lng2, updated_at = NOW()"
        );
        return $stmt->execute([':rid' => $riderId, ':lat' => $lat, ':lat2' => $lat, ':lng' => $lng, ':lng2' => $lng]);
    }

    public function get(int $riderId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM rider_locations WHERE rider_id = :rid LIMIT 1");
        $stmt->execute([':rid' => $riderId]);
        return $stmt->fetch() ?: null;
    }
}
