<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site Configuration
define('SITE_NAME', 'FoodHub');
define('SITE_SLOGAN', 'Delicious Food Delivered Fast & Fresh');
define('CURRENCY_SYMBOL', '$');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodhub_db');

// Root & Paths Dynamic Resolution
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

// Determine if we are inside FoodHub folder or root
if (strpos($script_name, '/FoodHub/') !== false) {
    $sub_dir = substr($script_name, 0, strpos($script_name, '/FoodHub/') + 9);
} else {
    $sub_dir = '/FoodHub/';
}

define('BASE_URL', $protocol . $host . $sub_dir);
define('ROOT_PATH', dirname(__DIR__) . '/');

// Roles Constants
define('ROLE_USER', 'user');
define('ROLE_RESTAURANT', 'restaurant');
define('ROLE_DELIVERY', 'delivery');
define('ROLE_ADMIN', 'admin');

// Helper Functions
if (!function_exists('sanitize')) {
    function sanitize($data) {
        if (is_array($data)) {
            return array_map('sanitize', $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        if (!headers_sent()) {
            header("Location: " . $url);
            exit();
        } else {
            echo "<script>window.location.href='" . $url . "';</script>";
            exit();
        }
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

if (!function_exists('getAuthUser')) {
    function getAuthUser() {
        if (isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['user_name'] ?? 'User',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? 'user',
                'avatar' => $_SESSION['user_avatar'] ?? 'default-avatar.jpg'
            ];
        }
        return null;
    }
}

if (!function_exists('requireRole')) {
    function requireRole($roles) {
        if (!isLoggedIn()) {
            $_SESSION['flash_error'] = "Please log in to access this page.";
            redirect(BASE_URL . 'login.php');
        }
        
        $allowed = is_array($roles) ? $roles : [$roles];
        $userRole = $_SESSION['user_role'] ?? '';
        
        if (!in_array($userRole, $allowed)) {
            $_SESSION['flash_error'] = "You do not have permission to access that area.";
            if ($userRole === ROLE_ADMIN) redirect(BASE_URL . 'admin/dashboard.php');
            elseif ($userRole === ROLE_RESTAURANT) redirect(BASE_URL . 'restaurant/dashboard.php');
            elseif ($userRole === ROLE_DELIVERY) redirect(BASE_URL . 'delivery/dashboard.php');
            else redirect(BASE_URL . 'user/dashboard.php');
        }
    }
}

if (!function_exists('flash')) {
    function flash($key, $message = '', $type = 'info') {
        if (!empty($message)) {
            $_SESSION['flash_' . $key] = ['message' => $message, 'type' => $type];
        } elseif (isset($_SESSION['flash_' . $key])) {
            $flash = $_SESSION['flash_' . $key];
            unset($_SESSION['flash_' . $key]);
            return $flash;
        }
        return null;
    }
}

if (!function_exists('getFoodImage')) {
    function getFoodImage($image) {
        if (empty($image)) {
            return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';
        }
        if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
            return $image;
        }
        if (file_exists(ROOT_PATH . 'uploads/food/' . $image)) {
            return BASE_URL . 'uploads/food/' . $image;
        }
        return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';
    }
}

if (!function_exists('getRestaurantImage')) {
    function getRestaurantImage($image) {
        if (empty($image)) {
            return 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80';
        }
        if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
            return $image;
        }
        if (file_exists(ROOT_PATH . 'uploads/restaurant/' . $image)) {
            return BASE_URL . 'uploads/restaurant/' . $image;
        }
        return 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80';
    }
}

// Currency & Country Switcher Processing
if (isset($_GET['set_curr'])) {
    $_SESSION['currency'] = sanitize($_GET['set_curr']);
}

if (!function_exists('getCurrencies')) {
    function getCurrencies() {
        return [
            'USD' => ['code' => 'USD', 'symbol' => '$', 'rate' => 1.0, 'flag' => '🇺🇸', 'name' => 'USA ($)'],
            'INR' => ['code' => 'INR', 'symbol' => '₹', 'rate' => 83.5, 'flag' => '🇮🇳', 'name' => 'India (₹)'],
            'EUR' => ['code' => 'EUR', 'symbol' => '€', 'rate' => 0.92, 'flag' => '🇪🇺', 'name' => 'Europe (€)'],
            'GBP' => ['code' => 'GBP', 'symbol' => '£', 'rate' => 0.78, 'flag' => '🇬🇧', 'name' => 'UK (£)'],
            'CAD' => ['code' => 'CAD', 'symbol' => 'CA$', 'rate' => 1.36, 'flag' => '🇨🇦', 'name' => 'Canada (CA$)'],
            'AUD' => ['code' => 'AUD', 'symbol' => 'A$', 'rate' => 1.50, 'flag' => '🇦🇺', 'name' => 'Australia (A$)'],
            'AED' => ['code' => 'AED', 'symbol' => 'AED ', 'rate' => 3.67, 'flag' => '🇦🇪', 'name' => 'UAE (AED)'],
        ];
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice($amount) {
        $selected = $_SESSION['currency'] ?? 'USD';
        $currencies = getCurrencies();
        $curr = $currencies[$selected] ?? $currencies['USD'];
        $converted = floatval($amount) * $curr['rate'];
        return $curr['symbol'] . number_format($converted, 2);
    }
}

// Shorthand: returns just the active currency symbol (e.g. for form labels)
if (!function_exists('currSymbol')) {
    function currSymbol() {
        $selected = $_SESSION['currency'] ?? 'USD';
        $currencies = getCurrencies();
        return $currencies[$selected]['symbol'] ?? '$';
    }
}

