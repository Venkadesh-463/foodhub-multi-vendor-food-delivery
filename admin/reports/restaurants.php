<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Restaurant Partner Performance";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Restaurant Partner Performance</h2>
    <div class="glass-card p-4 mt-3">
        <p class="text-muted mb-0">Order counts and fulfillment rates by restaurant.</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
