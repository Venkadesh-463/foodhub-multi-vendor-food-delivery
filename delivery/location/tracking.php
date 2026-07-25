<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';

RiderMiddleware::handle();
$pageTitle = "GPS Telemetry & Location";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Active GPS Telemetry</h2>
    <div class="glass-card p-4 mt-3">
        <p class="text-success"><i class="fa-solid fa-satellite-dish me-2"></i> GPS Tracking is Active</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
