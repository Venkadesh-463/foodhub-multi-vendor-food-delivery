<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
require_once __DIR__ . '/../../models/Address.php';

CustomerMiddleware::handle();
$pageTitle = "My Addresses";

$addressModel = new Address();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $addressModel->add($_SESSION['user_id'], $_POST['title'], $_POST['address_line'], $_POST['city'], $_POST['postal_code'], true);
    flash('success', 'Address added!');
    redirect(BASE_URL . 'customer/profile/addresses.php');
}

$addresses = $addressModel->getByUserId($_SESSION['user_id']);

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Manage Addresses</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="glass-card p-4">
                <h4>Add New Address</h4>
                <form method="POST">
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Address Title (e.g. Home, Office)" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="address_line" class="form-control" placeholder="Street Address" required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col"><input type="text" name="city" class="form-control" placeholder="City"></div>
                        <div class="col"><input type="text" name="postal_code" class="form-control" placeholder="Zip/Postal"></div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Address</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <?php foreach ($addresses as $a): ?>
                <div class="glass-card p-3 mb-3">
                    <strong><?= htmlspecialchars($a['title']) ?></strong>
                    <p class="mb-0 text-muted small"><?= htmlspecialchars($a['address_line']) ?>, <?= htmlspecialchars($a['city']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
