<?php
$pageTitle = "Restaurant Management";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Restaurant.php';

requireRole(ROLE_ADMIN);

$restaurantModel = new Restaurant();

if (isset($_GET['status']) && isset($_GET['id'])) {
    $restaurantModel->updateStatus(intval($_GET['id']), sanitize($_GET['status']));
    flash('success', 'Restaurant approval status updated.', 'success');
    redirect(BASE_URL . 'admin/restaurants.php');
}

$restaurants = $restaurantModel->getAll(''); // fetch all statuses
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin: 0;">Partner Restaurants Approval & Oversight</h1>
      <a href="<?= BASE_URL ?>admin/add-restaurant.php" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Add New Restaurant</a>
    </div>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Owner</th>
            <th>Cuisine</th>
            <th>Phone</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($restaurants as $r): ?>
            <tr>
              <td>#<?= $r['id'] ?></td>
              <td><strong><?= sanitize($r['name']) ?></strong></td>
              <td><?= sanitize($r['owner_name']) ?></td>
              <td><?= sanitize($r['cuisine']) ?></td>
              <td><?= sanitize($r['phone']) ?></td>
              <td><i class="fas fa-star" style="color: #f59e0b;"></i> <?= $r['rating'] ?></td>
              <td><span class="status-badge status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
              <td>
                <?php if ($r['status'] !== 'approved'): ?>
                  <a href="<?= BASE_URL ?>admin/restaurants.php?id=<?= $r['id'] ?>&status=approved" class="btn btn-sm" style="background: #dcfce7; color: #166534;">Approve Store</a>
                <?php else: ?>
                  <a href="<?= BASE_URL ?>admin/restaurants.php?id=<?= $r['id'] ?>&status=rejected" class="btn btn-sm" style="background: #fee2e2; color: #991b1b;">Reject / Suspend</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
