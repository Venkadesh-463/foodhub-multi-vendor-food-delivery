<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/NotificationService.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$to = sanitize($input['to'] ?? '');
$subject = sanitize($input['subject'] ?? '');
$body = $input['body'] ?? '';

$ns = new NotificationService();
$ok = $ns->sendEmail($to, $subject, $body);
echo json_encode(['success' => $ok]);
