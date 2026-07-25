<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../classes/Food.php';
require_once __DIR__ . '/../classes/Restaurant.php';

class FoodController {
    private $foodModel;
    private $restaurantModel;

    public function __construct() {
        $this->foodModel = new Food();
        $this->restaurantModel = new Restaurant();
    }

    public function handleAddFood() {
        requireRole([ROLE_RESTAURANT, ROLE_ADMIN]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name'] ?? '');
            $category_id = intval($_POST['category_id'] ?? 0);
            $description = sanitize($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $is_veg = isset($_POST['is_veg']) ? 1 : 0;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            // Get restaurant id
            $restaurant = $this->restaurantModel->getByUserId($_SESSION['user_id']);
            $restaurant_id = $restaurant['id'] ?? 1;

            $imageName = 'default-food.jpg';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['image']['tmp_name'];
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $uploadDir = ROOT_PATH . 'uploads/food/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($fileTmp, $uploadDir . $imageName);
            }

            $res = $this->foodModel->create($restaurant_id, $category_id, $name, $description, $price, $imageName, $is_veg, $is_featured);

            if ($res) {
                flash('success', 'Food item added successfully!', 'success');
                redirect(BASE_URL . 'restaurant/manage-food.php');
            } else {
                flash('error', 'Failed to add food item.', 'danger');
                redirect(BASE_URL . 'restaurant/add-food.php');
            }
        }
    }
}
