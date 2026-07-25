<?php
/**
 * services/RiderAssignmentService.php
 * Assigns the best available rider to a new order.
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../services/NotificationService.php';

class RiderAssignmentService {

    private \PDO $db;
    private NotificationService $notif;

    public function __construct() {
        $this->db    = Database::getInstance()->getConnection();
        $this->notif = new NotificationService();
    }

    /**
     * Auto-assign the nearest available rider to an order.
     *
     * @param int $orderId
     * @return array ['success' => bool, 'rider_id' => int|null, 'message' => string]
     */
    public function autoAssign(int $orderId): array {
        $order = $this->getOrder($orderId);
        if (!$order) return ['success' => false, 'rider_id' => null, 'message' => 'Order not found.'];

        $rider = $this->findNearestRider($order['restaurant_id']);
        if (!$rider) return ['success' => false, 'rider_id' => null, 'message' => 'No available riders at this time.'];

        return $this->assign($orderId, (int)$rider['id']);
    }

    /**
     * Manually assign a specific rider to an order.
     */
    public function assign(int $orderId, int $riderId): array {
        // Check rider is free
        $busy = $this->db->prepare("SELECT id FROM delivery_assignments WHERE driver_id = :rid AND status IN ('assigned','picked_up')");
        $busy->execute([':rid' => $riderId]);
        if ($busy->fetch()) {
            return ['success' => false, 'rider_id' => $riderId, 'message' => 'Rider is currently busy.'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO delivery_assignments (order_id, driver_id, status, assigned_at)
             VALUES (:oid, :rid, 'assigned', NOW())"
        );
        $ok = $stmt->execute([':oid' => $orderId, ':rid' => $riderId]);

        if ($ok) {
            // Update order status
            $this->db->prepare("UPDATE orders SET order_status = 'out_for_delivery' WHERE id = :oid")
                ->execute([':oid' => $orderId]);

            // Notify the rider
            $this->notif->send($riderId, 'New Delivery Assignment', "You have been assigned to Order #$orderId. Please pick up the order.", 'delivery', $orderId);

            // Notify the customer
            $order = $this->getOrder($orderId);
            if ($order) {
                $this->notif->send((int)$order['user_id'], 'Rider Assigned!', 'A delivery rider is on the way to pick up your order.', 'delivery', $orderId);
            }
        }

        return ['success' => $ok, 'rider_id' => $riderId, 'message' => $ok ? 'Rider assigned successfully.' : 'Failed to assign rider.'];
    }

    /**
     * Update delivery status (assigned → picked_up → delivered).
     */
    public function updateStatus(int $orderId, int $riderId, string $status): bool {
        $allowed = ['assigned', 'picked_up', 'delivered'];
        if (!in_array($status, $allowed, true)) return false;

        $stmt = $this->db->prepare("UPDATE delivery_assignments SET status = :s WHERE order_id = :oid AND driver_id = :rid");
        $ok   = $stmt->execute([':s' => $status, ':oid' => $orderId, ':rid' => $riderId]);

        if ($ok && $status === 'delivered') {
            $this->db->prepare("UPDATE orders SET order_status = 'delivered', updated_at = NOW() WHERE id = :oid")->execute([':oid' => $orderId]);
            $this->db->prepare("UPDATE delivery_assignments SET delivered_at = NOW() WHERE order_id = :oid")->execute([':oid' => $orderId]);
            $order = $this->getOrder($orderId);
            if ($order) {
                $this->notif->send((int)$order['user_id'], 'Order Delivered! 🎉', 'Your order has been delivered. Enjoy your meal!', 'order', $orderId);
            }
        }

        return $ok;
    }

    /* ── Private helpers ────────────────────────────────── */

    private function getOrder(int $orderId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $orderId]);
        return $stmt->fetch() ?: null;
    }

    private function findNearestRider(int $restaurantId): ?array {
        // Simplistic: pick any active delivery user not currently assigned
        $stmt = $this->db->query(
            "SELECT u.id, u.name FROM users u
             WHERE u.role = 'delivery' AND u.status = 'active'
             AND u.id NOT IN (
                 SELECT driver_id FROM delivery_assignments WHERE status IN ('assigned','picked_up')
             )
             LIMIT 1"
        );
        return $stmt->fetch() ?: null;
    }
}
