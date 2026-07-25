<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Food.php';

$id = (int)($_GET['id'] ?? 0);
$foodModel = new Food();
$food = $foodModel->findById($id);

echo json_encode(['success' => (bool)$food, 'food' => $food]);
