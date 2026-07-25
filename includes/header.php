<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../classes/Cart.php';

$cartHelper = new Cart();
$cartTotals = $cartHelper->getTotals($_SESSION['user_id'] ?? null);
$authUser = getAuthUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' | ' . SITE_NAME : SITE_NAME . ' - ' . SITE_SLOGAN ?></title>
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Application Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dashboard.css">
    <script>
        window.FOODHUB_BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>

<!-- Flash Message Notifications -->
<?php 
$flashError = flash('error');
$flashSuccess = flash('success');
$flashInfo = flash('info');
?>
<?php if ($flashError): ?>
    <script>document.addEventListener('DOMContentLoaded', () => showToast("<?= addslashes($flashError['message']) ?>", "danger"));</script>
<?php endif; ?>
<?php if ($flashSuccess): ?>
    <script>document.addEventListener('DOMContentLoaded', () => showToast("<?= addslashes($flashSuccess['message']) ?>", "success"));</script>
<?php endif; ?>
<?php if ($flashInfo): ?>
    <script>document.addEventListener('DOMContentLoaded', () => showToast("<?= addslashes($flashInfo['message']) ?>", "info"));</script>
<?php endif; ?>
