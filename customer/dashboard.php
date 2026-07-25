<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../middleware/CustomerMiddleware.php';
CustomerMiddleware::handle();

$pageTitle = "Customer Dashboard";
include __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <div class="glass-card p-4 mb-4">
        <h2>Welcome back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Customer') ?>! 👋</h2>
        <p class="text-muted">What would you like to eat today?</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>customer/restaurants/index.php" class="text-decoration-none">
                <div class="glass-card p-4 text-center hover-lift">
                    <i class="fa-solid fa-store fa-2x text-warning mb-2"></i>
                    <h5>Restaurants</h5>
                    <p class="small text-muted mb-0">Browse top spots nearby</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>customer/orders/index.php" class="text-decoration-none">
                <div class="glass-card p-4 text-center hover-lift">
                    <i class="fa-solid fa-receipt fa-2x text-info mb-2"></i>
                    <h5>My Orders</h5>
                    <p class="small text-muted mb-0">Track & view history</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>customer/cart/index.php" class="text-decoration-none">
                <div class="glass-card p-4 text-center hover-lift">
                    <i class="fa-solid fa-cart-shopping fa-2x text-success mb-2"></i>
                    <h5>My Cart</h5>
                    <p class="small text-muted mb-0">View items in cart</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>customer/profile/edit.php" class="text-decoration-none">
                <div class="glass-card p-4 text-center hover-lift">
                    <i class="fa-solid fa-user-gear fa-2x text-primary mb-2"></i>
                    <h5>Account</h5>
                    <p class="small text-muted mb-0">Profile & Addresses</p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
