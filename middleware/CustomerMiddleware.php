<?php
/**
 * middleware/CustomerMiddleware.php
 * Guards pages that only regular customers (role = 'user') may access.
 *
 * Usage:
 *   require_once __DIR__ . '/../../middleware/CustomerMiddleware.php';
 *   CustomerMiddleware::handle();
 */
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class CustomerMiddleware extends AuthMiddleware {
    public static function handle(): void {
        parent::handle();
        requireRole(ROLE_USER);
    }
}
