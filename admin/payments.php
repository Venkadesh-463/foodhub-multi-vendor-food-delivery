<?php
$pageTitle = "Financial Transactions";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Payment.php';

requireRole(ROLE_ADMIN);

$paymentModel = new Payment();
$payments = $paymentModel->getAllPayments();
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">Financial Transactions & Ledger</h1>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Txn ID</th>
            <th>Order #</th>
            <th>Customer</th>
            <th>Restaurant</th>
            <th>Method</th>
            <th>Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($payments as $p): ?>
            <tr>
              <td><strong><?= $p['transaction_id'] ?></strong></td>
              <td><?= $p['order_number'] ?></td>
              <td><?= sanitize($p['customer_name']) ?></td>
              <td><?= sanitize($p['restaurant_name']) ?></td>
              <td><span style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem;"><?= $p['payment_method'] ?></span></td>
              <td><strong><?= formatPrice($p['amount']) ?></strong></td>
              <td><span class="status-badge status-active"><?= ucfirst($p['status']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
