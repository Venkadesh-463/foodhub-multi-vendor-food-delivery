<?php
/**
 * models/Cart.php & models/CartItem.php
 */
require_once __DIR__ . '/../config/database.php';

class Cart {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getItems(int $userId): array {
        $stmt = $this->db->prepare("SELECT c.*, f.name, f.price, f.image, f.restaurant_id, r.name AS restaurant_name FROM cart c JOIN food_items f ON f.id = c.food_id JOIN restaurants r ON r.id = f.restaurant_id WHERE c.user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function add(int $userId, int $foodId, int $quantity = 1): bool {
        $stmt = $this->db->prepare("SELECT id, quantity FROM cart WHERE user_id = :uid AND food_id = :fid LIMIT 1");
        $stmt->execute([':uid' => $userId, ':fid' => $foodId]);
        $item = $stmt->fetch();
        if ($item) {
            $newQty = $item['quantity'] + $quantity;
            $upd = $this->db->prepare("UPDATE cart SET quantity = :qty WHERE id = :id");
            return $upd->execute([':qty' => $newQty, ':id' => $item['id']]);
        }
        $ins = $this->db->prepare("INSERT INTO cart (user_id, food_id, quantity) VALUES (:uid, :fid, :qty)");
        return $ins->execute([':uid' => $userId, ':fid' => $foodId, ':qty' => $quantity]);
    }

    public function update(int $userId, int $foodId, int $quantity): bool {
        if ($quantity <= 0) {
            return $this->remove($userId, $foodId);
        }
        $stmt = $this->db->prepare("UPDATE cart SET quantity = :qty WHERE user_id = :uid AND food_id = :fid");
        return $stmt->execute([':qty' => $quantity, ':uid' => $userId, ':fid' => $foodId]);
    }

    public function remove(int $userId, int $foodId): bool {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = :uid AND food_id = :fid");
        return $stmt->execute([':uid' => $userId, ':fid' => $foodId]);
    }

    public function clear(int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = :uid");
        return $stmt->execute([':uid' => $userId]);
    }
}

class CartItem {
    public int $id;
    public int $userId;
    public int $foodId;
    public int $quantity;
}
