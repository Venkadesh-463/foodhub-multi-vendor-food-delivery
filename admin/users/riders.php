<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Rider Management";

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT * FROM users WHERE role = 'delivery' ORDER BY id DESC");
$riders = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Manage Delivery Riders</h2>
    <div class="glass-card p-3 mt-3">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($riders as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['name']) ?></td>
                        <td><?= htmlspecialchars($r['email']) ?></td>
                        <td><?= htmlspecialchars($r['phone'] ?? '-') ?></td>
                        <td><span class="badge bg-success"><?= ucfirst($r['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
