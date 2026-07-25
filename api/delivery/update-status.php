<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/RiderAssignmentService.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$orderId = (int)($input['order_id'] ?? 0);
$riderId = (int)($input['rider_id'] ?? $_SESSION['user_id'] ?? 0);
$status  = sanitize($input['status'] ?? '');

$service = new RiderAssignmentService();
$ok = $service->updateStatus($orderId, $riderId, $status);
echo json_encode(['success' => $ok]);
