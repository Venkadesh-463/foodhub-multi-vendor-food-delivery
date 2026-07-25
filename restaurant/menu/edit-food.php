<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Food.php';
require_once __DIR__ . '/../../models/Category.php';

RestaurantMiddleware::handle();
$pageTitle = "Edit Menu Item";

$id = (int)($_GET['id'] ?? 0);
$foodModel = new Food();
$food = $foodModel->findById($id);

$catModel = new Category();
$categories = $catModel->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $food) {
    $foodModel->update($id, [
        'category_id' => $_POST['category_id'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'image' => $food['image'],
        'is_veg' => isset($_POST['is_veg']) ? 1 : 0,
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'status' => $_POST['status'] ?? 'available'
    ]);
    flash('success', 'Food item updated.');
    redirect(BASE_URL . 'restaurant/dashboard.php');
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Edit Food Item</h2>
    <?php if ($food): ?>
        <div class="glass-card p-4 col-md-6 mt-3">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($food['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?= $food['price'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $c['id'] == $food['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($food['description']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Item</button>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Food item not found.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
