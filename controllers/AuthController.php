<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../classes/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $phone = sanitize($_POST['phone'] ?? '');
            $address = sanitize($_POST['address'] ?? '');
            $role = sanitize($_POST['role'] ?? 'user');

            if (empty($name) || empty($email) || empty($password)) {
                flash('error', 'Please fill in all required fields.', 'danger');
                redirect(BASE_URL . 'register.php');
            }

            if ($password !== $confirmPassword) {
                flash('error', 'Passwords do not match.', 'danger');
                redirect(BASE_URL . 'register.php');
            }

            $res = $this->userModel->register($name, $email, $password, $phone, $address, $role);
            if ($res['success']) {
                flash('success', 'Registration successful! You can now login.', 'success');
                redirect(BASE_URL . 'login.php');
            } else {
                flash('error', $res['message'], 'danger');
                redirect(BASE_URL . 'register.php');
            }
        }
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                flash('error', 'Please provide both email and password.', 'danger');
                redirect(BASE_URL . 'login.php');
            }

            $res = $this->userModel->login($email, $password);
            if ($res['success']) {
                $user = $res['user'];
                flash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!', 'success');

                // Redirect based on role
                switch ($user['role']) {
                    case ROLE_ADMIN:
                        redirect(BASE_URL . 'admin/dashboard.php');
                        break;
                    case ROLE_RESTAURANT:
                        redirect(BASE_URL . 'restaurant/dashboard.php');
                        break;
                    case ROLE_DELIVERY:
                        redirect(BASE_URL . 'delivery/dashboard.php');
                        break;
                    default:
                        redirect(BASE_URL . 'user/dashboard.php');
                        break;
                }
            } else {
                flash('error', $res['message'], 'danger');
                redirect(BASE_URL . 'login.php');
            }
        }
    }

    public function handleLogout() {
        session_unset();
        session_destroy();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        flash('success', 'You have been successfully logged out.', 'info');
        redirect(BASE_URL . 'login.php');
    }
}
