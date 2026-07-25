<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Platform Commissions";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Platform Commission Rates</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <label class="form-label">Restaurant Commission (%)</label>
        <input type="number" class="form-control" value="15">
        <button class="btn btn-primary mt-3">Update Commission</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
