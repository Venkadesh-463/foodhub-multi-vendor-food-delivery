<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Food.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/Restaurant.php';

RestaurantMiddleware::handle();
$pageTitle = "Add Menu Item";

$catModel = new Category();
$categories = $catModel->getAll();

$restModel = new Restaurant();
$restaurant = $restModel->findByUserId($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $restaurant) {
    $foodModel = new Food();
    $foodModel->create([
        'restaurant_id' => $restaurant['id'],
        'category_id' => $_POST['category_id'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'is_veg' => isset($_POST['is_veg']) ? 1 : 0,
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0
    ]);
    flash('success', 'Food item added to menu.');
    redirect(BASE_URL . 'restaurant/dashboard.php');
}

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Add Food Item</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Item Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_veg" id="veg">
                <label class="form-check-label" for="veg">Vegetarian Item</label>
            </div>
            <button type="submit" class="btn btn-success">Save Item</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
