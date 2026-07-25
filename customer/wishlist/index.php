<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';

CustomerMiddleware::handle();
$pageTitle = "My Wishlist";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Saved Wishlist</h2>
    <div class="glass-card p-4 mt-3 text-center">
        <i class="fa-solid fa-heart text-danger fa-3x mb-3"></i>
        <p class="text-muted">You have no saved favorite restaurants or dishes yet.</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
