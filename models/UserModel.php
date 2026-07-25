<?php
require_once __DIR__ . '/../classes/User.php';

class UserModel extends User {
    // Model wrapper inheriting core User methods and adding custom statistics/analytics queries
    public function getUserStats() {
        $db = Database::getInstance()->getConnection();
        $totalUsers = $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
        $totalRestaurants = $db->query("SELECT COUNT(*) FROM restaurants")->fetchColumn();
        $totalDrivers = $db->query("SELECT COUNT(*) FROM users WHERE role = 'delivery'")->fetchColumn();
        
        return [
            'total_users' => $totalUsers,
            'total_restaurants' => $totalRestaurants,
            'total_drivers' => $totalDrivers
        ];
    }
}
