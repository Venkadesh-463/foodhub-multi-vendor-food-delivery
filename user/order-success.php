<?php
$pageTitle = "Order Confirmed!";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Order.php';

$orderId = intval($_GET['id'] ?? 0);
$orderModel = new Order();
$order = $orderModel->getOrderById($orderId);

if (!$order) {
    redirect(BASE_URL . 'user/orders.php');
}
?>

<div class="section" style="padding-top: 4rem; padding-bottom: 6rem; max-width: 650px; text-align: center;">
  <div style="background: var(--white); padding: 3.5rem 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-lg);">
    <div style="width: 80px; height: 80px; background: #dcfce7; color: #166534; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem auto;">
      <i class="fas fa-check"></i>
    </div>

    <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">Order Placed Successfully!</h1>
    <p style="color: var(--gray-600); font-size: 1rem; margin-bottom: 2rem;">Order Reference: <strong><?= $order['order_number'] ?></strong></p>

    <div style="background: var(--gray-100); padding: 1.5rem; border-radius: var(--radius-md); text-align: left; margin-bottom: 2rem;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
        <span>Restaurant:</span>
        <strong><?= sanitize($order['restaurant_name']) ?></strong>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
        <span>Total Paid:</span>
        <strong><?= formatPrice($order['final_amount']) ?></strong>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Status:</span>
        <span class="status-badge status-<?= $order['order_status'] ?>"><?= str_replace('_', ' ', $order['order_status']) ?></span>
      </div>
    </div>

    <div style="display: flex; gap: 1rem; justify-content: center;">
      <a href="<?= BASE_URL ?>user/orders.php?id=<?= $order['id'] ?>" class="btn btn-primary">Track Order Progress</a>
      <a href="<?= BASE_URL ?>index.php" class="btn btn-outline">Back to Home</a>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
