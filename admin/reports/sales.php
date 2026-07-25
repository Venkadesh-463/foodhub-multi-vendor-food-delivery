<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Global Sales Report";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Platform Sales Report</h2>
    <div class="glass-card p-4 mt-3">
        <button class="btn btn-success"><i class="fa-solid fa-file-excel me-2"></i> Export Sales CSV</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
