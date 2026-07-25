<?php
$pageTitle = "Food Management";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Food.php';

requireRole(ROLE_ADMIN);

$foodModel = new Food();

if (isset($_GET['delete'])) {
    $foodModel->delete(intval($_GET['delete']));
    flash('success', 'Food item removed.', 'success');
    redirect(BASE_URL . 'admin/food-management.php');
}

$allItems = $foodModel->getAll();
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">All Food Items Catalog</h1>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Dish Name</th>
            <th>Restaurant</th>
            <th>Category</th>
            <th>Price</th>
            <th>Dietary</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($allItems as $item): ?>
            <tr>
              <td><strong><?= sanitize($item['name']) ?></strong></td>
              <td><?= sanitize($item['restaurant_name']) ?></td>
              <td><?= sanitize($item['category_name']) ?></td>
              <td><strong><?= formatPrice($item['price']) ?></strong></td>
              <td>
                <span class="badge-tag <?= $item['is_veg'] ? 'badge-veg' : 'badge-nonveg' ?>" style="position: static;">
                  <?= $item['is_veg'] ? 'Veg' : 'Non-Veg' ?>
                </span>
              </td>
              <td>
                <a href="<?= BASE_URL ?>admin/food-management.php?delete=<?= $item['id'] ?>" onclick="return confirm('Delete this menu item?')" class="btn btn-sm" style="background: #fee2e2; color: #991b1b;">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
