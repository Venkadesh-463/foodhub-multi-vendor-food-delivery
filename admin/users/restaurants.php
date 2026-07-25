<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../models/Restaurant.php';

AdminMiddleware::handle();
$pageTitle = "Restaurant Management";

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT r.*, u.name AS owner_name FROM restaurants r JOIN users u ON u.id = r.user_id ORDER BY r.id DESC");
$restaurants = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Manage Restaurants</h2>
    <div class="glass-card p-3 mt-3">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Restaurant Name</th><th>Owner</th><th>Cuisine</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($restaurants as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['name']) ?></td>
                        <td><?= htmlspecialchars($r['owner_name']) ?></td>
                        <td><?= htmlspecialchars($r['cuisine']) ?></td>
                        <td><span class="badge bg-primary"><?= ucfirst($r['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
