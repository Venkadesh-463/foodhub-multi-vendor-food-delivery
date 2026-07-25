<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Food.php';

$id = (int)($_GET['id'] ?? 1);
$foodModel = new Food();
$food = $foodModel->findById($id);
$pageTitle = $food ? $food['name'] : 'Food Details';

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <?php if ($food): ?>
        <div class="glass-card p-4">
            <div class="row">
                <div class="col-md-5">
                    <img src="<?= getFoodImage($food['image']) ?>" alt="<?= htmlspecialchars($food['name']) ?>" class="img-fluid rounded-3">
                </div>
                <div class="col-md-7">
                    <h3><?= htmlspecialchars($food['name']) ?></h3>
                    <p class="text-muted"><?= htmlspecialchars($food['description']) ?></p>
                    <h4 class="text-success mb-3"><?= formatPrice($food['price']) ?></h4>
                    <button onclick="addToCart(<?= $food['id'] ?>)" class="btn btn-primary btn-lg">Add to Cart</button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Food item not found.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
