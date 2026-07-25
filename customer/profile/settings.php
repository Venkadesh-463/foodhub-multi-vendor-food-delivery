<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';

CustomerMiddleware::handle();
$pageTitle = "Account Settings";

include __DIR__ . '/../../includes/header.php';
?>
<div class="container py-4">
    <h2>Account Settings</h2>
    <div class="glass-card p-4 col-md-6 mt-3">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="emailNotif" checked>
            <label class="form-check-label" for="emailNotif">Email Notifications</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="smsNotif" checked>
            <label class="form-check-label" for="smsNotif">SMS Notifications</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="promoNotif" checked>
            <label class="form-check-label" for="promoNotif">Promotional Offers & Discounts</label>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
