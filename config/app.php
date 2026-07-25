<?php
/**
 * Application-level configuration
 * Environment settings, feature flags, and third-party API keys.
 * This file is the single source of truth for app-wide tuneable settings.
 *
 * !! IMPORTANT: Never commit real API keys to version control. !!
 * Use environment variables or a .env file in production.
 */

// ─── Environment ───────────────────────────────────────────────────────────────
define('APP_ENV', 'development');   // 'development' | 'production'
define('APP_DEBUG', APP_ENV === 'development');

// ─── Application Meta ──────────────────────────────────────────────────────────
define('APP_VERSION', '2.0.0');
define('APP_TIMEZONE', 'Asia/Kolkata');  // Adjust to your region

// Set timezone globally
date_default_timezone_set(APP_TIMEZONE);

// ─── Error Reporting ───────────────────────────────────────────────────────────
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__DIR__) . '/logs/errors.log');
}

// ─── File Upload Settings ──────────────────────────────────────────────────────
define('MAX_UPLOAD_SIZE',    5 * 1024 * 1024);  // 5 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('UPLOAD_PATH',    dirname(__DIR__) . '/uploads/');

// ─── Pagination ────────────────────────────────────────────────────────────────
define('ITEMS_PER_PAGE',     12);
define('ORDERS_PER_PAGE',    10);

// ─── Order & Delivery Settings ─────────────────────────────────────────────────
define('BASE_DELIVERY_FEE',    2.99);    // USD
define('TAX_RATE',             0.08);    // 8%
define('MAX_DELIVERY_RADIUS',  15);      // km

// ─── Payment Gateway (Stripe sandbox) ─────────────────────────────────────────
define('STRIPE_PUBLIC_KEY',  'pk_test_XXXXXXXXXXXXXXXXXXXXXXXXXXXX');
define('STRIPE_SECRET_KEY',  'sk_test_XXXXXXXXXXXXXXXXXXXXXXXXXXXX');
define('STRIPE_WEBHOOK_KEY', 'whsec_XXXXXXXXXXXXXXXXXXXXXXXXXXXX');

// ─── Maps / Geo API ────────────────────────────────────────────────────────────
define('GOOGLE_MAPS_API_KEY', 'YOUR_GOOGLE_MAPS_API_KEY_HERE');

// ─── Email / SMTP (PHPMailer) ──────────────────────────────────────────────────
define('MAIL_HOST',     'smtp.mailtrap.io');
define('MAIL_PORT',     587);
define('MAIL_USERNAME', 'your_mailtrap_user');
define('MAIL_PASSWORD', 'your_mailtrap_pass');
define('MAIL_FROM',     'no-reply@foodhub.com');
define('MAIL_FROM_NAME', SITE_NAME ?? 'FoodHub');

// ─── SMS / Twilio ──────────────────────────────────────────────────────────────
define('TWILIO_SID',   'ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
define('TWILIO_TOKEN', 'your_auth_token_here');
define('TWILIO_FROM',  '+1XXXXXXXXXX');

// ─── Feature Flags ─────────────────────────────────────────────────────────────
define('FEATURE_LIVE_TRACKING',    true);
define('FEATURE_PUSH_NOTIFY',      false);  // Enable when FCM is configured
define('FEATURE_RECOMMENDATIONS',  true);

// ─── Cache Settings ────────────────────────────────────────────────────────────
define('CACHE_ENABLED', false);   // Toggle file-based cache
define('CACHE_TTL',     300);     // Seconds (5 minutes)
define('CACHE_PATH',    dirname(__DIR__) . '/logs/cache/');
