<?php
/**
 * controllers/AdminController.php
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function getDashboardStats(): array {
        return $this->userModel->getUserStats();
    }
}
