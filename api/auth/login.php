<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/AuthService.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

$auth = new AuthService();
$res = $auth->login($email, $password);
echo json_encode($res);
