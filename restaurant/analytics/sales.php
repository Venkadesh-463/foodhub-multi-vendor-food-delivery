<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';

RestaurantMiddleware::handle();
$pageTitle = "Sales Analytics";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Sales Reports & Volume</h2>
    <div class="glass-card p-4 mt-3">
        <p class="text-muted mb-0">Detailed breakdown of top selling items and order volumes.</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
