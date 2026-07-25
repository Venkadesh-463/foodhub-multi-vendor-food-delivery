<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Cart.php';

if (isLoggedIn()) {
    $foodId = (int)($_GET['food_id'] ?? 0);
    $cart = new Cart();
    $cart->remove($_SESSION['user_id'], $foodId);
    flash('info', 'Item removed from cart.');
}
redirect(BASE_URL . 'customer/cart/index.php');
