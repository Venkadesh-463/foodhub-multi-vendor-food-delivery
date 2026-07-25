<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Restaurant.php';

RestaurantMiddleware::handle();
$pageTitle = "Ready for Pickup";

$id = (int)($_GET['id'] ?? 0);
$orderModel = new Order();
if ($id > 0) {
    $orderModel->updateStatus($id, 'ready_for_delivery');
    flash('success', 'Order marked ready for delivery pickup.');
}

$restModel = new Restaurant();
$restaurant = $restModel->findByUserId($_SESSION['user_id']);
$orders = $restaurant ? $orderModel->getByRestaurantId($restaurant['id'], 'ready_for_delivery') : [];

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Ready for Pickup / Rider</h2>
    <div class="mt-4">
        <?php foreach ($orders as $o): ?>
            <div class="glass-card p-3 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5>#<?= $o['order_number'] ?></h5>
                    <span class="badge bg-success">Ready</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
