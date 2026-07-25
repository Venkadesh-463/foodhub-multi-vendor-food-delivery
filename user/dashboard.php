<?php
$pageTitle = "Customer Dashboard";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/../classes/Food.php';

requireRole(ROLE_USER);

$orderModel = new Order();
$foodModel = new Food();

$userOrders = $orderModel->getUserOrders($_SESSION['user_id']);
$recentItems = $foodModel->getAll(null, null, true);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="margin-bottom: 2rem;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);">Welcome back, <?= sanitize($_SESSION['user_name']) ?>! 👋</h1>
      <p style="color: var(--gray-600);">Here is a summary of your recent food delivery activity.</p>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-receipt"></i></div>
        <div>
          <div class="stat-value"><?= count($userOrders) ?></div>
          <div class="stat-label">Total Orders</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #166534;"><i class="fas fa-truck-loading"></i></div>
        <div>
          <div class="stat-value">
            <?= count(array_filter($userOrders, fn($o) => in_array($o['order_status'], ['pending', 'preparing', 'out_for_delivery']))) ?>
          </div>
          <div class="stat-label">Active Deliveries</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #92400e;"><i class="fas fa-heart"></i></div>
        <div>
          <div class="stat-value">2</div>
          <div class="stat-label">Saved Favorites</div>
        </div>
      </div>
    </div>

    <!-- Active & Recent Orders -->
    <div style="margin-bottom: 3rem;">
      <h2 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 1rem;">Recent Orders</h2>
      <div class="table-container">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Restaurant</th>
              <th>Total Amount</th>
              <th>Status</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($userOrders)): ?>
              <tr>
                <td colspan="6" style="text-align: center; color: var(--gray-600); padding: 2rem;">No order history found. Start ordering today!</td>
              </tr>
            <?php else: ?>
              <?php foreach (array_slice($userOrders, 0, 5) as $order): ?>
                <tr>
                  <td><strong><?= $order['order_number'] ?></strong></td>
                  <td><?= sanitize($order['restaurant_name']) ?></td>
                  <td><strong><?= formatPrice($order['final_amount']) ?></strong></td>
                  <td><span class="status-badge status-<?= $order['order_status'] ?>"><?= str_replace('_', ' ', $order['order_status']) ?></span></td>
                  <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                  <td>
                    <a href="<?= BASE_URL ?>user/orders.php?id=<?= $order['id'] ?>" class="btn btn-outline btn-sm">View Details</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
