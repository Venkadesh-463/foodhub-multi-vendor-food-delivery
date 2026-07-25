<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';
require_once __DIR__ . '/../../services/TrackingService.php';

RiderMiddleware::handle();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ts = new TrackingService();
    $ts->updateLocation($_SESSION['user_id'], (float)$_POST['lat'], (float)$_POST['lng']);
    echo json_encode(['success' => true]);
    exit();
}
