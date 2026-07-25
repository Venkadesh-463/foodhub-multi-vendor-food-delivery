<?php
$pageTitle = "Admin Control Panel";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../classes/Restaurant.php';

requireRole(ROLE_ADMIN);

$userModel = new UserModel();
$orderModel = new OrderModel();
$restaurantModel = new Restaurant();

$userStats = $userModel->getUserStats();
$orderStats = $orderModel->getAdminOrderStats();
$recentOrders = array_slice($orderModel->getAllOrders(), 0, 5);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="margin-bottom: 2rem;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);">System Administration Control Panel</h1>
      <p style="color: var(--gray-600);">Platform performance metric summary and global oversight.</p>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #166534;"><i class="fas fa-wallet"></i></div>
        <div>
          <div class="stat-value"><?= formatPrice($orderStats['total_revenue']) ?></div>
          <div class="stat-label">Total Platform Gross Revenue</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-shopping-basket"></i></div>
        <div>
          <div class="stat-value"><?= $orderStats['total_orders'] ?></div>
          <div class="stat-label">Total Orders Placed</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #e0f2fe; color: #075985;"><i class="fas fa-store"></i></div>
        <div>
          <div class="stat-value"><?= $userStats['total_restaurants'] ?></div>
          <div class="stat-label">Registered Restaurants</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #92400e;"><i class="fas fa-users"></i></div>
        <div>
          <div class="stat-value"><?= $userStats['total_users'] ?></div>
          <div class="stat-label">Customer Accounts</div>
        </div>
      </div>
    </div>

    <h2 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 1rem;">Recent Platform Orders</h2>
    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Restaurant</th>
            <th>Final Amount</th>
            <th>Order Status</th>
            <th>Payment Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentOrders as $ord): ?>
            <tr>
              <td><strong><?= $ord['order_number'] ?></strong></td>
              <td><?= sanitize($ord['customer_name']) ?></td>
              <td><?= sanitize($ord['restaurant_name']) ?></td>
              <td><strong><?= formatPrice($ord['final_amount']) ?></strong></td>
              <td><span class="status-badge status-<?= $ord['order_status'] ?>"><?= str_replace('_', ' ', $ord['order_status']) ?></span></td>
              <td><span class="status-badge status-<?= $ord['payment_status'] ?>"><?= ucfirst($ord['payment_status']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
