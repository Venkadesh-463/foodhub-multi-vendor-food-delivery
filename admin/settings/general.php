<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "General System Settings";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Platform System Settings</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <div class="mb-3">
            <label class="form-label">Application Name</label>
            <input type="text" class="form-control" value="<?= SITE_NAME ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Support Email</label>
            <input type="email" class="form-control" value="support@foodhub.com">
        </div>
        <button class="btn btn-primary">Save Platform Config</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
