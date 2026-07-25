<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../models/UserModel.php';

AdminMiddleware::handle();
$pageTitle = "Customer Management";

$userModel = new UserModel();
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT * FROM users WHERE role = 'user' ORDER BY id DESC");
$customers = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Manage Customers</h2>
    <div class="glass-card p-3 mt-3">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['phone'] ?? '-') ?></td>
                        <td><span class="badge bg-success"><?= ucfirst($c['status'] ?? 'active') ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
