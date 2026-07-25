<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Restaurant.php';

RestaurantMiddleware::handle();
$pageTitle = "Accepted Orders";

$restModel = new Restaurant();
$restaurant = $restModel->findByUserId($_SESSION['user_id']);
$orderModel = new Order();
$orders = $restaurant ? $orderModel->getByRestaurantId($restaurant['id'], 'preparing') : [];

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Accepted & Preparing Orders</h2>
    <div class="mt-4">
        <?php foreach ($orders as $o): ?>
            <div class="glass-card p-3 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5>#<?= $o['order_number'] ?></h5>
                    <p class="mb-0 text-muted small">Preparing in kitchen...</p>
                </div>
                <a href="<?= BASE_URL ?>restaurant/orders/ready.php?action=ready&id=<?= $o['id'] ?>" class="btn btn-sm btn-primary">Mark Ready</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
