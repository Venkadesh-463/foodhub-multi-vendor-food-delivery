<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../classes/Order.php';

$orderModel = new Order();
$userId = $_SESSION['user_id'] ?? null;
$role = $_SESSION['user_role'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = $input['action'] ?? '';

    if ($action === 'update_status') {
        $orderId = intval($input['order_id'] ?? 0);
        $status = sanitize($input['status'] ?? '');

        if ($orderId > 0 && !empty($status)) {
            $orderModel->updateOrderStatus($orderId, $status);
            echo json_encode(['success' => true, 'message' => 'Order status updated to ' . ucfirst($status)]);
            exit();
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid endpoint query']);
