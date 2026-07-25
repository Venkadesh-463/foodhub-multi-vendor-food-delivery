<?php
/**
 * middleware/AdminMiddleware.php
 * Guards pages accessible only by platform administrators (role = 'admin').
 */
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class AdminMiddleware extends AuthMiddleware {
    public static function handle(): void {
        parent::handle();
        requireRole(ROLE_ADMIN);
    }
}
