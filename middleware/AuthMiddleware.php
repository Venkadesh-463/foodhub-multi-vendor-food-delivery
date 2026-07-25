<?php
/**
 * middleware/AuthMiddleware.php
 * Base middleware — verifies any authenticated user.
 * Extend this for role-specific middleware.
 *
 * Usage (top of any protected page):
 *   require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
 *   AuthMiddleware::handle();
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/auth.php';

class AuthMiddleware {

    /**
     * Ensure the visitor is logged in.
     * Redirects to login page with the intended URL stored in session.
     */
    public static function handle(): void {
        if (!isLoggedIn()) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '';
            flash('error', 'Please log in to continue.', 'danger');
            redirect(BASE_URL . 'login.php');
        }
    }
}
