<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Cart.php';

if (isLoggedIn()) {
    $foodId = (int)($_GET['food_id'] ?? $_POST['food_id'] ?? 0);
    $qty = (int)($_GET['quantity'] ?? $_POST['quantity'] ?? 1);
    $cart = new Cart();
    $cart->add($_SESSION['user_id'], $foodId, $qty);
    flash('success', 'Item added to cart.');
}
redirect(BASE_URL . 'customer/cart/index.php');
