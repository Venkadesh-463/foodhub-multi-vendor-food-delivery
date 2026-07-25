<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';
require_once __DIR__ . '/../../services/RiderAssignmentService.php';

RiderMiddleware::handle();
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $service = new RiderAssignmentService();
    $service->assign($id, $_SESSION['user_id']);
    flash('success', 'Order accepted!');
}
redirect(BASE_URL . 'delivery/active-orders/pickup.php?id=' . $id);
