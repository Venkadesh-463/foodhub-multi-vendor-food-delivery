<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../models/Cart.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$foodId = (int)($input['food_id'] ?? 0);
$userId = $_SESSION['user_id'];

$cart = new Cart();
$ok = $cart->remove($userId, $foodId);
echo json_encode(['success' => $ok]);
