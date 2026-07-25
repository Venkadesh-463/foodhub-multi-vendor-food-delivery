<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Restaurant.php';

RestaurantMiddleware::handle();
$pageTitle = "Restaurant Profile Settings";

$restModel = new Restaurant();
$restaurant = $restModel->findByUserId($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $restaurant) {
    $restModel->update($restaurant['id'], $_POST);
    flash('success', 'Restaurant settings saved.');
    redirect(BASE_URL . 'restaurant/settings/profile.php');
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Restaurant Profile</h2>
    <?php if ($restaurant): ?>
        <div class="glass-card p-4 col-md-6 mt-3">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Restaurant Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($restaurant['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cuisine Type</label>
                    <input type="text" name="cuisine" class="form-control" value="<?= htmlspecialchars($restaurant['cuisine']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($restaurant['address']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Profile</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
