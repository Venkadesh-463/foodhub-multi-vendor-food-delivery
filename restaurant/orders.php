<?php
$pageTitle = "Store Orders";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Restaurant.php';
require_once __DIR__ . '/../classes/Order.php';

requireRole(ROLE_RESTAURANT);

$restaurantModel = new Restaurant();
$orderModel = new Order();

$restaurant = $restaurantModel->getByUserId($_SESSION['user_id']);
$restaurantId = $restaurant['id'] ?? 1;

$orders = $orderModel->getRestaurantOrders($restaurantId);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">All Store Orders</h1>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Address / Instructions</th>
            <th>Total</th>
            <th>Order Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
            <tr>
              <td colspan="6" style="text-align: center; color: var(--gray-600); padding: 2rem;">No orders recorded.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($orders as $ord): ?>
              <tr>
                <td><strong><?= $ord['order_number'] ?></strong></td>
                <td><?= sanitize($ord['customer_name']) ?><br><span style="font-size: 0.8rem; color: var(--gray-600);"><?= sanitize($ord['customer_phone'] ?? '') ?></span></td>
                <td style="max-width: 200px; font-size: 0.85rem;"><?= sanitize($ord['delivery_address']) ?></td>
                <td><strong><?= formatPrice($ord['final_amount']) ?></strong></td>
                <td><span class="status-badge status-<?= $ord['order_status'] ?>"><?= str_replace('_', ' ', $ord['order_status']) ?></span></td>
                <td>
                  <div style="display: flex; gap: 0.4rem;">
                    <button onclick="updateOrderStatus(<?= $ord['id'] ?>, 'preparing')" class="btn btn-outline btn-sm">Preparing</button>
                    <button onclick="updateOrderStatus(<?= $ord['id'] ?>, 'ready_for_delivery')" class="btn btn-primary btn-sm">Ready</button>
                  </div>
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
