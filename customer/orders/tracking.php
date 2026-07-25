<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
require_once __DIR__ . '/../../models/Order.php';

CustomerMiddleware::handle();
$id = (int)($_GET['id'] ?? 1);
$pageTitle = "Track Order";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Live Order Tracking</h2>
    <div class="glass-card p-4 mt-3">
        <div id="map" style="height:350px; width:100%; border-radius:12px;" class="mb-3"></div>
        <div class="progress mb-3" style="height: 10px;">
            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: 60%"></div>
        </div>
        <p class="text-center fw-bold">Rider is on the way with your food!</p>
    </div>
</div>
<script src="<?= BASE_URL ?>assets/js/tracking.js"></script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
