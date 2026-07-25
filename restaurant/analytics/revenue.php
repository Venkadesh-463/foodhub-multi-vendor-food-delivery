<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Restaurant.php';

RestaurantMiddleware::handle();
$pageTitle = "Revenue Analytics";

$restModel = new Restaurant();
$restaurant = $restModel->findByUserId($_SESSION['user_id']);
$totalRevenue = $restaurant ? $restModel->getTotalRevenue($restaurant['id']) : 0;

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Revenue & Earnings</h2>
    <div class="glass-card p-4 col-md-4 mt-3">
        <h6>Total Earnings</h6>
        <h3 class="text-success mb-0"><?= formatPrice($totalRevenue) ?></h3>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
