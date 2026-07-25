<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../middleware/RestaurantMiddleware.php';
require_once __DIR__ . '/../../models/Food.php';

RestaurantMiddleware::handle();
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $foodModel = new Food();
    $foodModel->delete($id);
    flash('info', 'Food item removed.');
}
redirect(BASE_URL . 'restaurant/dashboard.php');
