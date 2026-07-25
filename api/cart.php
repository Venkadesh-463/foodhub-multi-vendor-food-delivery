<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../classes/Cart.php';

$cart = new Cart();
$userId = $_SESSION['user_id'] ?? null;
$action = $_GET['action'] ?? $_POST['action'] ?? 'get';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = $input['action'] ?? $action;
    $foodId = intval($input['food_id'] ?? 0);
    $quantity = intval($input['quantity'] ?? 1);

    if ($action === 'add') {
        $cart->addItem($userId, $foodId, $quantity);
        echo json_encode(['success' => true, 'message' => 'Item added to cart!', 'totals' => $cart->getTotals($userId)]);
        exit();
    } elseif ($action === 'update') {
        $cart->updateItem($userId, $foodId, $quantity);
        echo json_encode(['success' => true, 'message' => 'Cart updated!', 'totals' => $cart->getTotals($userId)]);
        exit();
    } elseif ($action === 'remove') {
        $cart->removeItem($userId, $foodId);
        echo json_encode(['success' => true, 'message' => 'Item removed!', 'totals' => $cart->getTotals($userId)]);
        exit();
    } elseif ($action === 'clear') {
        $cart->clearCart($userId);
        echo json_encode(['success' => true, 'message' => 'Cart cleared!', 'totals' => $cart->getTotals($userId)]);
        exit();
    }
}

echo json_encode(['success' => true, 'totals' => $cart->getTotals($userId)]);
