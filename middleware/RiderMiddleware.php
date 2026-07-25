<?php
/**
 * middleware/RiderMiddleware.php
 * Guards pages accessible only by delivery riders (role = 'delivery').
 */
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class RiderMiddleware extends AuthMiddleware {
    public static function handle(): void {
        parent::handle();
        requireRole(ROLE_DELIVERY);
    }
}
