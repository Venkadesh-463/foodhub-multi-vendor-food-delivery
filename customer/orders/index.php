<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';

CustomerMiddleware::handle();
$pageTitle = "My Orders";

$orderModel = new Order();
$orders = $orderModel->getByUserId($_SESSION['user_id']);

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>My Orders</h2>
    <div class="mt-4">
        <?php foreach ($orders as $o): ?>
            <div class="glass-card p-3 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5>#<?= $o['order_number'] ?></h5>
                    <p class="mb-0 text-muted small"><?= htmlspecialchars($o['restaurant_name']) ?> • <?= formatPrice($o['total_amount']) ?></p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary"><?= ucfirst($o['order_status']) ?></span>
                    <a href="<?= BASE_URL ?>customer/orders/details.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-light">Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
