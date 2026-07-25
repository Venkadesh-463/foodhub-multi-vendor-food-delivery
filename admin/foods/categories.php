<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../models/Category.php';

AdminMiddleware::handle();
$pageTitle = "Global Categories";

$catModel = new Category();
$categories = $catModel->getAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Global Food Categories</h2>
    <div class="glass-card p-3 mt-3">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Icon</th><th>Category Name</th></tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><i class="fa-solid <?= $c['icon'] ?>"></i></td>
                        <td><?= htmlspecialchars($c['name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
