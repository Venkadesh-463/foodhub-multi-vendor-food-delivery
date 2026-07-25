<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';

RestaurantMiddleware::handle();
$pageTitle = "Inventory Management";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Inventory Stock</h2>
    <p class="text-muted">Manage item availability and stock status.</p>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
