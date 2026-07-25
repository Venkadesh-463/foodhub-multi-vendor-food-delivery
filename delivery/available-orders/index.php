<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';

RiderMiddleware::handle();
$pageTitle = "Available Orders";

$orderModel = new Order();
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT o.*, r.name AS restaurant_name, r.address AS restaurant_address FROM orders o JOIN restaurants r ON r.id = o.restaurant_id WHERE o.order_status = 'ready_for_delivery' ORDER BY o.id DESC");
$orders = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Available Orders to Pickup</h2>
    <div class="mt-3">
        <?php foreach ($orders as $o): ?>
            <div class="glass-card p-3 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5>#<?= $o['order_number'] ?></h5>
                    <p class="mb-0 text-muted small">Pickup: <?= htmlspecialchars($o['restaurant_name']) ?></p>
                </div>
                <a href="<?= BASE_URL ?>delivery/available-orders/accept.php?id=<?= $o['id'] ?>" class="btn btn-success btn-sm">Accept Order</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
