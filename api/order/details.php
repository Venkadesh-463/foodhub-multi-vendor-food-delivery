<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Order.php';

$id = (int)($_GET['id'] ?? 0);
$orderModel = new Order();
$order = $orderModel->findById($id);
if ($order) {
    $order['items'] = $orderModel->getItems($id);
}
echo json_encode(['success' => (bool)$order, 'order' => $order]);
