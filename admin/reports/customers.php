<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Customer Acquisition Report";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Customer Growth Analytics</h2>
    <div class="glass-card p-4 mt-3">
        <p class="text-muted mb-0">Monthly user signups and retention metrics.</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
