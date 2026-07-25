<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Surge Pricing Controls";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Surge Pricing Rules</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="surgeToggle" checked>
            <label class="form-check-label" for="surgeToggle">Enable Peak Hour Surge Pricing (1.25x)</label>
        </div>
        <button class="btn btn-warning">Save Surge Settings</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
