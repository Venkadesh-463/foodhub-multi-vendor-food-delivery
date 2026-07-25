<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';
require_once __DIR__ . '/../../models/DeliveryAssignment.php';

RiderMiddleware::handle();
$pageTitle = "Delivery History";

$da = new DeliveryAssignment();
$history = $da->getByDriver($_SESSION['user_id']);

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Completed Deliveries</h2>
    <div class="mt-3">
        <?php foreach ($history as $h): ?>
            <div class="glass-card p-3 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5>#<?= $h['order_number'] ?></h5>
                    <p class="mb-0 text-muted small"><?= htmlspecialchars($h['restaurant_name']) ?></p>
                </div>
                <span class="badge bg-success">Completed</span>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
