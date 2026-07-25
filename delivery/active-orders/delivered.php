<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';
require_once __DIR__ . '/../../services/RiderAssignmentService.php';

RiderMiddleware::handle();
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $service = new RiderAssignmentService();
    $service->updateStatus($id, $_SESSION['user_id'], 'delivered');
    flash('success', 'Order marked as delivered!');
}
redirect(BASE_URL . 'delivery/dashboard.php');
