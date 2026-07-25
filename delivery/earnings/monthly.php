<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';

RiderMiddleware::handle();
$pageTitle = "Monthly Earnings";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Monthly Earnings</h2>
    <div class="glass-card p-4 col-md-4 mt-3">
        <h6>This Month</h6>
        <h3 class="text-success mb-0"><?= formatPrice(1240.00) ?></h3>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
