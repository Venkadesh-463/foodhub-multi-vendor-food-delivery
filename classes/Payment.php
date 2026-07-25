<?php
require_once __DIR__ . '/../config/database.php';

class Payment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllPayments() {
        $stmt = $this->db->query("SELECT p.*, o.order_number, u.name as customer_name, r.name as restaurant_name 
                                 FROM payments p 
                                 JOIN orders o ON p.order_id = o.id 
                                 JOIN users u ON o.user_id = u.id 
                                 JOIN restaurants r ON o.restaurant_id = r.id 
                                 ORDER BY p.id DESC");
        return $stmt->fetchAll();
    }

    public function getPaymentsByRestaurant($restaurantId) {
        $stmt = $this->db->prepare("SELECT p.*, o.order_number, u.name as customer_name 
                                   FROM payments p 
                                   JOIN orders o ON p.order_id = o.id 
                                   JOIN users u ON o.user_id = u.id 
                                   WHERE o.restaurant_id = ? 
                                   ORDER BY p.id DESC");
        $stmt->execute([$restaurantId]);
        return $stmt->fetchAll();
    }
}
