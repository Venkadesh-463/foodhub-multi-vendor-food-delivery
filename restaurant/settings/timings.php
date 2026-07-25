<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/RestaurantTiming.php';
require_once __DIR__ . '/../../models/Restaurant.php';

RestaurantMiddleware::handle();
$pageTitle = "Opening Hours & Timings";

$restModel = new Restaurant();
$restaurant = $restModel->findByUserId($_SESSION['user_id']);
$timingModel = new RestaurantTiming();
$timings = $restaurant ? $timingModel->getByRestaurant($restaurant['id']) : [];

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Operating Hours</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <p class="text-muted">Configure daily opening and closing hours.</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
