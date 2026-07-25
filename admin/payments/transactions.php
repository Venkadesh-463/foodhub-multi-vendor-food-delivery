<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../models/Transaction.php';

AdminMiddleware::handle();
$pageTitle = "Payment Transactions";

$txnModel = new Transaction();
$transactions = $txnModel->getAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Payment Transactions</h2>
    <div class="glass-card p-3 mt-3">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>Txn ID</th><th>Order #</th><th>Customer</th><th>Method</th><th>Amount</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><code><?= $t['transaction_id'] ?></code></td>
                        <td>#<?= $t['order_number'] ?></td>
                        <td><?= htmlspecialchars($t['customer_name']) ?></td>
                        <td><?= strtoupper($t['payment_method']) ?></td>
                        <td><?= formatPrice($t['amount']) ?></td>
                        <td><span class="badge bg-success"><?= ucfirst($t['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
