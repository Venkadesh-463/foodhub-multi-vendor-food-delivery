<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
require_once __DIR__ . '/../../models/Cart.php';

CustomerMiddleware::handle();

$cart = new Cart();
$cart->clear($_SESSION['user_id']);

$orderNum = 'FH-' . rand(10000, 99999);
$pageTitle = "Order Confirmed!";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-5 text-center">
    <div class="glass-card p-5 col-md-6 mx-auto">
        <i class="fa-solid fa-circle-check text-success fa-4x mb-3"></i>
        <h2>Thank You!</h2>
        <p class="text-muted">Your order <strong>#<?= $orderNum ?></strong> has been successfully placed.</p>
        <a href="<?= BASE_URL ?>customer/orders/index.php" class="btn btn-primary mt-3">Track Order</a>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
