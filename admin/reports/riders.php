<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Rider Performance Report";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Rider Fleet Efficiency</h2>
    <div class="glass-card p-4 mt-3">
        <p class="text-muted mb-0">Average delivery turnaround time and rider ratings.</p>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
