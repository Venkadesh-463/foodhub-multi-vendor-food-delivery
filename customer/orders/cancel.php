<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Order.php';

if (isLoggedIn()) {
    $id = (int)($_GET['id'] ?? 0);
    $orderModel = new Order();
    $orderModel->updateStatus($id, 'cancelled');
    flash('warning', 'Order has been cancelled.');
}
redirect(BASE_URL . 'customer/orders/index.php');
