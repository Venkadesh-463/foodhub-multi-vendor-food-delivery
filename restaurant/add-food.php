<?php
$pageTitle = "Add New Food Item";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Food.php';
require_once __DIR__ . '/../controllers/FoodController.php';

requireRole([ROLE_RESTAURANT, ROLE_ADMIN]);

$foodModel = new Food();
$categories = $foodModel->getCategories();

$foodController = new FoodController();
$foodController->handleAddFood();
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="max-width: 650px;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">Add New Menu Item</h1>

      <div style="background: var(--white); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
        <form action="" method="POST" enctype="multipart/form-data" data-validate="true">
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Item Name</label>
            <input type="text" name="name" required placeholder="e.g. Signature Truffle Pizza" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Category</label>
            <select name="category_id" required style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= sanitize($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Price (<?= currSymbol() ?>)</label>
            <input type="number" step="0.01" name="price" required placeholder="14.99" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Description &amp; Ingredients</label>
            <textarea name="description" rows="4" required placeholder="Describe the taste, ingredients, and preparation method..." style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;"></textarea>
          </div>

          <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Food Image</label>
            <input type="file" name="image" accept="image/*">
          </div>

          <div style="display: flex; gap: 2rem; margin-bottom: 2rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 600;">
              <input type="checkbox" name="is_veg" value="1"> Vegetarian Dish
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 600;">
              <input type="checkbox" name="is_featured" value="1"> Feature on Home Page
            </label>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
            <i class="fas fa-plus"></i> Save &amp; Publish Item
          </button>
        </form>
      </div>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
