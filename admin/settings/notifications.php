<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/AdminMiddleware.php';

AdminMiddleware::handle();
$pageTitle = "Global System Notifications";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container-fluid py-4">
    <h2>Notification Gateway Settings</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <div class="mb-3">
            <label class="form-label">Twilio SMS Gateway SID</label>
            <input type="text" class="form-control" value="AC_sample_twilio_sid">
        </div>
        <div class="mb-3">
            <label class="form-label">SMTP Mail Host</label>
            <input type="text" class="form-control" value="smtp.mailtrap.io">
        </div>
        <button class="btn btn-primary">Save Gateways</button>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
