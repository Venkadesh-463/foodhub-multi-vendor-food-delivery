<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../classes/Order.php';

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function handleCheckout() {
        requireRole(ROLE_USER);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $address = sanitize($_POST['address'] ?? '');
            $paymentMethod = sanitize($_POST['payment_method'] ?? 'card');
            $instructions = sanitize($_POST['instructions'] ?? '');

            if (empty($address)) {
                flash('error', 'Delivery address is required.', 'danger');
                redirect(BASE_URL . 'user/checkout.php');
            }

            $res = $this->orderModel->createOrder($userId, $address, $paymentMethod, $instructions);

            if ($res['success']) {
                redirect(BASE_URL . 'user/order-success.php?id=' . $res['order_id']);
            } else {
                flash('error', $res['message'], 'danger');
                redirect(BASE_URL . 'user/checkout.php');
            }
        }
    }
}
