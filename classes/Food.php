<?php
require_once __DIR__ . '/../config/database.php';

class Food {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($restaurantId = null, $categoryId = null, $featuredOnly = false, $search = '') {
        $query = "SELECT f.*, r.name as restaurant_name, c.name as category_name 
                  FROM food_items f 
                  JOIN restaurants r ON f.restaurant_id = r.id 
                  JOIN categories c ON f.category_id = c.id 
                  WHERE f.status = 'available'";
        $params = [];

        if ($restaurantId) {
            $query .= " AND f.restaurant_id = ?";
            $params[] = $restaurantId;
        }

        if ($categoryId) {
            $query .= " AND f.category_id = ?";
            $params[] = $categoryId;
        }

        if ($featuredOnly) {
            $query .= " AND f.is_featured = 1";
        }

        if ($search) {
            $query .= " AND (f.name LIKE ? OR f.description LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        $query .= " ORDER BY f.id DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT f.*, r.name as restaurant_name, r.delivery_fee, r.delivery_time, c.name as category_name 
                                   FROM food_items f 
                                   JOIN restaurants r ON f.restaurant_id = r.id 
                                   JOIN categories c ON f.category_id = c.id 
                                   WHERE f.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getCategories() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create($restaurant_id, $category_id, $name, $description, $price, $image = 'default-food.jpg', $is_veg = 0, $is_featured = 0) {
        $stmt = $this->db->prepare("INSERT INTO food_items (restaurant_id, category_id, name, description, price, image, is_veg, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$restaurant_id, $category_id, $name, $description, $price, $image, $is_veg, $is_featured]);
    }

    public function update($id, $category_id, $name, $description, $price, $image = null, $is_veg = 0, $is_featured = 0, $status = 'available') {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE food_items SET category_id=?, name=?, description=?, price=?, image=?, is_veg=?, is_featured=?, status=? WHERE id=?");
            return $stmt->execute([$category_id, $name, $description, $price, $image, $is_veg, $is_featured, $status, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE food_items SET category_id=?, name=?, description=?, price=?, is_veg=?, is_featured=?, status=? WHERE id=?");
            return $stmt->execute([$category_id, $name, $description, $price, $is_veg, $is_featured, $status, $id]);
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM food_items WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
