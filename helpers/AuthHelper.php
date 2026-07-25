<?php
/**
 * helpers/AuthHelper.php
 * Thin wrappers over config/auth.php for convenience in views and controllers.
 * Re-exports the procedural auth functions as a class if desired.
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/auth.php';

class AuthHelper {

    /** True if a user is currently logged in. */
    public static function check(): bool {
        return isLoggedIn();
    }

    /** Return the logged-in user array or null. */
    public static function user(): ?array {
        return getAuthUser();
    }

    /** Current user ID or null. */
    public static function id(): ?int {
        return currentUserId();
    }

    /** Current user role or null. */
    public static function role(): ?string {
        return currentUserRole();
    }

    /** True if the current user has the given role. */
    public static function is(string $role): bool {
        return self::role() === $role;
    }

    /** True if the current user is an admin. */
    public static function isAdmin(): bool {
        return self::is(ROLE_ADMIN);
    }

    /** True if the current user is a restaurant owner. */
    public static function isRestaurant(): bool {
        return self::is(ROLE_RESTAURANT);
    }

    /** True if the current user is a delivery rider. */
    public static function isRider(): bool {
        return self::is(ROLE_DELIVERY);
    }

    /** True if the current user is a regular customer. */
    public static function isCustomer(): bool {
        return self::is(ROLE_USER);
    }

    /**
     * Redirect to dashboard appropriate for the logged-in user's role.
     * Call after login instead of hard-coding the path.
     */
    public static function redirectToDashboard(): void {
        $role = self::role();
        switch ($role) {
            case ROLE_ADMIN:      redirect(BASE_URL . 'admin/dashboard.php'); break;
            case ROLE_RESTAURANT: redirect(BASE_URL . 'restaurant/dashboard.php'); break;
            case ROLE_DELIVERY:   redirect(BASE_URL . 'delivery/dashboard.php'); break;
            default:              redirect(BASE_URL . 'user/dashboard.php');
        }
    }

    /**
     * Hash a plain-text password.
     *
     * @param string $password
     * @return string  Bcrypt hash
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify a plain-text password against a stored hash.
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /**
     * Generate a secure random token (for password reset, email verify, etc.)
     *
     * @param int $length  Byte length (result is 2x hex chars)
     */
    public static function generateToken(int $length = 32): string {
        return bin2hex(random_bytes($length));
    }
}
