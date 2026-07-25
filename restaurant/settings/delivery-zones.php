<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';

RestaurantMiddleware::handle();
$pageTitle = "Delivery Radius & Zones";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Delivery Radius & Zones</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <label class="form-label">Max Delivery Distance (KM)</label>
        <input type="number" class="form-control" value="10">
        <button class="btn btn-primary mt-3">Save Zone</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
