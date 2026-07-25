<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Restaurant.php';

$cuisine = sanitize($_GET['cuisine'] ?? '');
$restaurantModel = new Restaurant();
$results = $restaurantModel->getAll(['cuisine' => $cuisine]);
$pageTitle = "Filter: " . $cuisine;

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Filter by Cuisine: <?= htmlspecialchars($cuisine) ?></h2>
    <div class="row g-4 mt-2">
        <?php foreach ($results as $r): ?>
            <div class="col-md-4">
                <div class="glass-card p-3">
                    <h5><?= htmlspecialchars($r['name']) ?></h5>
                    <a href="<?= BASE_URL ?>customer/restaurants/details.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary mt-2">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
