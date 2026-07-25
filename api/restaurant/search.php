<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Restaurant.php';

$query = $_GET['q'] ?? '';
$restaurantModel = new Restaurant();
$restaurants = $restaurantModel->getAll(['search' => $query]);

echo json_encode(['success' => true, 'restaurants' => $restaurants]);
