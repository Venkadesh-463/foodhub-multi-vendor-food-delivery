<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/PaymentService.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$orderId = (int)($input['order_id'] ?? 0);
$amount = (float)($input['amount'] ?? 0);

$ps = new PaymentService();
$res = $ps->process($orderId, $amount, 'cod', $input);
echo json_encode($res);
