<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Order.php';

$id = (int)($_GET['id'] ?? 1);
$orderModel = new Order();
$order = $orderModel->findById($id);
$items = $order ? $orderModel->getItems($id) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $order['order_number'] ?? '0' ?></title>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #fff; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>FOODHUB INVOICE</h2>
    <p>Order #: <?= htmlspecialchars($order['order_number'] ?? '') ?></p>
    <p>Customer: <?= htmlspecialchars($order['customer_name'] ?? '') ?></p>
    <table>
        <thead>
            <tr><th>Item</th><th>Qty</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            <?php foreach ($items as $it): ?>
                <tr>
                    <td><?= htmlspecialchars($it['food_name']) ?></td>
                    <td><?= $it['quantity'] ?></td>
                    <td><?= formatPrice($it['subtotal']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
