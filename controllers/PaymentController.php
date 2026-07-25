<?php
/**
 * controllers/PaymentController.php
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../services/PaymentService.php';

class PaymentController {
    private PaymentService $paymentService;

    public function __construct() {
        $this->paymentService = new PaymentService();
    }

    public function process(int $orderId, float $amount, string $method): array {
        return $this->paymentService->process($orderId, $amount, $method);
    }
}
