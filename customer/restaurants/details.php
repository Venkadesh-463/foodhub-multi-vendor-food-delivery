<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Restaurant.php';
require_once __DIR__ . '/../../models/Food.php';

$id = (int)($_GET['id'] ?? 1);
$restaurantModel = new Restaurant();
$foodModel = new Food();

$restaurant = $restaurantModel->findById($id);
$menu = $foodModel->getByRestaurant($id);
$pageTitle = $restaurant ? $restaurant['name'] : 'Restaurant Details';

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <?php if ($restaurant): ?>
        <div class="glass-card p-4 mb-4">
            <h2><?= htmlspecialchars($restaurant['name']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($restaurant['cuisine']) ?> • <?= htmlspecialchars($restaurant['address']) ?></p>
        </div>
        <h4 class="mb-3">Menu Items</h4>
        <div class="row g-4">
            <?php foreach ($menu as $item): ?>
                <div class="col-md-4">
                    <div class="glass-card p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5><?= htmlspecialchars($item['name']) ?></h5>
                            <p class="small text-muted"><?= htmlspecialchars($item['description']) ?></p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <strong class="text-success"><?= formatPrice($item['price']) ?></strong>
                            <button onclick="addToCart(<?= $item['id'] ?>)" class="btn btn-sm btn-primary">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Restaurant not found.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
