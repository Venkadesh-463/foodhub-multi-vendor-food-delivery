<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Global Delivery Fees";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Global Delivery Fee Configuration</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <label class="form-label">Base Fee (USD)</label>
        <input type="number" step="0.01" class="form-control" value="2.99">
        <button class="btn btn-primary mt-3">Save Delivery Fee</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
