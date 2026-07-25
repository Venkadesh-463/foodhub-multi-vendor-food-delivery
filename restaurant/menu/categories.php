<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Category.php';

RestaurantMiddleware::handle();
$pageTitle = "Categories";
$catModel = new Category();
$categories = $catModel->getAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Menu Categories</h2>
    <div class="row g-3 mt-2">
        <?php foreach ($categories as $c): ?>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fa-solid <?= $c['icon'] ?> fa-2x mb-2 text-warning"></i>
                    <h5><?= htmlspecialchars($c['name']) ?></h5>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
