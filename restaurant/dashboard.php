<?php
$pageTitle = "Restaurant Dashboard";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Restaurant.php';
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/../classes/Food.php';

requireRole(ROLE_RESTAURANT);

$restaurantModel = new Restaurant();
$orderModel = new Order();
$foodModel = new Food();

$restaurant = $restaurantModel->getByUserId($_SESSION['user_id']);
$restaurantId = $restaurant['id'] ?? 1;

$orders = $orderModel->getRestaurantOrders($restaurantId);
$foodItems = $foodModel->getAll($restaurantId);

$totalRevenue = array_reduce($orders, fn($acc, $o) => $acc + ($o['payment_status'] === 'paid' ? floatval($o['final_amount']) : 0), 0);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
      <div>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);"><?= sanitize($restaurant['name'] ?? 'Restaurant Partner') ?> Overview</h1>
        <p style="color: var(--gray-600);">Monitor incoming customer orders and kitchen menu statistics.</p>
      </div>
      <a href="<?= BASE_URL ?>restaurant/add-food.php" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Add Food Item</a>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #166534;"><i class="fas fa-dollar-sign"></i></div>
        <div>
          <div class="stat-value"><?= formatPrice($totalRevenue) ?></div>
          <div class="stat-label">Total Earnings</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
        <div>
          <div class="stat-value"><?= count($orders) ?></div>
          <div class="stat-label">Total Received Orders</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #e0f2fe; color: #075985;"><i class="fas fa-utensils"></i></div>
        <div>
          <div class="stat-value"><?= count($foodItems) ?></div>
          <div class="stat-label">Active Menu Dishes</div>
        </div>
      </div>
    </div>

    <!-- Active Orders Table -->
    <h2 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 1rem;">Incoming Customer Orders</h2>
    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
            <tr>
              <td colspan="6" style="text-align: center; color: var(--gray-600); padding: 2rem;">No orders received yet.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($orders as $ord): ?>
              <tr>
                <td><strong><?= $ord['order_number'] ?></strong></td>
                <td><?= sanitize($ord['customer_name']) ?></td>
                <td><?= sanitize($ord['customer_phone'] ?? 'N/A') ?></td>
                <td><strong><?= formatPrice($ord['final_amount']) ?></strong></td>
                <td><span class="status-badge status-<?= $ord['order_status'] ?>"><?= str_replace('_', ' ', $ord['order_status']) ?></span></td>
                <td>
                  <button onclick="updateOrderStatus(<?= $ord['id'] ?>, 'preparing')" class="btn btn-outline btn-sm">Set Preparing</button>
                  <button onclick="updateOrderStatus(<?= $ord['id'] ?>, 'ready_for_delivery')" class="btn btn-primary btn-sm">Set Ready</button>
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
