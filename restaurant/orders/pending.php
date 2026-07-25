<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Restaurant.php';

RestaurantMiddleware::handle();
$pageTitle = "Pending Orders";

$restModel = new Restaurant();
$restaurant = $restModel->findByUserId($_SESSION['user_id']);

$orderModel = new Order();
$orders = $restaurant ? $orderModel->getByRestaurantId($restaurant['id'], 'pending') : [];

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Pending Orders</h2>
    <div class="mt-4">
        <?php foreach ($orders as $o): ?>
            <div class="glass-card p-3 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5>#<?= $o['order_number'] ?></h5>
                    <p class="mb-0 text-muted small">Customer: <?= htmlspecialchars($o['customer_name']) ?></p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>restaurant/orders/preparing.php?action=accept&id=<?= $o['id'] ?>" class="btn btn-sm btn-success">Accept Order</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
