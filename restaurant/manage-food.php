<?php
$pageTitle = "Manage Food Items";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Restaurant.php';
require_once __DIR__ . '/../classes/Food.php';

requireRole([ROLE_RESTAURANT, ROLE_ADMIN]);

$restaurantModel = new Restaurant();
$foodModel = new Food();

$restaurant = $restaurantModel->getByUserId($_SESSION['user_id']);
$restaurantId = $restaurant['id'] ?? 1;

if (isset($_GET['delete'])) {
    $delId = intval($_GET['delete']);
    $foodModel->delete($delId);
    flash('success', 'Food item deleted successfully.', 'success');
    redirect(BASE_URL . 'restaurant/manage-food.php');
}

$foodItems = $foodModel->getAll($restaurantId);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);">Manage Menu Dishes</h1>
      <a href="<?= BASE_URL ?>restaurant/add-food.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Item</a>
    </div>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Item Name</th>
            <th>Category</th>
            <th>Type</th>
            <th>Price</th>
            <th>Featured</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($foodItems)): ?>
            <tr>
              <td colspan="6" style="text-align: center; color: var(--gray-600); padding: 2rem;">No items created yet.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($foodItems as $food): ?>
              <tr>
                <td><strong><?= sanitize($food['name']) ?></strong></td>
                <td><?= sanitize($food['category_name']) ?></td>
                <td>
                  <span class="badge-tag <?= $food['is_veg'] ? 'badge-veg' : 'badge-nonveg' ?>" style="position: static;">
                    <?= $food['is_veg'] ? 'Veg' : 'Non-Veg' ?>
                  </span>
                </td>
                <td><strong><?= formatPrice($food['price']) ?></strong></td>
                <td><?= $food['is_featured'] ? '★ Yes' : 'No' ?></td>
                <td>
                  <a href="<?= BASE_URL ?>restaurant/manage-food.php?delete=<?= $food['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?')" class="btn btn-sm" style="background: #fee2e2; color: #991b1b;"><i class="fas fa-trash"></i> Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
