<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';

CustomerMiddleware::handle();
$pageTitle = "Delivery Options";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Delivery Options</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <label class="d-block mb-3">
            <input type="radio" name="opt" checked> Standard Delivery (25 - 35 mins)
        </label>
        <label class="d-block mb-3">
            <input type="radio" name="opt"> Priority Express (+<?= formatPrice(1.99) ?>)
        </label>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
