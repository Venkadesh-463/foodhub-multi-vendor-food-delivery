<?php
$pageTitle = "Available Deliveries";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Order.php';

requireRole(ROLE_DELIVERY);

$orderModel = new Order();
$driverOrders = $orderModel->getDriverOrders($_SESSION['user_id']);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">Active Deliveries List</h1>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Pickup Location</th>
            <th>Dropoff Address</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($driverOrders as $ord): ?>
            <tr>
              <td><strong><?= $ord['order_number'] ?></strong></td>
              <td><?= sanitize($ord['restaurant_name']) ?></td>
              <td><?= sanitize($ord['delivery_address']) ?></td>
              <td><span class="status-badge status-<?= $ord['order_status'] ?>"><?= str_replace('_', ' ', $ord['order_status']) ?></span></td>
              <td>
                <button onclick="updateOrderStatus(<?= $ord['id'] ?>, 'out_for_delivery')" class="btn btn-outline btn-sm">Out for Delivery</button>
              </td>
            </tr>
          <?php endforeach; ?>
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
