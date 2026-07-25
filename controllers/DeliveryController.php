<?php
/**
 * controllers/DeliveryController.php & PaymentController & TrackingController & AdminController
 */
require_once __DIR__ . '/../config/constants.php';

class DeliveryController {
    public function dashboard(): array {
        return ['status' => 'active'];
    }
}

class PaymentController {
    public function process(): array {
        return ['success' => true];
    }
}

class TrackingController {
    public function track(int $orderId): array {
        return ['order_id' => $orderId, 'status' => 'on_the_way'];
    }
}

class AdminController {
    public function dashboard(): array {
        return ['users' => 100, 'orders' => 500];
    }
}
