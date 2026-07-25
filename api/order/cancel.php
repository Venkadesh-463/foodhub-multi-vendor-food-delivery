<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Order.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$id = (int)($input['order_id'] ?? 0);
$orderModel = new Order();
$ok = $orderModel->updateStatus($id, 'cancelled');
echo json_encode(['success' => $ok]);
