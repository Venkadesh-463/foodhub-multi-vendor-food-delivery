<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Food.php';

class Cart {
    private $db;
    private $foodModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->foodModel = new Food();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function addItem($userId, $foodId, $quantity = 1) {
        if ($userId) {
            // Check if item already in user cart DB
            $stmt = $this->db->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND food_id = ?");
            $stmt->execute([$userId, $foodId]);
            $item = $stmt->fetch();

            if ($item) {
                $newQty = $item['quantity'] + $quantity;
                $update = $this->db->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
                $update->execute([$newQty, $item['id']]);
            } else {
                $insert = $this->db->prepare("INSERT INTO cart (user_id, food_id, quantity) VALUES (?, ?, ?)");
                $insert->execute([$userId, $foodId, $quantity]);
            }
        } else {
            // Session based
            if (isset($_SESSION['cart'][$foodId])) {
                $_SESSION['cart'][$foodId] += $quantity;
            } else {
                $_SESSION['cart'][$foodId] = $quantity;
            }
        }
        return true;
    }

    public function updateItem($userId, $foodId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($userId, $foodId);
        }

        if ($userId) {
            $stmt = $this->db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND food_id = ?");
            return $stmt->execute([$quantity, $userId, $foodId]);
        } else {
            $_SESSION['cart'][$foodId] = $quantity;
            return true;
        }
    }

    public function removeItem($userId, $foodId) {
        if ($userId) {
            $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = ? AND food_id = ?");
            return $stmt->execute([$userId, $foodId]);
        } else {
            unset($_SESSION['cart'][$foodId]);
            return true;
        }
    }

    public function clearCart($userId) {
        if ($userId) {
            $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);
        }
        $_SESSION['cart'] = [];
        return true;
    }

    public function getItems($userId) {
        $items = [];
        if ($userId) {
            $stmt = $this->db->prepare("SELECT c.id as cart_id, c.quantity, f.*, r.name as restaurant_name, r.delivery_fee 
                                       FROM cart c 
                                       JOIN food_items f ON c.food_id = f.id 
                                       JOIN restaurants r ON f.restaurant_id = r.id 
                                       WHERE c.user_id = ?");
            $stmt->execute([$userId]);
            $items = $stmt->fetchAll();
        } else {
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $foodId => $qty) {
                    $food = $this->foodModel->getById($foodId);
                    if ($food) {
                        $food['quantity'] = $qty;
                        $items[] = $food;
                    }
                }
            }
        }
        return $items;
    }

    public function getTotals($userId) {
        $items = $this->getItems($userId);
        $subtotal = 0;
        $deliveryFee = 0;
        $restaurantIds = [];

        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
            if (!in_array($item['restaurant_id'], $restaurantIds)) {
                $restaurantIds[] = $item['restaurant_id'];
                $deliveryFee = max($deliveryFee, floatval($item['delivery_fee'] ?? 2.99));
            }
        }

        $tax = $subtotal * 0.08; // 8% sales tax
        $grandTotal = $subtotal + $deliveryFee + $tax;

        return [
            'count' => count($items),
            'subtotal' => round($subtotal, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'tax' => round($tax, 2),
            'grand_total' => round($grandTotal, 2),
            'items' => $items
        ];
    }
}
