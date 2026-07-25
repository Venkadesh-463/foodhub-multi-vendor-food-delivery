<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';

RestaurantMiddleware::handle();
$pageTitle = "Performance Reports";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Performance Reports</h2>
    <div class="glass-card p-4 mt-3">
        <button class="btn btn-primary"><i class="fa-solid fa-download me-2"></i> Export Monthly Report (CSV)</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
