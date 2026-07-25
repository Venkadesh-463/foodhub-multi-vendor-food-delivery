<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Platform Delivery Zones";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Platform Delivery Boundaries</h2>
    <div class="glass-card p-4 mt-3">
        <p class="text-muted mb-0">Define operational geographic boundaries for riders and restaurants.</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
