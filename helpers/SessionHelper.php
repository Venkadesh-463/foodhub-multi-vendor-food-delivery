<?php
/**
 * helpers/SessionHelper.php
 * Convenience wrappers around native PHP session functions.
 * Includes flash message management.
 */
require_once __DIR__ . '/../config/session.php';

class SessionHelper {

    /** Set a session key. */
    public static function set(string $key, mixed $value): void { setSession($key, $value); }

    /** Get a session value. */
    public static function get(string $key, mixed $default = null): mixed { return getSession($key, $default); }

    /** Check if a session key exists. */
    public static function has(string $key): bool { return hasSession($key); }

    /** Remove a session key. */
    public static function forget(string $key): void { removeSession($key); }

    /** Destroy the entire session. */
    public static function destroy(): void { destroySession(); }

    /**
     * Store a one-time flash message.
     * Retrieve and auto-delete it with self::getFlash().
     *
     * @param string $key   e.g. 'success', 'error', 'info'
     * @param string $msg
     * @param string $type  Bootstrap-style: 'success'|'danger'|'info'|'warning'
     */
    public static function flash(string $key, string $msg, string $type = 'info'): void {
        $_SESSION['flash_' . $key] = ['message' => $msg, 'type' => $type];
    }

    /**
     * Retrieve and delete a flash message. Returns null if not set.
     */
    public static function getFlash(string $key): ?array {
        if (isset($_SESSION['flash_' . $key])) {
            $flash = $_SESSION['flash_' . $key];
            unset($_SESSION['flash_' . $key]);
            return $flash;
        }
        return null;
    }

    /** True if a flash message for this key exists. */
    public static function hasFlash(string $key): bool {
        return isset($_SESSION['flash_' . $key]);
    }

    /**
     * Regenerate the session ID (call after successful login).
     */
    public static function regenerate(): void { regenerateSession(); }

    /**
     * Store the intended URL so the user can be redirected back after login.
     */
    public static function setIntendedUrl(string $url): void {
        self::set('intended_url', $url);
    }

    /**
     * Pull and return the intended URL, then clear it.
     */
    public static function pullIntendedUrl(string $default = ''): string {
        $url = self::get('intended_url', $default);
        self::forget('intended_url');
        return $url;
    }
}
