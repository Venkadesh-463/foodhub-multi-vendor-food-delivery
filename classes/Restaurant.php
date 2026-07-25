<?php
require_once __DIR__ . '/../config/database.php';

class Restaurant {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($status = 'approved', $cuisine = '', $search = '') {
        $query = "SELECT r.*, u.name as owner_name FROM restaurants r JOIN users u ON r.user_id = u.id WHERE 1=1";
        $params = [];

        if ($status) {
            $query .= " AND r.status = ?";
            $params[] = $status;
        }

        if ($cuisine) {
            $query .= " AND r.cuisine LIKE ?";
            $params[] = '%' . $cuisine . '%';
        }

        if ($search) {
            $query .= " AND (r.name LIKE ? OR r.description LIKE ? OR r.cuisine LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        $query .= " ORDER BY r.rating DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT r.*, u.name as owner_name, u.email as owner_email FROM restaurants r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM restaurants WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function create($userId, $name, $cuisine, $phone, $address, $delivery_time, $delivery_fee, $min_order, $description, $image = 'default-restaurant.jpg') {
        $stmt = $this->db->prepare("INSERT INTO restaurants (user_id, name, cuisine, phone, address, delivery_time, delivery_fee, min_order, description, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved')");
        return $stmt->execute([$userId, $name, $cuisine, $phone, $address, $delivery_time, $delivery_fee, $min_order, $description, $image]);
    }

    public function update($id, $name, $cuisine, $phone, $address, $delivery_time, $delivery_fee, $min_order, $description, $image = null) {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE restaurants SET name=?, cuisine=?, phone=?, address=?, delivery_time=?, delivery_fee=?, min_order=?, description=?, image=? WHERE id=?");
            return $stmt->execute([$name, $cuisine, $phone, $address, $delivery_time, $delivery_fee, $min_order, $description, $image, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE restaurants SET name=?, cuisine=?, phone=?, address=?, delivery_time=?, delivery_fee=?, min_order=?, description=? WHERE id=?");
            return $stmt->execute([$name, $cuisine, $phone, $address, $delivery_time, $delivery_fee, $min_order, $description, $id]);
        }
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE restaurants SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
