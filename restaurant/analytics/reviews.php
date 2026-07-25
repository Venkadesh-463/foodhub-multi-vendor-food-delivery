<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/FoodReview.php';
require_once __DIR__ . '/../../models/Restaurant.php';

RestaurantMiddleware::handle();
$pageTitle = "Customer Feedback";

$restModel = new Restaurant();
$restaurant = $restModel->findByUserId($_SESSION['user_id']);
$reviewModel = new FoodReview();
$reviews = $restaurant ? $reviewModel->getByRestaurant($restaurant['id']) : [];

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Customer Reviews & Ratings</h2>
    <div class="mt-3">
        <?php foreach ($reviews as $rev): ?>
            <div class="glass-card p-3 mb-3">
                <div class="d-flex justify-content-between">
                    <strong><?= htmlspecialchars($rev['user_name']) ?></strong>
                    <span class="badge bg-warning text-dark"><?= $rev['rating'] ?> ★</span>
                </div>
                <p class="mb-0 mt-2 text-muted"><?= htmlspecialchars($rev['comment']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
