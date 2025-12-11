<?php
require_once __DIR__ . '/../model/User.php';
session_start();
class AuthController {
    public function login($email, $password) {
        $userModel = new User();
        $user = $userModel->verifyPassword($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['User_ID'];
            $_SESSION['role'] = $user['Role'];
            return $user['Role'];
        }
        return false;
    }
    public function signup($data) {
        $userModel = new User();
        if ($userModel->findByEmail($data['email'])) {
            return 'Email already exists';
        }
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $userModel->create($data);
    }
    public function logout() {
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
