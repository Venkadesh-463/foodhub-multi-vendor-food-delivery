<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';

RestaurantMiddleware::handle();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $orderModel = new Order();
    $orderModel->updateStatus($id, 'preparing');
    flash('info', 'Order status updated to preparing.');
}
redirect(BASE_URL . 'restaurant/orders/accepted.php');
