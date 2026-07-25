<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Restaurant.php';

$pageTitle = "Restaurants";
$restaurantModel = new Restaurant();
$restaurants = $restaurantModel->getAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2 class="mb-4">All Restaurants</h2>
    <div class="row g-4">
        <?php foreach ($restaurants as $r): ?>
            <div class="col-md-4">
                <div class="glass-card overflow-hidden h-100">
                    <img src="<?= getRestaurantImage($r['image']) ?>" alt="<?= htmlspecialchars($r['name']) ?>" class="w-100" style="height:180px; object-fit:cover;">
                    <div class="p-3">
                        <h5><?= htmlspecialchars($r['name']) ?></h5>
                        <p class="text-muted small mb-2"><?= htmlspecialchars($r['cuisine']) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-star"></i> <?= $r['rating'] ?></span>
                            <a href="<?= BASE_URL ?>customer/restaurants/details.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">View Menu</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
