<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/TrackingService.php';

$ts = new TrackingService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $riderId = (int)($input['rider_id'] ?? $_SESSION['user_id'] ?? 0);
    $lat = (float)($input['latitude'] ?? 0);
    $lng = (float)($input['longitude'] ?? 0);
    $ok = $ts->updateLocation($riderId, $lat, $lng);
    echo json_encode(['success' => $ok]);
} else {
    $orderId = (int)($_GET['order_id'] ?? 0);
    $loc = $ts->getOrderRiderLocation($orderId);
    echo json_encode(['success' => (bool)$loc, 'location' => $loc]);
}
