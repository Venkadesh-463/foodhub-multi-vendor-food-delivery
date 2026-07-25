<?php
/**
 * controllers/CartController.php
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/Cart.php';

class CartController {
    private Cart $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
    }

    public function getItems(): array {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) return [];
        return $this->cartModel->getItems($userId);
    }
}
