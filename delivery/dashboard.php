<?php
$pageTitle = "Driver Dashboard";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Order.php';

requireRole(ROLE_DELIVERY);

$orderModel = new Order();
$driverOrders = $orderModel->getDriverOrders($_SESSION['user_id']);

$activeDeliveries = array_filter($driverOrders, fn($o) => in_array($o['order_status'], ['ready_for_delivery', 'out_for_delivery', 'preparing']));
$completedDeliveries = array_filter($driverOrders, fn($o) => $o['order_status'] === 'delivered');
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="margin-bottom: 2rem;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);">Driver Portal - <?= sanitize($_SESSION['user_name']) ?> 🛵</h1>
      <p style="color: var(--gray-600);">View assigned food delivery tasks and update dispatch progress.</p>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background: #e0f2fe; color: #075985;"><i class="fas fa-motorcycle"></i></div>
        <div>
          <div class="stat-value"><?= count($activeDeliveries) ?></div>
          <div class="stat-label">Active Trip Tasks</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #166534;"><i class="fas fa-check-circle"></i></div>
        <div>
          <div class="stat-value"><?= count($completedDeliveries) ?></div>
          <div class="stat-label">Completed Drops</div>
        </div>
      </div>
    </div>

    <h2 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 1rem;">Active Delivery Jobs</h2>
    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Pickup Restaurant</th>
            <th>Customer & Address</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($driverOrders)): ?>
            <tr>
              <td colspan="5" style="text-align: center; color: var(--gray-600); padding: 2rem;">No delivery assignments at this moment.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($driverOrders as $ord): ?>
              <tr>
                <td><strong><?= $ord['order_number'] ?></strong></td>
                <td><?= sanitize($ord['restaurant_name']) ?><br><span style="font-size: 0.8rem; color: var(--gray-600);"><?= sanitize($ord['restaurant_address']) ?></span></td>
                <td><strong><?= sanitize($ord['customer_name']) ?></strong><br><span style="font-size: 0.85rem; color: var(--gray-600);"><?= sanitize($ord['delivery_address']) ?></span></td>
                <td><span class="status-badge status-<?= $ord['order_status'] ?>"><?= str_replace('_', ' ', $ord['order_status']) ?></span></td>
                <td>
                  <button onclick="updateOrderStatus(<?= $ord['id'] ?>, 'out_for_delivery')" class="btn btn-outline btn-sm">Picked Up</button>
                  <button onclick="updateOrderStatus(<?= $ord['id'] ?>, 'delivered')" class="btn btn-primary btn-sm">Delivered</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script>
async function updateOrderStatus(orderId, status) {
    try {
        const response = await fetch(`${FOODHUB_BASE_URL}api/order.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'update_status', order_id: orderId, status: status })
        });
        const data = await response.json();
        showToast(data.message || 'Status updated', 'success');
        setTimeout(() => window.location.reload(), 800);
    } catch(e) {
        window.location.reload();
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
