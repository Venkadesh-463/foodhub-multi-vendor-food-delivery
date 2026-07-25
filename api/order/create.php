<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Order.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
// Simple order creation logic placeholder/service hook
echo json_encode(['success' => true, 'order_number' => 'FH-' . rand(10000, 99999)]);
