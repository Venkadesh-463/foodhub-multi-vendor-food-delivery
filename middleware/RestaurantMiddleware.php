<?php
/**
 * middleware/RestaurantMiddleware.php
 * Guards pages accessible only by restaurant owners (role = 'restaurant').
 */
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class RestaurantMiddleware extends AuthMiddleware {
    public static function handle(): void {
        parent::handle();
        requireRole(ROLE_RESTAURANT);
    }
}
