<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RiderMiddleware.php';

RiderMiddleware::handle();
$pageTitle = "Rider Documents & KYC";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Rider Documents & KYC</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <p class="text-success"><i class="fa-solid fa-circle-check me-2"></i> Driving License Verified</p>
        <p class="text-success"><i class="fa-solid fa-circle-check me-2"></i> Vehicle Insurance Verified</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
