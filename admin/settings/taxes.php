<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Tax Rates & GST";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Tax Rate Settings</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <label class="form-label">Global Service Tax / VAT Rate (%)</label>
        <input type="number" step="0.1" class="form-control" value="5.0">
        <button class="btn btn-primary mt-3">Update Tax Rate</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
