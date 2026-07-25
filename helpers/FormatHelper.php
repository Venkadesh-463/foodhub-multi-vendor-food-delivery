<?php
/**
 * helpers/FormatHelper.php
 * Formatting utilities: dates, numbers, strings used throughout FoodHub.
 * All methods are static.
 */
class FormatHelper {

    /**
     * Human-readable time ago (e.g. "2 hours ago").
     */
    public static function timeAgo(string $datetime): string {
        $diff = time() - strtotime($datetime);
        if ($diff < 60)     return 'just now';
        if ($diff < 3600)   return floor($diff / 60)   . ' min ago';
        if ($diff < 86400)  return floor($diff / 3600)  . ' hr ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';
        return date('d M Y', strtotime($datetime));
    }

    /**
     * Format a date/time for display.
     *
     * @param string $datetime
     * @param string $format   PHP date format string
     */
    public static function dateTime(string $datetime, string $format = 'd M Y, h:i A'): string {
        return date($format, strtotime($datetime));
    }

    /**
     * Format a currency amount using the active session currency.
     * Wrapper around the global formatPrice() helper.
     */
    public static function price(float $amount): string {
        if (function_exists('formatPrice')) {
            return formatPrice($amount);
        }
        return '$' . number_format($amount, 2);
    }

    /**
     * Truncate a string to a given length, appending ellipsis if needed.
     */
    public static function truncate(string $text, int $length = 80, string $suffix = '…'): string {
        if (mb_strlen($text) <= $length) return $text;
        return mb_substr($text, 0, $length) . $suffix;
    }

    /**
     * Convert a database order_status key to a readable label.
     */
    public static function orderStatus(string $status): string {
        $map = [
            'pending'           => 'Order Placed',
            'preparing'         => 'Preparing',
            'ready_for_delivery'=> 'Ready for Pickup',
            'out_for_delivery'  => 'Out for Delivery',
            'delivered'         => 'Delivered',
            'cancelled'         => 'Cancelled',
        ];
        return $map[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * CSS badge class for an order status.
     */
    public static function orderStatusClass(string $status): string {
        $map = [
            'pending'           => 'badge-warning',
            'preparing'         => 'badge-info',
            'ready_for_delivery'=> 'badge-primary',
            'out_for_delivery'  => 'badge-primary',
            'delivered'         => 'badge-success',
            'cancelled'         => 'badge-danger',
        ];
        return $map[$status] ?? 'badge-secondary';
    }

    /**
     * Generate star HTML from a decimal rating (e.g. 4.5).
     */
    public static function stars(float $rating, int $max = 5): string {
        $html = '';
        for ($i = 1; $i <= $max; $i++) {
            if ($rating >= $i)      $html .= '<i class="fa-solid fa-star text-warning"></i>';
            elseif ($rating >= $i - 0.5) $html .= '<i class="fa-solid fa-star-half-stroke text-warning"></i>';
            else                    $html .= '<i class="fa-regular fa-star text-warning"></i>';
        }
        return $html;
    }

    /**
     * Return initials from a full name (up to 2 characters).
     */
    public static function initials(string $name): string {
        $words = preg_split('/\s+/', trim($name));
        if (count($words) >= 2) return strtoupper($words[0][0] . $words[1][0]);
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Format a file size in bytes to human-readable string.
     */
    public static function fileSize(int $bytes): string {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576,    2) . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024,       2) . ' KB';
        return $bytes . ' B';
    }

    /**
     * Format a phone number for display (basic, non-normalising).
     */
    public static function phone(string $phone): string {
        // Strips non-numeric chars except leading +
        return preg_replace('/[^\d+]/', '', $phone);
    }

    /**
     * Ordinal suffix for a number (1st, 2nd, 3rd…).
     */
    public static function ordinal(int $n): string {
        $suffix = ['th','st','nd','rd'];
        $v = $n % 100;
        return $n . ($suffix[($v - 20) % 10] ?? $suffix[min($v, 4)] ?? $suffix[0]);
    }
}
