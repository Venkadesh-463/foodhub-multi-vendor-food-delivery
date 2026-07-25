<?php
/**
 * models/User.php
 */
require_once __DIR__ . '/../config/database.php';

class User {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => strtolower(trim($email))]);
        return $stmt->fetch() ?: null;
    }

    public function register(string $name, string $email, string $password, string $phone = '', string $address = '', string $role = 'user'): array {
        $existing = $this->findByEmail($email);
        if ($existing) {
            return ['success' => false, 'message' => 'Email already registered.'];
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, phone, address, role, status) VALUES (:name, :email, :pass, :phone, :address, :role, 'active')");
        $ok = $stmt->execute([
            ':name' => $name,
            ':email' => strtolower(trim($email)),
            ':pass' => $hash,
            ':phone' => $phone,
            ':address' => $address,
            ':role' => $role
        ]);
        return ['success' => $ok, 'message' => $ok ? 'User registered' : 'Registration failed'];
    }

    public function login(string $email, string $password): array {
        $user = $this->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
        if ($user['status'] !== 'active') {
            return ['success' => false, 'message' => 'Account is suspended.'];
        }
        return ['success' => true, 'user' => $user];
    }

    public function updateProfile(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, phone = :phone, address = :address WHERE id = :id");
        return $stmt->execute([
            ':name' => $data['name'],
            ':phone' => $data['phone'] ?? '',
            ':address' => $data['address'] ?? '',
            ':id' => $id
        ]);
    }
}
