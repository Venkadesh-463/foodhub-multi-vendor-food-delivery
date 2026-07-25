<?php
$pageTitle = "All Platform Orders";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Order.php';

requireRole(ROLE_ADMIN);

$orderModel = new Order();
$orders = $orderModel->getAllOrders();
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">Global Platform Orders Oversight</h1>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Restaurant</th>
            <th>Amount</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $ord): ?>
            <tr>
              <td><strong><?= $ord['order_number'] ?></strong></td>
              <td><?= sanitize($ord['customer_name']) ?></td>
              <td><?= sanitize($ord['restaurant_name']) ?></td>
              <td><strong><?= formatPrice($ord['final_amount']) ?></strong></td>
              <td><span class="status-badge status-<?= $ord['payment_status'] ?>"><?= ucfirst($ord['payment_status']) ?></span></td>
              <td><span class="status-badge status-<?= $ord['order_status'] ?>"><?= str_replace('_', ' ', $ord['order_status']) ?></span></td>
              <td><?= date('M d, Y H:i', strtotime($ord['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
