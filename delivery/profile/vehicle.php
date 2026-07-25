<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';

RiderMiddleware::handle();
$pageTitle = "Vehicle Information";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Vehicle Info</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <label class="form-label">Vehicle Type</label>
        <input type="text" class="form-control" value="Motorcycle / Scooter" readonly>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
