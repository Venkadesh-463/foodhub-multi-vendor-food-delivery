<?php
$pageTitle = "My Orders";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Order.php';

requireRole(ROLE_USER);

$orderModel = new Order();
$selectedOrderId = intval($_GET['id'] ?? 0);
$selectedOrder = null;

if ($selectedOrderId > 0) {
    $selectedOrder = $orderModel->getOrderById($selectedOrderId);
}

$userOrders = $orderModel->getUserOrders($_SESSION['user_id']);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <?php if ($selectedOrder): ?>
      <div style="margin-bottom: 2rem;">
        <a href="<?= BASE_URL ?>user/orders.php" class="btn btn-outline btn-sm" style="margin-bottom: 1rem;"><i class="fas fa-arrow-left"></i> Back to Orders</a>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);">Order Details: <?= $selectedOrder['order_number'] ?></h1>
      </div>

      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div style="background: var(--white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);">
          <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem; border-bottom: 1px solid var(--gray-200); padding-bottom: 0.5rem;">Ordered Items</h3>
          
          <?php foreach ($selectedOrder['items'] as $item): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--gray-100);">
              <div>
                <strong style="display: block;"><?= sanitize($item['food_name']) ?></strong>
                <span style="font-size: 0.85rem; color: var(--gray-600);">Qty: <?= $item['quantity'] ?> x <?= formatPrice($item['price']) ?></span>
              </div>
              <span style="font-weight: 700; color: var(--primary);"><?= formatPrice($item['subtotal']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <div style="background: var(--white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); height: fit-content;">
          <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem;">Status Timeline</h3>
          <div style="padding: 1rem; background: var(--gray-100); border-radius: var(--radius-md); text-align: center; margin-bottom: 1.5rem;">
            <span class="status-badge status-<?= $selectedOrder['order_status'] ?>" style="font-size: 1rem; padding: 0.5rem 1rem;">
              <?= str_replace('_', ' ', strtoupper($selectedOrder['order_status'])) ?>
            </span>
          </div>
          <p style="font-size: 0.9rem; color: var(--gray-600);"><strong>Delivery Address:</strong><br><?= sanitize($selectedOrder['delivery_address']) ?></p>
        </div>
      </div>

    <?php else: ?>
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">My Order History</h1>

      <div class="table-container">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Restaurant</th>
              <th>Items</th>
              <th>Total Amount</th>
              <th>Status</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($userOrders)): ?>
              <tr>
                <td colspan="7" style="text-align: center; color: var(--gray-600); padding: 2rem;">No orders placed yet.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($userOrders as $order): ?>
                <tr>
                  <td><strong><?= $order['order_number'] ?></strong></td>
                  <td><?= sanitize($order['restaurant_name']) ?></td>
                  <td><?= $order['item_count'] ?> items</td>
                  <td><strong><?= formatPrice($order['final_amount']) ?></strong></td>
                  <td><span class="status-badge status-<?= $order['order_status'] ?>"><?= str_replace('_', ' ', $order['order_status']) ?></span></td>
                  <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                  <td>
                    <a href="<?= BASE_URL ?>user/orders.php?id=<?= $order['id'] ?>" class="btn btn-outline btn-sm">View Details</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
