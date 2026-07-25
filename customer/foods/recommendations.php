<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/RecommendationService.php';

$recService = new RecommendationService();
$userId = $_SESSION['user_id'] ?? 0;
$items = $recService->getForUser($userId);
$pageTitle = "Recommended for You";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Recommended Dishes</h2>
    <div class="row g-4 mt-2">
        <?php foreach ($items as $item): ?>
            <div class="col-md-3">
                <div class="glass-card p-3 h-100">
                    <img src="<?= getFoodImage($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-100 rounded mb-2" style="height:120px; object-fit:cover;">
                    <h6><?= htmlspecialchars($item['name']) ?></h6>
                    <small class="text-muted d-block mb-2"><?= htmlspecialchars($item['restaurant_name']) ?></small>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-success"><?= formatPrice($item['price']) ?></strong>
                        <button onclick="addToCart(<?= $item['id'] ?>)" class="btn btn-sm btn-primary">Add</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
