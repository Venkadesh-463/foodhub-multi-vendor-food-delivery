<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';

RestaurantMiddleware::handle();
$pageTitle = "Outbound Delivery Tracking";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Delivery Tracking Map</h2>
    <div class="glass-card p-4 mt-3">
        <div id="map" style="height:350px;" class="rounded mb-3"></div>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
