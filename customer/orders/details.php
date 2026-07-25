<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';

CustomerMiddleware::handle();

$id = (int)($_GET['id'] ?? 1);
$orderModel = new Order();
$order = $orderModel->findById($id);
$items = $order ? $orderModel->getItems($id) : [];
$pageTitle = "Order Details";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <?php if ($order): ?>
        <div class="glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Order #<?= htmlspecialchars($order['order_number']) ?></h3>
                <a href="<?= BASE_URL ?>customer/orders/tracking.php?id=<?= $order['id'] ?>" class="btn btn-warning"><i class="fa-solid fa-location-crosshairs me-1"></i> Live Track</a>
            </div>
            <p><strong>Restaurant:</strong> <?= htmlspecialchars($order['restaurant_name']) ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order['order_status']) ?></p>
            <hr>
            <h5>Items</h5>
            <ul class="list-group list-group-flush bg-transparent">
                <?php foreach ($items as $it): ?>
                    <li class="list-group-item bg-transparent text-white d-flex justify-content-between">
                        <span><?= htmlspecialchars($it['food_name']) ?> x <?= $it['quantity'] ?></span>
                        <strong><?= formatPrice($it['subtotal']) ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Order not found.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
