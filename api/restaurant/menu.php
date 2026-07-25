<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Food.php';

$restaurantId = (int)($_GET['restaurant_id'] ?? 0);
$foodModel = new Food();
$items = $foodModel->getByRestaurant($restaurantId);

echo json_encode(['success' => true, 'menu' => $items]);
