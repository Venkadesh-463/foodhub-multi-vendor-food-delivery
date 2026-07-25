<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';

RiderMiddleware::handle();
$id = (int)($_GET['id'] ?? 1);
$orderModel = new Order();
$order = $orderModel->findById($id);
$pageTitle = "Navigation";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Step 2: Delivery Navigation</h2>
    <div class="glass-card p-4 mt-3">
        <div id="map" style="height:350px;" class="rounded mb-3"></div>
        <a href="<?= BASE_URL ?>delivery/active-orders/delivered.php?id=<?= $id ?>" class="btn btn-success w-100 btn-lg">Mark as Delivered</a>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
