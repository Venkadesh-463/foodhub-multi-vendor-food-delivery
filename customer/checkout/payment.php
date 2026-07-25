<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';

CustomerMiddleware::handle();
$pageTitle = "Step 2: Payment Method";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Step 2: Choose Payment Method</h2>
    <form action="<?= BASE_URL ?>customer/checkout/confirm.php" method="POST" class="mt-4">
        <div class="glass-card p-4 col-md-6">
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                <label class="form-check-label fw-bold" for="cod"><i class="fa-solid fa-money-bill-1 text-success me-2"></i> Cash on Delivery</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="payment_method" value="upi" id="upi">
                <label class="form-check-label fw-bold" for="upi"><i class="fa-solid fa-qrcode text-info me-2"></i> UPI / Online Transfer</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="payment_method" value="card" id="card">
                <label class="form-check-label fw-bold" for="card"><i class="fa-solid fa-credit-card text-warning me-2"></i> Credit / Debit Card</label>
            </div>
            <button type="submit" class="btn btn-success w-100 mt-3">Place Order</button>
        </div>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
