<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../classes/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function handleProfileUpdate() {
        requireRole(['user', 'restaurant', 'delivery', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $name = sanitize($_POST['name'] ?? '');
            $phone = sanitize($_POST['phone'] ?? '');
            $address = sanitize($_POST['address'] ?? '');

            $avatarName = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['avatar']['tmp_name'];
                $fileName = time() . '_' . basename($_FILES['avatar']['name']);
                $uploadDir = ROOT_PATH . 'uploads/profile/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (move_uploaded_file($fileTmp, $uploadDir . $fileName)) {
                    $avatarName = $fileName;
                }
            }

            $res = $this->userModel->updateProfile($userId, $name, $phone, $address, $avatarName);
            if ($res) {
                flash('success', 'Profile updated successfully!', 'success');
            } else {
                flash('error', 'Failed to update profile.', 'danger');
            }
            redirect($_SERVER['HTTP_REFERER'] ?? BASE_URL . 'user/profile.php');
        }
    }
}
