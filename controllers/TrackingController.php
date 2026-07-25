<?php
/**
 * controllers/TrackingController.php
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../services/TrackingService.php';

class TrackingController {
    private TrackingService $trackingService;

    public function __construct() {
        $this->trackingService = new TrackingService();
    }

    public function getLocation(int $orderId): ?array {
        return $this->trackingService->getOrderRiderLocation($orderId);
    }
}
