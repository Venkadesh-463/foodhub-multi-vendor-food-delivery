<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Cart.php';

if (isLoggedIn()) {
    $cart = new Cart();
    $cart->clear($_SESSION['user_id']);
    flash('info', 'Cart cleared.');
}
redirect(BASE_URL . 'customer/cart/index.php');
