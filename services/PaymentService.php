<?php
/**
 * services/PaymentService.php
 * Handles payment processing: COD, simulated card/UPI, and Stripe (stub).
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../services/NotificationService.php';

class PaymentService {

    private \PDO $db;
    private NotificationService $notif;

    public function __construct() {
        $this->db    = Database::getInstance()->getConnection();
        $this->notif = new NotificationService();
    }

    /**
     * Process a payment and record the transaction.
     *
     * @param int    $orderId
     * @param float  $amount
     * @param string $method   'cod' | 'card' | 'upi' | 'wallet'
     * @param array  $payload  Method-specific data (card token, UPI ID, etc.)
     * @return array ['success' => bool, 'transaction_id' => string|null, 'message' => string]
     */
    public function process(int $orderId, float $amount, string $method, array $payload = []): array {
        switch ($method) {
            case 'cod':    return $this->handleCOD($orderId, $amount);
            case 'card':   return $this->handleCard($orderId, $amount, $payload);
            case 'upi':    return $this->handleUPI($orderId, $amount, $payload);
            case 'wallet': return $this->handleWallet($orderId, $amount);
            default:       return ['success' => false, 'transaction_id' => null, 'message' => 'Unknown payment method.'];
        }
    }

    /* ── Payment handlers ───────────────────────────────── */

    private function handleCOD(int $orderId, float $amount): array {
        $txnId = 'COD-' . strtoupper(uniqid());
        $ok    = $this->recordPayment($orderId, $txnId, 'cod', $amount, 'pending');
        $this->updateOrderPaymentStatus($orderId, 'pending');
        $this->sendPaymentNotification($orderId, 'cod');
        return ['success' => $ok, 'transaction_id' => $txnId, 'message' => 'Cash on delivery confirmed.'];
    }

    private function handleCard(int $orderId, float $amount, array $payload): array {
        // TODO: Replace with real Stripe charge using STRIPE_SECRET_KEY from config/app.php
        $txnId = 'TXN-' . strtoupper(bin2hex(random_bytes(8)));
        $ok    = $this->recordPayment($orderId, $txnId, 'card', $amount, 'success');
        $this->updateOrderPaymentStatus($orderId, 'paid');
        $this->sendPaymentNotification($orderId, 'card');
        return ['success' => $ok, 'transaction_id' => $txnId, 'message' => 'Card payment successful.'];
    }

    private function handleUPI(int $orderId, float $amount, array $payload): array {
        $txnId = 'UPI-' . strtoupper(bin2hex(random_bytes(8)));
        $ok    = $this->recordPayment($orderId, $txnId, 'upi', $amount, 'success');
        $this->updateOrderPaymentStatus($orderId, 'paid');
        $this->sendPaymentNotification($orderId, 'upi');
        return ['success' => $ok, 'transaction_id' => $txnId, 'message' => 'UPI payment successful.'];
    }

    private function handleWallet(int $orderId, float $amount): array {
        $txnId = 'WLT-' . strtoupper(bin2hex(random_bytes(8)));
        $ok    = $this->recordPayment($orderId, $txnId, 'wallet', $amount, 'success');
        $this->updateOrderPaymentStatus($orderId, 'paid');
        $this->sendPaymentNotification($orderId, 'wallet');
        return ['success' => $ok, 'transaction_id' => $txnId, 'message' => 'Wallet payment successful.'];
    }

    /* ── Refund ─────────────────────────────────────────── */

    public function refund(int $orderId): array {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE order_id = :oid AND status = 'success' LIMIT 1");
        $stmt->execute([':oid' => $orderId]);
        $payment = $stmt->fetch();
        if (!$payment) return ['success' => false, 'message' => 'No successful payment found for this order.'];

        $upd = $this->db->prepare("UPDATE payments SET status = 'refunded' WHERE id = :id");
        $ok  = $upd->execute([':id' => $payment['id']]);
        // TODO: Trigger actual refund via Stripe / payment gateway
        return ['success' => $ok, 'message' => $ok ? 'Refund initiated.' : 'Refund failed.'];
    }

    /* ── Helpers ────────────────────────────────────────── */

    private function recordPayment(int $orderId, string $txnId, string $method, float $amount, string $status): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO payments (order_id, transaction_id, payment_method, amount, status, created_at)
             VALUES (:oid, :txn, :method, :amount, :status, NOW())"
        );
        return $stmt->execute([
            ':oid' => $orderId, ':txn' => $txnId,
            ':method' => $method, ':amount' => $amount, ':status' => $status,
        ]);
    }

    private function updateOrderPaymentStatus(int $orderId, string $status): void {
        $this->db->prepare("UPDATE orders SET payment_status = :s WHERE id = :id")->execute([':s' => $status, ':id' => $orderId]);
    }

    private function sendPaymentNotification(int $orderId, string $method): void {
        $order = $this->db->prepare("SELECT user_id FROM orders WHERE id = :id LIMIT 1");
        $order->execute([':id' => $orderId]);
        $row = $order->fetch();
        if ($row) {
            $this->notif->send((int)$row['user_id'], 'Payment Confirmed', "Payment for Order #$orderId via " . strtoupper($method) . " was successful.", 'payment', $orderId);
        }
    }
}
