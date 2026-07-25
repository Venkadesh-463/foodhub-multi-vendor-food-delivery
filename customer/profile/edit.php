<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
require_once __DIR__ . '/../../models/User.php';

CustomerMiddleware::handle();
$pageTitle = "Edit Profile";

$userModel = new User();
$user = $userModel->findById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userModel->updateProfile($_SESSION['user_id'], $_POST);
    $_SESSION['user_name'] = $_POST['name'];
    flash('success', 'Profile updated successfully.');
    redirect(BASE_URL . 'customer/profile/edit.php');
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Edit Profile</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
