<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/RiderAssignmentService.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$orderId = (int)($input['order_id'] ?? 0);
$riderId = (int)($input['rider_id'] ?? 0);

$service = new RiderAssignmentService();
if ($riderId > 0) {
    $res = $service->assign($orderId, $riderId);
} else {
    $res = $service->autoAssign($orderId);
}
echo json_encode($res);
