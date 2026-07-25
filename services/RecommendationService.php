<?php
/**
 * services/RecommendationService.php
 * Returns personalised food recommendations for a customer.
 */
require_once __DIR__ . '/../config/database.php';

class RecommendationService {

    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Return up to $limit recommended food items for a user.
     * Strategy: items from restaurants the user has ordered before,
     * then fallback to featured/top-rated items.
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getForUser(int $userId, int $limit = 8): array {
        // 1. Items from previously ordered restaurants
        $personal = $this->personalised($userId, $limit);
        if (count($personal) >= $limit) return $personal;

        // 2. Fill remaining slots with popular items
        $existing = array_column($personal, 'id');
        $popular  = $this->popular($limit - count($personal), $existing);

        return array_merge($personal, $popular);
    }

    /**
     * Top-rated/featured items (guest or cold-start fallback).
     */
    public function getFeatured(int $limit = 8): array {
        $stmt = $this->db->prepare(
            "SELECT f.*, r.name AS restaurant_name, r.rating AS restaurant_rating
             FROM food_items f
             JOIN restaurants r ON r.id = f.restaurant_id
             WHERE f.is_featured = 1 AND f.status = 'available' AND r.status = 'approved'
             ORDER BY r.rating DESC, f.id DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* ── Private strategies ─────────────────────────────── */

    private function personalised(int $userId, int $limit): array {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT f.*, r.name AS restaurant_name, r.rating AS restaurant_rating
             FROM orders o
             JOIN order_items oi ON oi.order_id = o.id
             JOIN food_items f   ON f.id = oi.food_id
             JOIN restaurants r  ON r.id = f.restaurant_id
             WHERE o.user_id = :uid AND f.status = 'available' AND r.status = 'approved'
             ORDER BY o.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':uid',   $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit,  \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function popular(int $limit, array $excludeIds = []): array {
        $notIn = empty($excludeIds) ? '' : 'AND f.id NOT IN (' . implode(',', array_map('intval', $excludeIds)) . ')';
        $stmt  = $this->db->prepare(
            "SELECT f.*, r.name AS restaurant_name, r.rating AS restaurant_rating,
                    COUNT(oi.id) AS order_count
             FROM food_items f
             JOIN restaurants r  ON r.id = f.restaurant_id
             LEFT JOIN order_items oi ON oi.food_id = f.id
             WHERE f.status = 'available' AND r.status = 'approved' $notIn
             GROUP BY f.id
             ORDER BY order_count DESC, r.rating DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
