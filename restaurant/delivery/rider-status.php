<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';

RestaurantMiddleware::handle();
$pageTitle = "Rider Status";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Delivery Rider Status</h2>
    <p class="text-muted">Monitor delivery riders assigned to your active orders.</p>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
