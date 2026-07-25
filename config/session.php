<?php
/**
 * Session Configuration & Management
 * Handles all session lifecycle operations for FoodHub
 */

if (session_status() === PHP_SESSION_NONE) {
    // Secure session settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Lax');

    session_start();
}

/**
 * Regenerate session ID to prevent session fixation attacks.
 * Call this after a successful login.
 */
function regenerateSession(): void {
    session_regenerate_id(true);
}

/**
 * Completely destroy the current session.
 */
function destroySession(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}

/**
 * Set a value in the session.
 *
 * @param string $key   Session key
 * @param mixed  $value Value to store
 */
function setSession(string $key, mixed $value): void {
    $_SESSION[$key] = $value;
}

/**
 * Get a value from the session.
 *
 * @param string $key     Session key
 * @param mixed  $default Default value if key doesn't exist
 * @return mixed
 */
function getSession(string $key, mixed $default = null): mixed {
    return $_SESSION[$key] ?? $default;
}

/**
 * Remove a specific key from the session.
 *
 * @param string $key Session key to remove
 */
function removeSession(string $key): void {
    unset($_SESSION[$key]);
}

/**
 * Check if a specific session key exists.
 *
 * @param string $key Session key
 * @return bool
 */
function hasSession(string $key): bool {
    return isset($_SESSION[$key]);
}
