<?php
/**
 * models/Restaurant.php
 * DB operations for the restaurants table.
 */
require_once __DIR__ . '/../config/database.php';

class Restaurant {

    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /** All approved restaurants with optional search and filters. */
    public function getAll(array $filters = [], int $limit = 20, int $offset = 0): array {
        $where  = ["r.status = 'approved'"];
        $params = [];

        if (!empty($filters['search'])) {
            $where[]         = "(r.name LIKE :search OR r.cuisine LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['cuisine'])) {
            $where[]          = "r.cuisine LIKE :cuisine";
            $params[':cuisine'] = '%' . $filters['cuisine'] . '%';
        }
        if (!empty($filters['max_fee'])) {
            $where[]          = "r.delivery_fee <= :max_fee";
            $params[':max_fee'] = $filters['max_fee'];
        }

        $sql = "SELECT r.*, u.name AS owner_name FROM restaurants r
                JOIN users u ON u.id = r.user_id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY r.rating DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT r.*, u.name AS owner_name FROM restaurants r JOIN users u ON u.id = r.user_id WHERE r.id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByUserId(int $userId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM restaurants WHERE user_id = :uid LIMIT 1");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO restaurants (user_id, name, cuisine, phone, address, delivery_time, delivery_fee, min_order, image, description, status)
             VALUES (:uid, :name, :cuisine, :phone, :address, :del_time, :del_fee, :min_order, :image, :desc, 'pending')"
        );
        $ok = $stmt->execute([
            ':uid'      => $data['user_id'],
            ':name'     => $data['name'],
            ':cuisine'  => $data['cuisine'],
            ':phone'    => $data['phone']    ?? '',
            ':address'  => $data['address'],
            ':del_time' => $data['delivery_time'] ?? '25-35 min',
            ':del_fee'  => $data['delivery_fee']  ?? 2.99,
            ':min_order'=> $data['min_order'] ?? 10.00,
            ':image'    => $data['image']    ?? 'default-restaurant.jpg',
            ':desc'     => $data['description'] ?? '',
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $allowed = ['name','cuisine','phone','address','delivery_time','delivery_fee','min_order','image','banner','description','status'];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) $fields[] = "$col = :$col";
        }
        if (empty($fields)) return false;
        $stmt = $this->db->prepare("UPDATE restaurants SET " . implode(', ', $fields) . " WHERE id = :id");
        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) $stmt->bindValue(":$col", $data[$col]);
        }
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateStatus(int $id, string $status): bool {
        return $this->db->prepare("UPDATE restaurants SET status = :s WHERE id = :id")->execute([':s' => $status, ':id' => $id]);
    }

    public function count(string $status = 'approved'): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM restaurants WHERE status = '$status'")->fetchColumn();
    }

    public function getTotalRevenue(int $restaurantId): float {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(final_amount),0) FROM orders WHERE restaurant_id = :rid AND order_status = 'delivered'");
        $stmt->execute([':rid' => $restaurantId]);
        return (float)$stmt->fetchColumn();
    }
}
