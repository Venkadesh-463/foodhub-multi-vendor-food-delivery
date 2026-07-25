<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
require_once __DIR__ . '/../../models/Cart.php';

CustomerMiddleware::handle();

$pageTitle = "Shopping Cart";
$cartModel = new Cart();
$items = $cartModel->getItems($_SESSION['user_id']);

$subtotal = 0;
foreach ($items as $it) {
    $subtotal += $it['price'] * $it['quantity'];
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2 class="mb-4">Shopping Cart</h2>
    <?php if (!empty($items)): ?>
        <div class="row g-4">
            <div class="col-md-8">
                <?php foreach ($items as $it): ?>
                    <div class="glass-card p-3 mb-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= getFoodImage($it['image']) ?>" alt="" style="width:60px; height:60px; object-fit:cover;" class="rounded">
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($it['name']) ?></h6>
                                <small class="text-muted"><?= formatPrice($it['price']) ?> × <?= $it['quantity'] ?></small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <strong><?= formatPrice($it['price'] * $it['quantity']) ?></strong>
                            <a href="<?= BASE_URL ?>customer/cart/remove.php?food_id=<?= $it['food_id'] ?>" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <h4>Summary</h4>
                    <div class="d-flex justify-content-between my-3">
                        <span>Subtotal:</span>
                        <strong><?= formatPrice($subtotal) ?></strong>
                    </div>
                    <a href="<?= BASE_URL ?>customer/checkout/address.php" class="btn btn-success w-100 btn-lg">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Your cart is empty. <a href="<?= BASE_URL ?>customer/restaurants/index.php">Browse restaurants</a>.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
