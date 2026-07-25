<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/DeliveryPartner.php';

RestaurantMiddleware::handle();
$pageTitle = "Assign Delivery Rider";

$dp = new DeliveryPartner();
$riders = $dp->getAvailable();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Assign Rider</h2>
    <div class="row g-3 mt-3">
        <?php foreach ($riders as $r): ?>
            <div class="col-md-4">
                <div class="glass-card p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6><?= htmlspecialchars($r['name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($r['phone']) ?></small>
                    </div>
                    <button class="btn btn-sm btn-primary">Assign</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
