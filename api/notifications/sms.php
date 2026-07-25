<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/NotificationService.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$phone = sanitize($input['phone'] ?? '');
$message = sanitize($input['message'] ?? '');

$ns = new NotificationService();
$ok = $ns->sendSMS($phone, $message);
echo json_encode(['success' => $ok]);
