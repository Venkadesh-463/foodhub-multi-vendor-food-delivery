<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Restaurant.php';

$query = sanitize($_GET['q'] ?? '');
$restaurantModel = new Restaurant();
$results = $restaurantModel->getAll(['search' => $query]);
$pageTitle = "Search: " . $query;

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Search Results for "<?= htmlspecialchars($query) ?>"</h2>
    <div class="row g-4 mt-2">
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $r): ?>
                <div class="col-md-4">
                    <div class="glass-card p-3">
                        <h5><?= htmlspecialchars($r['name']) ?></h5>
                        <p class="small text-muted"><?= htmlspecialchars($r['cuisine']) ?></p>
                        <a href="<?= BASE_URL ?>customer/restaurants/details.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No restaurants found matching your query.</p>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
