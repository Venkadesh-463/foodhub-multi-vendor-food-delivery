<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Cart.php';

class Order {
    private $db;
    private $cartModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->cartModel = new Cart();
    }

    public function createOrder($userId, $deliveryAddress, $paymentMethod, $specialInstructions = '') {
        $totals = $this->cartModel->getTotals($userId);
        if ($totals['count'] === 0) {
            return ['success' => false, 'message' => 'Your cart is empty.'];
        }

        $items = $totals['items'];
        $restaurantId = $items[0]['restaurant_id']; // primary restaurant
        $orderNumber = 'FH-' . date('Y') . '-' . rand(10000, 99999);

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO orders 
                (order_number, user_id, restaurant_id, total_amount, delivery_fee, tax_amount, discount_amount, final_amount, delivery_address, payment_method, payment_status, order_status, special_instructions) 
                VALUES (?, ?, ?, ?, ?, ?, 0.00, ?, ?, ?, 'paid', 'pending', ?)");

            $stmt->execute([
                $orderNumber,
                $userId,
                $restaurantId,
                $totals['subtotal'],
                $totals['delivery_fee'],
                $totals['tax'],
                $totals['grand_total'],
                $deliveryAddress,
                $paymentMethod,
                $specialInstructions
            ]);

            $orderId = $this->db->lastInsertId();

            // Insert Order Items
            $itemStmt = $this->db->prepare("INSERT INTO order_items (order_id, food_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
            foreach ($items as $item) {
                $sub = $item['price'] * $item['quantity'];
                $itemStmt->execute([$orderId, $item['id'], $item['quantity'], $item['price'], $sub]);
            }

            // Create Payment entry
            $payStmt = $this->db->prepare("INSERT INTO payments (order_id, transaction_id, payment_method, amount, status) VALUES (?, ?, ?, ?, 'success')");
            $payStmt->execute([$orderId, 'TXN-' . strtoupper(substr(md5(uniqid()), 0, 10)), $paymentMethod, $totals['grand_total']]);

            // Assign driver automatically if driver exists
            $driverStmt = $this->db->query("SELECT id FROM users WHERE role = 'delivery' AND status = 'active' LIMIT 1");
            $driver = $driverStmt->fetch();
            if ($driver) {
                $assignStmt = $this->db->prepare("INSERT INTO delivery_assignments (order_id, driver_id, status) VALUES (?, ?, 'assigned')");
                $assignStmt->execute([$orderId, $driver['id']]);
            }

            $this->db->commit();
            $this->cartModel->clearCart($userId);

            return ['success' => true, 'order_id' => $orderId, 'order_number' => $orderNumber];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Failed to process order: ' . $e->getMessage()];
        }
    }

    public function getOrderById($orderId) {
        $stmt = $this->db->prepare("SELECT o.*, u.name as customer_name, u.phone as customer_phone, u.email as customer_email, r.name as restaurant_name, r.phone as restaurant_phone, r.address as restaurant_address 
                                   FROM orders o 
                                   JOIN users u ON o.user_id = u.id 
                                   JOIN restaurants r ON o.restaurant_id = r.id 
                                   WHERE o.id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        if ($order) {
            $itemStmt = $this->db->prepare("SELECT oi.*, f.name as food_name, f.image as food_image 
                                           FROM order_items oi 
                                           JOIN food_items f ON oi.food_id = f.id 
                                           WHERE oi.order_id = ?");
            $itemStmt->execute([$orderId]);
            $order['items'] = $itemStmt->fetchAll();

            // Driver assignment info if any
            $driverStmt = $this->db->prepare("SELECT da.*, u.name as driver_name, u.phone as driver_phone 
                                             FROM delivery_assignments da 
                                             JOIN users u ON da.driver_id = u.id 
                                             WHERE da.order_id = ?");
            $driverStmt->execute([$orderId]);
            $order['driver'] = $driverStmt->fetch();
        }

        return $order;
    }

    public function getUserOrders($userId) {
        $stmt = $this->db->prepare("SELECT o.*, r.name as restaurant_name, r.image as restaurant_image,
                                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count 
                                   FROM orders o 
                                   JOIN restaurants r ON o.restaurant_id = r.id 
                                   WHERE o.user_id = ? 
                                   ORDER BY o.id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getRestaurantOrders($restaurantId) {
        $stmt = $this->db->prepare("SELECT o.*, u.name as customer_name, u.phone as customer_phone,
                                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count 
                                   FROM orders o 
                                   JOIN users u ON o.user_id = u.id 
                                   WHERE o.restaurant_id = ? 
                                   ORDER BY o.id DESC");
        $stmt->execute([$restaurantId]);
        return $stmt->fetchAll();
    }

    public function getDriverOrders($driverId) {
        $stmt = $this->db->prepare("SELECT o.*, da.status as assignment_status, r.name as restaurant_name, r.address as restaurant_address, u.name as customer_name, u.phone as customer_phone 
                                   FROM delivery_assignments da 
                                   JOIN orders o ON da.order_id = o.id 
                                   JOIN restaurants r ON o.restaurant_id = r.id 
                                   JOIN users u ON o.user_id = u.id 
                                   WHERE da.driver_id = ? 
                                   ORDER BY o.id DESC");
        $stmt->execute([$driverId]);
        return $stmt->fetchAll();
    }

    public function getAllOrders() {
        $stmt = $this->db->query("SELECT o.*, u.name as customer_name, r.name as restaurant_name 
                                 FROM orders o 
                                 JOIN users u ON o.user_id = u.id 
                                 JOIN restaurants r ON o.restaurant_id = r.id 
                                 ORDER BY o.id DESC");
        return $stmt->fetchAll();
    }

    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }
}
