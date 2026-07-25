<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/FoodReview.php';

$restaurantId = (int)($_GET['restaurant_id'] ?? 1);
$reviewModel = new FoodReview();
$reviews = $reviewModel->getByRestaurant($restaurantId);
$pageTitle = "Reviews";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Customer Reviews</h2>
    <div class="mt-4">
        <?php foreach ($reviews as $rev): ?>
            <div class="glass-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong><?= htmlspecialchars($rev['user_name']) ?></strong>
                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-star"></i> <?= $rev['rating'] ?></span>
                </div>
                <p class="mb-0 text-muted"><?= htmlspecialchars($rev['comment']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
