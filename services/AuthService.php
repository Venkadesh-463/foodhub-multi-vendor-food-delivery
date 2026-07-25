<?php
/**
 * services/AuthService.php
 * Business-logic layer for authentication operations.
 * Used by AuthController to keep controllers thin.
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';

class AuthService {

    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Register a new user.
     *
     * @param array $data  name, email, password, phone, address, role
     * @return array       ['success' => bool, 'message' => string, 'user' => array|null]
     */
    public function register(array $data): array {
        // Validation
        $valid = ValidationHelper::validate($data, [
            'name'     => 'required|minLength:2|maxLength:100',
            'email'    => 'required|email',
            'password' => 'required|minLength:8',
            'role'     => 'required|inList:user,restaurant,delivery',
        ]);
        if (!$valid) {
            $errors = ValidationHelper::errors();
            return ['success' => false, 'message' => implode(' ', array_merge(...array_values($errors)))];
        }

        // Duplicate email check
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => strtolower(trim($data['email']))]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'An account with this email already exists.'];
        }

        // Insert
        $ins = $this->db->prepare(
            "INSERT INTO users (name, email, password, phone, address, role, status, created_at)
             VALUES (:name, :email, :password, :phone, :address, :role, 'active', NOW())"
        );
        $ok = $ins->execute([
            ':name'     => sanitize($data['name']),
            ':email'    => strtolower(trim($data['email'])),
            ':password' => AuthHelper::hashPassword($data['password']),
            ':phone'    => sanitize($data['phone'] ?? ''),
            ':address'  => sanitize($data['address'] ?? ''),
            ':role'     => $data['role'],
        ]);

        if (!$ok) return ['success' => false, 'message' => 'Registration failed. Please try again.'];

        $userId = (int)$this->db->lastInsertId();
        $user   = $this->findById($userId);
        return ['success' => true, 'message' => 'Registration successful!', 'user' => $user];
    }

    /**
     * Authenticate a user by email and password.
     *
     * @return array ['success' => bool, 'message' => string, 'user' => array|null]
     */
    public function login(string $email, string $password): array {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password are required.'];
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => strtolower(trim($email))]);
        $user = $stmt->fetch();

        if (!$user || !AuthHelper::verifyPassword($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
        if ($user['status'] !== 'active') {
            return ['success' => false, 'message' => 'Your account is suspended. Please contact support.'];
        }

        // Write session
        loginUser($user);

        return ['success' => true, 'message' => 'Welcome back, ' . $user['name'] . '!', 'user' => $user];
    }

    /**
     * Initiate a password reset by generating a token.
     * (Email delivery is a stub — wire via NotificationService in production.)
     */
    public function requestPasswordReset(string $email): array {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email AND status = 'active' LIMIT 1");
        $stmt->execute([':email' => strtolower(trim($email))]);
        $user = $stmt->fetch();

        // Always return success to avoid email enumeration
        if (!$user) return ['success' => true, 'message' => 'If that email exists, a reset link has been sent.'];

        $token   = AuthHelper::generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->ensureResetTable();
        $this->db->prepare("DELETE FROM password_resets WHERE email = :email")->execute([':email' => strtolower($email)]);
        $this->db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :exp)")
            ->execute([':email' => strtolower($email), ':token' => $token, ':exp' => $expires]);

        // TODO: Send reset email via NotificationService::sendEmail()
        error_log("[AuthService] Password reset token for {$email}: $token");

        return ['success' => true, 'message' => 'If that email exists, a reset link has been sent.'];
    }

    /* ── Private helpers ────────────────────────────────── */

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    private function ensureResetTable(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `password_resets` (
                `email`      VARCHAR(150) NOT NULL,
                `token`      VARCHAR(64) NOT NULL,
                `expires_at` DATETIME NOT NULL,
                PRIMARY KEY (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }
}
