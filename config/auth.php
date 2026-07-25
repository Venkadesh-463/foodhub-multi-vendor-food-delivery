<?php
/**
 * Authentication Guard Helpers
 * Central authentication utilities consumed by middleware and controllers.
 * Depends on: constants.php (for BASE_URL, role constants, isLoggedIn(), requireRole(), redirect())
 */

/**
 * Require the user to be authenticated; redirect to login if not.
 */
function requireAuth(): void {
    if (!isLoggedIn()) {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '';
        flash('error', 'Please log in to access that page.', 'danger');
        redirect(BASE_URL . 'login.php');
    }
}

/**
 * Redirect already-logged-in users away from guest-only pages (login, register).
 */
function redirectIfAuthenticated(): void {
    if (isLoggedIn()) {
        $role = $_SESSION['user_role'] ?? 'user';
        switch ($role) {
            case ROLE_ADMIN:      redirect(BASE_URL . 'admin/dashboard.php'); break;
            case ROLE_RESTAURANT: redirect(BASE_URL . 'restaurant/dashboard.php'); break;
            case ROLE_DELIVERY:   redirect(BASE_URL . 'delivery/dashboard.php'); break;
            default:              redirect(BASE_URL . 'user/dashboard.php');
        }
    }
}

/**
 * Guard: only customers (role = user) may access this page.
 */
function requireCustomer(): void {
    requireAuth();
    requireRole(ROLE_USER);
}

/**
 * Guard: only restaurant owners may access this page.
 */
function requireRestaurant(): void {
    requireAuth();
    requireRole(ROLE_RESTAURANT);
}

/**
 * Guard: only delivery riders may access this page.
 */
function requireDelivery(): void {
    requireAuth();
    requireRole(ROLE_DELIVERY);
}

/**
 * Guard: only admins may access this page.
 */
function requireAdmin(): void {
    requireAuth();
    requireRole(ROLE_ADMIN);
}

/**
 * Log the user in by writing all required session keys.
 *
 * @param array $user  Associative array from the users table
 */
function loginUser(array $user): void {
    $_SESSION['user_id']     = $user['id'];
    $_SESSION['user_name']   = $user['name'];
    $_SESSION['user_email']  = $user['email'];
    $_SESSION['user_role']   = $user['role'];
    $_SESSION['user_avatar'] = $user['avatar'] ?? 'default-avatar.png';
    $_SESSION['logged_in']   = true;

    // Restore intended URL if set
    $intended = $_SESSION['intended_url'] ?? '';
    unset($_SESSION['intended_url']);

    if (!empty($intended)) {
        redirect(BASE_URL . ltrim($intended, '/'));
    }
}

/**
 * Log the user out and clear all session data.
 */
function logoutUser(): void {
    session_unset();
    session_destroy();
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    flash('success', 'You have been successfully logged out.', 'info');
    redirect(BASE_URL . 'login.php');
}

/**
 * Return the currently authenticated user's ID or null.
 *
 * @return int|null
 */
function currentUserId(): ?int {
    return isLoggedIn() ? (int) $_SESSION['user_id'] : null;
}

/**
 * Return the currently authenticated user's role or null.
 *
 * @return string|null
 */
function currentUserRole(): ?string {
    return isLoggedIn() ? ($_SESSION['user_role'] ?? null) : null;
}
