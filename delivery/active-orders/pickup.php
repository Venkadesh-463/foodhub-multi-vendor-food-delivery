<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';

RiderMiddleware::handle();
$id = (int)($_GET['id'] ?? 1);
$orderModel = new Order();
$order = $orderModel->findById($id);
$pageTitle = "Pickup Order";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Step 1: Order Pickup</h2>
    <?php if ($order): ?>
        <div class="glass-card p-4 col-md-6 mt-3">
            <h5>#<?= $order['order_number'] ?></h5>
            <p><strong>Restaurant:</strong> <?= htmlspecialchars($order['restaurant_name']) ?></p>
            <p><strong>Pickup Address:</strong> <?= htmlspecialchars($order['restaurant_phone'] ?? 'Main Store') ?></p>
            <a href="<?= BASE_URL ?>delivery/active-orders/navigate.php?id=<?= $order['id'] ?>" class="btn btn-primary w-100">Confirm Pickup & Navigate</a>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
