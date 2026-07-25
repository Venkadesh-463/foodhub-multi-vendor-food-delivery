<?php
/**
 * services/NotificationService.php
 * Creates, retrieves, and manages in-app notifications.
 * Also provides email and SMS hooks (stubs — wire to real providers in app.php).
 */
require_once __DIR__ . '/../config/database.php';

class NotificationService {

    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureTableExists();
    }

    /* ── Create a notification ──────────────────────────── */

    /**
     * Send an in-app notification to one user.
     *
     * @param int    $userId
     * @param string $title
     * @param string $message
     * @param string $type    order|payment|delivery|promo|system
     * @param int|null $referenceId  e.g. order_id for quick linking
     */
    public function send(int $userId, string $title, string $message, string $type = 'system', ?int $referenceId = null): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO notifications (user_id, title, message, type, reference_id, is_read, created_at)
             VALUES (:user_id, :title, :message, :type, :ref_id, 0, NOW())"
        );
        return $stmt->execute([
            ':user_id' => $userId,
            ':title'   => $title,
            ':message' => $message,
            ':type'    => $type,
            ':ref_id'  => $referenceId,
        ]);
    }

    /**
     * Broadcast a notification to all users with a specific role.
     *
     * @param string $role  user|restaurant|delivery|admin
     */
    public function broadcast(string $role, string $title, string $message, string $type = 'system'): int {
        $ids  = $this->db->query("SELECT id FROM users WHERE role = " . $this->db->quote($role) . " AND status = 'active'")->fetchAll(\PDO::FETCH_COLUMN);
        $sent = 0;
        foreach ($ids as $uid) {
            if ($this->send((int)$uid, $title, $message, $type)) $sent++;
        }
        return $sent;
    }

    /* ── Retrieve notifications ─────────────────────────── */

    public function getForUser(int $userId, int $limit = 20, int $offset = 0): array {
        $stmt = $this->db->prepare(
            "SELECT *, TIMESTAMPDIFF(SECOND, created_at, NOW()) AS seconds_ago
             FROM notifications
             WHERE user_id = :uid
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':uid',    $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $limit,  \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            $row['time_ago'] = $this->secondsToAgo((int)$row['seconds_ago']);
        }
        return $rows;
    }

    public function unreadCount(int $userId): int {
        return (int)$this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :uid AND is_read = 0")
            ->execute([':uid' => $userId]) ? $this->db->query("SELECT FOUND_ROWS()")->fetchColumn() : 0;
    }

    /* ── Mark read ─────────────────────────────────────── */

    public function markRead(int $id, int $userId): bool {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :uid");
        return $stmt->execute([':id' => $id, ':uid' => $userId]);
    }

    public function markAllRead(int $userId): bool {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :uid AND is_read = 0");
        return $stmt->execute([':uid' => $userId]);
    }

    /* ── Email (stub — wire PHPMailer in production) ────── */

    public function sendEmail(string $to, string $subject, string $htmlBody): bool {
        // TODO: Replace with PHPMailer implementation using constants from config/app.php
        error_log("[NotificationService] Email to $to | Subject: $subject");
        return true;
    }

    /* ── SMS (stub — wire Twilio in production) ─────────── */

    public function sendSMS(string $phone, string $message): bool {
        // TODO: Replace with Twilio SDK using TWILIO_SID / TWILIO_TOKEN from config/app.php
        error_log("[NotificationService] SMS to $phone | Message: $message");
        return true;
    }

    /* ── Private helpers ────────────────────────────────── */

    private function secondsToAgo(int $seconds): string {
        if ($seconds < 60)     return 'just now';
        if ($seconds < 3600)   return floor($seconds / 60)   . ' min ago';
        if ($seconds < 86400)  return floor($seconds / 3600)  . ' hr ago';
        if ($seconds < 604800) return floor($seconds / 86400) . ' days ago';
        return 'over a week ago';
    }

    private function ensureTableExists(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `notifications` (
                `id`           INT AUTO_INCREMENT PRIMARY KEY,
                `user_id`      INT NOT NULL,
                `title`        VARCHAR(200) NOT NULL,
                `message`      TEXT NOT NULL,
                `type`         ENUM('order','payment','delivery','promo','system') DEFAULT 'system',
                `reference_id` INT DEFAULT NULL,
                `is_read`      TINYINT(1) DEFAULT 0,
                `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_read (user_id, is_read)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }
}
