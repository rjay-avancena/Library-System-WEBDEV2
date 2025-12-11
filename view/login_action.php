<?php
require_once __DIR__ . '/../controller/AuthController.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $auth = new AuthController();
    $role = $auth->login($email, $password);
    if ($role) {
        header('Location: dashboard.php');
        exit();
    } else {
        header('Location: login.php?msg=Invalid credentials');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
