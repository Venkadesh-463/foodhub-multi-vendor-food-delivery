<?php
require_once __DIR__ . '/../classes/Food.php';

class FoodModel extends Food {
    public function getWishlistItems($userId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT f.*, r.name as restaurant_name, c.name as category_name 
                              FROM wishlist w 
                              JOIN food_items f ON w.food_id = f.id 
                              JOIN restaurants r ON f.restaurant_id = r.id 
                              JOIN categories c ON f.category_id = c.id 
                              WHERE w.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function toggleWishlist($userId, $foodId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM wishlist WHERE user_id = ? AND food_id = ?");
        $stmt->execute([$userId, $foodId]);
        $exists = $stmt->fetch();

        if ($exists) {
            $del = $db->prepare("DELETE FROM wishlist WHERE id = ?");
            $del->execute([$exists['id']]);
            return ['action' => 'removed', 'message' => 'Removed from wishlist'];
        } else {
            $ins = $db->prepare("INSERT INTO wishlist (user_id, food_id) VALUES (?, ?)");
            $ins->execute([$userId, $foodId]);
            return ['action' => 'added', 'message' => 'Added to wishlist'];
        }
    }
}
