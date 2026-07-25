<?php
/**
 * controllers/RestaurantController.php
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/Restaurant.php';

class RestaurantController {
    private Restaurant $restaurantModel;

    public function __construct() {
        $this->restaurantModel = new Restaurant();
    }

    public function index(): array {
        return $this->restaurantModel->getAll();
    }

    public function show(int $id): ?array {
        return $this->restaurantModel->findById($id);
    }
}
