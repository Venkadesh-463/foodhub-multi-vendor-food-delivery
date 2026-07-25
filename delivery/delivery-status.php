<?php
$pageTitle = "Trip History";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Order.php';

requireRole(ROLE_DELIVERY);

$orderModel = new Order();
$driverOrders = $orderModel->getDriverOrders($_SESSION['user_id']);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">Completed Trips & Log History</h1>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Delivery Location</th>
            <th>Order Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($driverOrders as $ord): ?>
            <tr>
              <td><strong><?= $ord['order_number'] ?></strong></td>
              <td><?= sanitize($ord['customer_name']) ?></td>
              <td><?= sanitize($ord['delivery_address']) ?></td>
              <td><strong><?= formatPrice($ord['final_amount']) ?></strong></td>
              <td><span class="status-badge status-<?= $ord['order_status'] ?>"><?= str_replace('_', ' ', $ord['order_status']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
