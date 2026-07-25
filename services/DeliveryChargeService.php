<?php
/**
 * services/DeliveryChargeService.php
 * Calculates delivery fee based on distance, zone rules, surge pricing.
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

class DeliveryChargeService {

    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Calculate the delivery fee for an order.
     *
     * @param int   $restaurantId
     * @param float $orderTotal    Subtotal before fees
     * @param float $distanceKm    Distance from restaurant to customer (km)
     * @return array               ['fee' => float, 'is_free' => bool, 'reason' => string]
     */
    public function calculate(int $restaurantId, float $orderTotal, float $distanceKm = 0): array {
        $restaurant = $this->getRestaurantFeeSettings($restaurantId);

        // Check free delivery threshold
        $freeAbove = (float)($restaurant['free_delivery_above'] ?? 0);
        if ($freeAbove > 0 && $orderTotal >= $freeAbove) {
            return ['fee' => 0.0, 'is_free' => true, 'reason' => 'Free delivery on orders above ' . formatPrice($freeAbove)];
        }

        // Base fee from restaurant settings, fallback to app constant
        $fee = (float)($restaurant['delivery_fee'] ?? BASE_DELIVERY_FEE);

        // Distance-based surcharge: ₹1 per km above 3 km
        if ($distanceKm > 3) {
            $fee += round(($distanceKm - 3) * 1.0, 2);
        }

        // Apply surge multiplier if active
        $surge = $this->getActiveSurge();
        if ($surge > 1.0) {
            $fee = round($fee * $surge, 2);
        }

        return ['fee' => $fee, 'is_free' => false, 'reason' => $surge > 1.0 ? 'Surge pricing active' : ''];
    }

    /**
     * Haversine formula — calculate km between two lat/lng pairs.
     */
    public static function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float {
        $R = 6371; // Earth radius in km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /* ── Private helpers ────────────────────────────────── */

    private function getRestaurantFeeSettings(int $restaurantId): array {
        $stmt = $this->db->prepare("SELECT delivery_fee, min_order FROM restaurants WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $restaurantId]);
        return $stmt->fetch() ?: [];
    }

    /**
     * Return current surge multiplier (1.0 = no surge).
     * Could be driven by a DB table or time-of-day rules.
     */
    private function getActiveSurge(): float {
        $hour = (int)date('H');
        // Peak hours: 12–14 and 19–21 → 1.25x surcharge
        if (($hour >= 12 && $hour < 14) || ($hour >= 19 && $hour < 21)) {
            return 1.25;
        }
        return 1.0;
    }
}
