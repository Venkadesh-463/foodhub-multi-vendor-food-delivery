<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';

RiderMiddleware::handle();
$pageTitle = "Rider Settings";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Delivery Rider Settings</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="autoAccept">
            <label class="form-check-label" for="autoAccept">Auto-accept order assignments</label>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
