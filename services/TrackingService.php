<?php
/**
 * services/TrackingService.php
 * Stores and retrieves rider GPS coordinates for live order tracking.
 */
require_once __DIR__ . '/../config/database.php';

class TrackingService {

    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureTableExists();
    }

    /**
     * Update (or insert) the rider's current GPS location.
     *
     * @param int   $riderId
     * @param float $lat
     * @param float $lng
     */
    public function updateLocation(int $riderId, float $lat, float $lng): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO rider_locations (rider_id, latitude, longitude, updated_at)
             VALUES (:rid, :lat, :lng, NOW())
             ON DUPLICATE KEY UPDATE latitude = :lat2, longitude = :lng2, updated_at = NOW()"
        );
        return $stmt->execute([
            ':rid'  => $riderId,
            ':lat'  => $lat,  ':lat2' => $lat,
            ':lng'  => $lng,  ':lng2' => $lng,
        ]);
    }

    /**
     * Get the current location of a rider.
     *
     * @return array|null ['latitude', 'longitude', 'updated_at']
     */
    public function getRiderLocation(int $riderId): ?array {
        $stmt = $this->db->prepare("SELECT latitude, longitude, updated_at FROM rider_locations WHERE rider_id = :rid LIMIT 1");
        $stmt->execute([':rid' => $riderId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get the rider assigned to an order, plus their current location.
     *
     * @return array|null ['rider_id', 'rider_name', 'latitude', 'longitude', 'updated_at']
     */
    public function getOrderRiderLocation(int $orderId): ?array {
        $stmt = $this->db->prepare(
            "SELECT da.driver_id AS rider_id, u.name AS rider_name, rl.latitude, rl.longitude, rl.updated_at
             FROM delivery_assignments da
             JOIN users u           ON u.id  = da.driver_id
             LEFT JOIN rider_locations rl ON rl.rider_id = da.driver_id
             WHERE da.order_id = :oid AND da.status IN ('assigned','picked_up')
             LIMIT 1"
        );
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Estimate remaining delivery time (minutes) based on distance.
     * Uses 30 km/h average urban speed.
     */
    public function estimateETA(float $distanceKm): int {
        return (int)ceil(($distanceKm / 30) * 60);
    }

    /* ── Table bootstrap ────────────────────────────────── */

    private function ensureTableExists(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `rider_locations` (
                `rider_id`   INT PRIMARY KEY,
                `latitude`   DECIMAL(10,7) NOT NULL,
                `longitude`  DECIMAL(10,7) NOT NULL,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`rider_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }
}
