<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "All Platform Orders";

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT o.*, u.name AS customer_name, r.name AS restaurant_name FROM orders o JOIN users u ON u.id = o.user_id JOIN restaurants r ON r.id = o.restaurant_id ORDER BY o.id DESC");
$orders = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>All Platform Orders</h2>
    <div class="glass-card p-3 mt-3">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>Order #</th><th>Customer</th><th>Restaurant</th><th>Total</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>#<?= $o['order_number'] ?></td>
                        <td><?= htmlspecialchars($o['customer_name']) ?></td>
                        <td><?= htmlspecialchars($o['restaurant_name']) ?></td>
                        <td><?= formatPrice($o['total_amount']) ?></td>
                        <td><span class="badge bg-info"><?= ucfirst($o['order_status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
