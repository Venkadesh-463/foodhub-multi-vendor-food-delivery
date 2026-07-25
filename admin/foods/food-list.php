<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "All Food Items";

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT f.*, r.name AS restaurant_name FROM food_items f JOIN restaurants r ON r.id = f.restaurant_id ORDER BY f.id DESC");
$foods = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>All Catalog Foods</h2>
    <div class="glass-card p-3 mt-3">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Food Name</th><th>Restaurant</th><th>Price</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($foods as $f): ?>
                    <tr>
                        <td><?= $f['id'] ?></td>
                        <td><?= htmlspecialchars($f['name']) ?></td>
                        <td><?= htmlspecialchars($f['restaurant_name']) ?></td>
                        <td><?= formatPrice($f['price']) ?></td>
                        <td><span class="badge bg-success"><?= ucfirst($f['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
