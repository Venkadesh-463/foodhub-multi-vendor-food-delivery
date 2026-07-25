<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
require_once __DIR__ . '/../../models/Address.php';

CustomerMiddleware::handle();

$pageTitle = "Select Delivery Address";
$addressModel = new Address();
$addresses = $addressModel->getByUserId($_SESSION['user_id']);

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Step 1: Delivery Address</h2>
    <form action="<?= BASE_URL ?>customer/checkout/payment.php" method="POST" class="mt-4">
        <div class="row g-3">
            <?php foreach ($addresses as $addr): ?>
                <div class="col-md-6">
                    <label class="glass-card p-3 w-100 d-flex gap-3 cursor-pointer">
                        <input type="radio" name="address_id" value="<?= $addr['id'] ?>" <?= $addr['is_default'] ? 'checked' : '' ?> required>
                        <div>
                            <strong><?= htmlspecialchars($addr['title']) ?></strong>
                            <p class="mb-0 text-muted small"><?= htmlspecialchars($addr['address_line']) ?></p>
                        </div>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Continue to Payment</button>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
