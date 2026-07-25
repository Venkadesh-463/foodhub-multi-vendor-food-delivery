<?php
require_once __DIR__ . '/../classes/Order.php';

class OrderModel extends Order {
    public function getAdminOrderStats() {
        $db = Database::getInstance()->getConnection();
        $totalRevenue = $db->query("SELECT SUM(final_amount) FROM orders WHERE payment_status = 'paid'")->fetchColumn() ?: 0.00;
        $totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $pendingOrders = $db->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetchColumn();
        $deliveredOrders = $db->query("SELECT COUNT(*) FROM orders WHERE order_status = 'delivered'")->fetchColumn();

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'delivered_orders' => $deliveredOrders
        ];
    }
}
